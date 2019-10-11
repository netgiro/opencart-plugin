<?php
class ControllerExtensionPaymentNetgiroPost extends Controller {
	public function index() {
		
		$logger = new Log('payment_netgiro_post_debug.log'); 
		
		# Generic Init
		$extension_type 			= 'payment';
		$classname 					= 'payment_netgiro_post';
		$data['classname'] 			= $classname;		
		
		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		if ($this->config->get($classname . '_test')) {
			$data['action'] = 'https://test.netgiro.is/securepay';
		} else {
			$data['action'] = 'https://securepay.netgiro.is/v1/';
		}
		
		$data['testmode'] = $this->config->get($classname . '_test');
		
		$data['fields'] = array();
					
		# Error Check
		$data['error'] = (isset($this->session->data['error'])) ? $this->session->data['error'] : NULL;
		unset($this->session->data['error']);
		
		# Check for supported currency, otherwise convert to ISK.
		$supported_currencies = array('EUR','USD','ISK','GBP');
		if (in_array($order_info['currency_code'], $supported_currencies)) {
			$currency = $order_info['currency_code'];
		} else {
			$currency = 'ISK';
		}

		$amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['total'] = $amount;
		
		$cancelurl 		= $this->url->link('checkout/cart');
		#$callbackurl 	= $this->url->link('extension/' . $extension_type . '/' . $classname . '/callback', '', 'SSL');
		$callbackurl 	= $this->url->link('extension/payment/netgiro_post/callback', '', true);		
		$storename = ($this->config->get('config_name')) ? $this->config->get('config_name') : $this->config->get('config_store');

		$data['fields'] = array(
			'ApplicationID'				=> trim($this->config->get($classname . '_mid')),
			'Iframe'					=> 'true',
			'TotalAmount'				=> $amount,			
			'ReferenceNumber'			=> $order_info['order_id'],
			'ReturnCustomerInfo'		=> 'false',
			'PaymentSuccessfulURL' 		=> $callbackurl,
			'PaymentCancelledURL' 		=> $cancelurl,
		);
		
		$hash = (trim($this->config->get($classname . '_key')) . $order_info['order_id'] . $data['fields']['TotalAmount'] . $data['fields']['ApplicationID']);
		$data['fields']['Signature'] = hash('sha256', $hash);
		
		// Itemized Products
		$data['products'] = array();
		$products = $this->cart->getProducts();
		
		$sum = 0;
		$i = 0;
		foreach ($products as $product) {
			$product_price = str_replace(array(',','.'), '', $this->currency->format($product['price'], $currency, FALSE, FALSE));			                                                 
			$data['fields']["Items[$i].ProductNo"] = $product['model'];
			$data['fields']["Items[$i].Name"] = $product['name'];
			$data['fields']["Items[$i].Description"] = $product['name'];
			$data['fields']["Items[$i].UnitPrice"] = $product_price;
			$data['fields']["Items[$i].Amount"] = ($product_price * $product['quantity']);
			$data['fields']["Items[$i].Quantity"] = ($product['quantity'] * 1000);
			$i++;
			$sum += (float)$product_price * $product['quantity'];							
		}
		
		### Itemized Totals
		$dbtotals = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total where order_id = '" . $this->session->data['order_id'] . "' ORDER BY sort_order ASC");
		foreach ($dbtotals->rows as $dbtotal) {
			$total_price = str_replace(array(',','.'), '', $this->currency->format($dbtotal['value'], $currency, FALSE, FALSE));
			switch ($dbtotal['code']) {
				case 'sub_total':
				case 'total':
					break;
				case 'shipping':
					$data['fields']['ShippingAmount'] = ($total_price);
					break;
				case 'handling':
					$data['fields']['HandlingAmount'] = ($total_price);
					break;
				default:
					$data['fields']["Items[$i].ProductNo"] = $dbtotal['code'];
					$data['fields']["Items[$i].Name"] = $dbtotal['title'];
					$data['fields']["Items[$i].Description"] = $dbtotal['title'];
					$data['fields']["Items[$i].UnitPrice"] = $total_price;
					$data['fields']["Items[$i].Amount"] = $total_price * 1;
					$data['fields']["Items[$i].Quantity"] = '1000';
					$i++;
					$sum += (float)$total_price * 1;
					break;
			}
		}
		
		if ($this->config->get($classname . '_debug')) { 
			$logger->write("-----------------\r\nForm Fields: " . print_r($data['fields'], 1) . "\r\n" . PHP_EOL);
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/netgiro_post')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/netgiro_post', $data);
		} else {
			return $this->load->view('extension/payment/netgiro_post', $data);
		}			
	}

	public function callback() {
		
		$classname = 'payment_netgiro_post';
		
		if (!empty($this->session->data['order_id']) && !empty($this->request->get['orderid'])) {
			$order_id = $this->request->get['orderid'];
		} else {
			$order_id = 0;
		}
		
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		// If there is no order info then fail.
		if (!$order_info) {
			$this->session->data['error'] = $this->language->get('error_no_order');
			$this->response->redirect($this->url->link('checkout/failure'));
		}
	
		// If we get a successful response back...
		if (isset($this->request->get['confirmationCode'])) {
			
			if ($order_info) {
				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_netgiro_post_order_status_id'));				
			}	
			
			$html  = '<html><head><base target="_top"><script language="Javascript">parent.location="'. $this->url->link('checkout/success') . '"</script>';
			$html .= '</head><body><a href="' . $this->url->link('checkout/success') . '">--></a></body></html>';
			echo $html;
			exit();
		}
		
		if ($this->config->get($classname . '_debug')) { 
			$logger->write('ERROR IN CHECKOUT: ' . $this->language->get('error_process_order') . PHP_EOL);
		}
		
		$this->session->data['error'] = $this->language->get('error_process_order');
		$this->response->redirect($this->url->link('checkout/failure'));
	}
}