<?php
class ControllerPaymentNetgiroPost extends Controller {

	public function index() {

		# Generic Init
		$extension_type 			= 'payment';
		$classname 					= str_replace('vq2-' . basename(DIR_APPLICATION) . '_' . strtolower(get_parent_class($this)) . '_' . $extension_type . '_', '', basename(__FILE__, '.php'));
		$data['classname'] 			= $classname;
		$data 						= array_merge($data, $this->load->language($extension_type . '/' . $classname));

		# Form Fields
		if ($this->config->get($classname . '_test')) {
			$data['action'] = 'https://test.netgiro.is/securepay';
		} else {
			$data['action'] = 'https://securepay.netgiro.is/v1/';
		}
		$data['fields'] = array();

		# Order Info
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

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

		$amount = str_replace(array(',','.'), '', $this->currency->format($order_info['total'], $currency, FALSE, FALSE));
		$cancelurl 		= $this->url->link('checkout/cart');
		$callbackurl 	= $this->url->link($extension_type . '/' . $classname . '/callback', '', 'SSL');
		$storename = ($this->config->get('config_name')) ? $this->config->get('config_name') : $this->config->get('config_store');

		$data['fields'] = array(
			'ApplicationID'				=> trim($this->config->get($classname . '_mid')),
			'Iframe'					=> 'true',
			'TotalAmount'				=> $amount,
			'PaymentOption' 			=> $this->config->get($classname . '_payment_option'),
			'MaxNumberOfInstallments'	=> $this->config->get($classname . '_max_install'),
			'OrderId'					=> $order_info['order_id'],
			'ReturnCustomerInfo'		=> 'false',
			'PaymentSuccessfulURL' 		=> $callbackurl,
			'PaymentCancelledURL' 		=> $cancelurl,
		);

		$hash = (trim($this->config->get($classname . '_key')) . $data['fields']['OrderId'] . $data['fields']['TotalAmount'] . $data['fields']['ApplicationID']);
		$data['fields']['Signature'] = hash('sha256', $hash);

		// Itemized Products
		$sum = 0;
		$i = 0;
		foreach ($this->cart->getProducts() as $product) {
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
/*		
		### Combine totals as one line item
		$remain = $amount - $sum;
		$data['fields']["Items[$i].ProductNo"] = 'totals';
		$data['fields']["Items[$i].Name"] = 'Tax, Shipping, Fees, Discounts';
		$data['fields']["Items[$i].Description"] = 'Tax, Shipping, Fees, Discounts';
		$data['fields']["Items[$i].UnitPrice"] = $remain;
		$data['fields']["Items[$i].Amount"] = $remain;
		$data['fields']["Items[$i].Quantity"] = '1000';
*/

		// Debug
		if ($this->config->get($classname . '_debug')) { file_put_contents(DIR_LOGS . $classname . '_debug.txt', __FUNCTION__ . "-----------------\r\nForm Fields: " . print_r($data['fields'], 1) . "\r\n", FILE_APPEND); }
	
		$data['testmode'] = $this->config->get($classname . '_test');
		
		# Compatibility
		if (version_compare(VERSION, '2.0', '>=')) { // v2.0.x Compatibility
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $extension_type . '/'. $classname . '.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/' . $extension_type . '/'. $classname . '.tpl', $data);
			} else {
				return $this->load->view('default/template/' . $extension_type . '/'. $classname . '.tpl', $data);
			}
		} elseif (version_compare(VERSION, '2.0', '<')) {  // 1.5.x Backwards Compatibility
			$this->data = array_merge($this->data, $data);
			$this->id 	= 'payment';
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/' . $classname . '.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/' . $classname . '.tpl';
			} else {
				$this->template = 'default/template/payment/' . $classname . '.tpl';
			}
        	$this->render();
		}
	}
	

	private function fail($msg = false) {
		$failurl = $this->url->link('checkout/cart');
		if (!$msg) { $msg = (!empty($this->session->data['error']) ? $this->session->data['error'] : 'Unknown Error'); }
		echo '<html><head><script type="text/javascript">';
		echo 'alert("'.addslashes($msg).'");';
		echo 'window.location="' . $failurl . '";';
		echo '</script></head></html>';
		exit;
	}

	public function callback() {

		# Generic Init
		$extension_type 			= 'payment';
		$classname 					= str_replace('vq2-' . basename(DIR_APPLICATION) . '_' . strtolower(get_parent_class($this)) . '_' . $extension_type . '_', '', basename(__FILE__, '.php'));
		$data['classname'] 			= $classname;
		$data 						= array_merge($data, $this->load->language($extension_type . '/' . $classname));

		// Debug
		if ($this->config->get($classname . '_debug')) { file_put_contents(DIR_LOGS . $classname . '_debug.txt', __FUNCTION__ . "\r\n$classname GET: " . print_r($_GET,1) . "\r\n" . "$classname POST: " . print_r($_POST,1) . "\r\n"); }
		
		if (!empty($_REQUEST['orderid'])) {
			$order_id = $_REQUEST['orderid'];
		} else {
			$order_id = 0;
		}
		
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);

		// If there is no order info then fail.
		if (!$order_info) {
			$this->session->data['error'] = $this->language->get('error_no_order');
			$this->fail();
		}

		// If we get a successful response back...
		if (isset($_REQUEST['confirmationCode'])) {
			if (method_exists($this->model_checkout_order, 'addOrderHistory')) { // v20x
				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'), $order_info['comment'], true);
				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get($classname . '_order_status_id'), FALSE, false);
			} else { //v15x
				$this->model_checkout_order->confirm($order_info['order_id'], $this->config->get($classname . '_order_status_id'), $order_info['comment']);
				$this->model_checkout_order->update($order_info['order_id'], $this->config->get($classname . '_order_status_id'), FALSE, FALSE);
			}
			$html  = '<html><head><base target="_top"><script language="Javascript">parent.location="'. $this->url->link('checkout/success') . '"</script>';
			$html .= '</head><body><a href="' . $this->url->link('checkout/success') . '">--></a></body></html>';
			print $html;
			exit;
		} else {
			$this->session->data['error'] = $this->language->get('error_invalid');
		}
		$this->log->write("$classname: ERROR for order id: $order_id  :: " . $this->session->data['error']);
        $this->fail();
	}
}
?>