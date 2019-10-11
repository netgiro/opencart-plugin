<?php
class ControllerPaymentNetgiroPost extends Controller {

	protected function index() {

		# Generic Init
		$classname = str_replace('vq2-catalog_controller_payment_', '', basename(__FILE__, '.php'));
		$store_url = ($this->config->get('config_ssl') ? (is_numeric($this->config->get('config_ssl'))) ? str_replace('http', 'https', $this->config->get('config_url')) : $this->config->get('config_ssl') : $this->config->get('config_url'));
		$this->data['classname'] = $classname;
		$this->data = array_merge($this->data, $this->load->language('payment/' . $classname));
		$this->data['testmode'] = $this->config->get($classname . '_test');


		# Form Fields
		if ($this->config->get($classname . '_test')) {
			$this->data['action'] = 'https://test.netgiro.is/securepay';
		} else {
			$this->data['action'] = 'https://securepay.netgiro.is/v1';
		}
		$this->data['fields'] = array();
		$this->data['fields']['hidden'] = array();

		$this->send();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/' . $classname . '.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/' . $classname . '.tpl';
		} else {
			$this->template = 'default/template/payment/' . $classname . '.tpl';
		}

		$this->id       = 'payment';

		$this->render();
	}

	public function send() {

		# Generic Init
		$classname = str_replace('vq2-catalog_controller_payment_', '', basename(__FILE__, '.php'));
		$store_url = ($this->config->get('config_ssl') ? (is_numeric($this->config->get('config_ssl'))) ? str_replace('http', 'https', $this->config->get('config_url')) : $this->config->get('config_ssl') : $this->config->get('config_url'));
		$this->data['classname'] = $classname;
		$this->data = array_merge($this->data, $this->load->language('payment/' . $classname));


		# Order Info
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);


		# v14x Backwards Compatibility
		if (isset($order_info['currency_code'])) { $order_info['currency'] = $order_info['currency_code']; }
		if (isset($order_info['currency_value'])) { $order_info['value'] = $order_info['currency_value']; }


		# Data manipulation

		$currencies = array(
			'USD' => 'USD',
			'GBP' => 'GBP',
		);
		if (in_array($order_info['currency'], $currencies)) {
			$currency = $currencies[$order_info['currency']];
		} else {
			$currency = 'GBP';
		}

		$currency = $order_info['currency'];

		$amount = str_replace(array(',','.'), '', $this->currency->format($order_info['total'], $currency, FALSE, FALSE));
		$callback = ($store_url . 'index.php?route=payment/' . $classname . '/callback');
		$cancelurl = ($store_url . 'index.php?route=checkout/cart');
		$storename = ($this->config->get('config_name')) ? $this->config->get('config_name') : $this->config->get('config_store');

		$this->data['fields'] = array(
			'ApplicationID'				=> trim($this->config->get($classname . '_mid')),
			'Iframe'					=> 'true',
			'TotalAmount'				=> $amount,
			'PaymentOption' 			=> $this->config->get($classname . '_payment_option'),
			'MaxNumberOfInstallments'	=> $this->config->get($classname . '_max_install'),
			'OrderId'					=> $order_info['order_id'],
			'ReturnCustomerInfo'		=> 'false',
			'PaymentSuccessfulURL' 		=> $callback,
			'PaymentCancelledURL' 		=> $cancelurl,
		);

		$hash = (trim($this->config->get($classname . '_key')) . $this->data['fields']['OrderId'] . $this->data['fields']['TotalAmount'] . $this->data['fields']['ApplicationID']);
		$this->data['fields']['Signature'] = hash('sha256', $hash);

		// Itemized Products
		$i = 0;
		foreach ($this->cart->getProducts() as $product) {
			$product_price = str_replace(array(',','.'), '', $this->currency->format($product['price'], $currency, FALSE, FALSE));
			$this->data['fields']["Items[$i].ProductNo"] = $product['model'];
			$this->data['fields']["Items[$i].Name"] = $product['name'];
			$this->data['fields']["Items[$i].Description"] = $product['name'];
			$this->data['fields']["Items[$i].UnitPrice"] = $product_price;
			$this->data['fields']["Items[$i].Amount"] = ($product_price * $product['quantity']);
			$this->data['fields']["Items[$i].Quantity"] = ($product['quantity'] * 1000);
			$i++;
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
					$this->data['fields']['ShippingAmount'] = ($total_price);
					break;
				case 'handling':
					$this->data['fields']['HandlingAmount'] = ($total_price);
					break;
				default:
					$this->data['fields']["Items[$i].ProductNo"] = $dbtotal['code'];
					$this->data['fields']["Items[$i].Name"] = $dbtotal['title'];
					$this->data['fields']["Items[$i].Description"] = $dbtotal['title'];
					$this->data['fields']["Items[$i].UnitPrice"] = $total_price;
					$this->data['fields']["Items[$i].Amount"] = $total_price;
					$this->data['fields']["Items[$i].Quantity"] = '1000';
					$i++;
					break;
			}
		}


		// Debug
		if ($this->config->get($classname . '_debug')) { file_put_contents(DIR_LOGS . $classname . '_debug.txt', __FUNCTION__ . "-----------------\r\nForm Fields: " . print_r($this->data['fields'], 1) . "\r\n", FILE_APPEND); }

		$this->data['error'] =(isset($this->session->data['error'])) ? $this->session->data['error'] : NULL;
		unset($this->session->data['error']);

		/*
		$this->id       = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/' . $classname . '.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/' . $classname . '.tpl';
        } else {
            $this->template = 'default/template/payment/' . $classname . '.tpl';
        }

		$this->render();
		*/
	}


	public function callback() {

		$classname = str_replace('vq2-catalog_controller_payment_', '', basename(__FILE__, '.php'));
		$store_url = ($this->config->get('config_ssl') ? (is_numeric($this->config->get('config_ssl'))) ? str_replace('http', 'https', $this->config->get('config_url')) : $this->config->get('config_ssl') : $this->config->get('config_url'));
		$this->data = array_merge($this->data, $this->load->language('payment/' . $classname));

		// Debug
		if ($this->config->get($classname . '_debug')) { file_put_contents(DIR_LOGS . $classname . '_debug.txt', __FUNCTION__ . "\r\n$classname GET: " . print_r($_GET,1) . "\r\n" . "$classname POST: " . print_r($_POST,1) . "\r\n"); }

		$this->load->model('checkout/order');

		if (!empty($_REQUEST['orderid'])) {
			$order_id = $_REQUEST['orderid'];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_checkout_order->getOrder($order_id);

		// If there is no order info then fail.
		if (!$order_info) {
			$this->session->data['error'] = $this->language->get('error_no_order');
			$this->fail();
		}

		// If we get a successful response back...
		if (isset($_REQUEST['confirmationCode'])) {
				//$this->redirect($store_url . 'index.php?route=checkout/success');
				$this->model_checkout_order->confirm($order_id, $this->config->get($classname . '_order_status_id'));
				$this->model_checkout_order->update($order_id, $this->config->get($classname . '_order_status_id'), print_r($_REQUEST,1), false);
				$html  = '<html><head><base target="_top"><script language="Javascript">parent.location="'. ($store_url . 'index.php?route=checkout/success') . '"</script>';
				$html .= '</head><body><a href="'.$store_url.'index.php?route=checkout/success">--></a></body></html>';
				print $html;
				exit;
		} else {
			$this->session->data['error'] = $this->language->get('error_invalid');
		}
		$this->log->write("$classname: ERROR for order id: $order_id  :: " . $this->session->data['error']);
        $this->fail();
	}

	private function fail($msg = false) {
		$store_url = ($this->config->get('config_ssl') ? (is_numeric($this->config->get('config_ssl'))) ? str_replace('http', 'https', $this->config->get('config_url')) : $this->config->get('config_ssl') : $this->config->get('config_url'));
		if (!$msg) { $msg = (!empty($this->session->data['error']) ? $this->session->data['error'] : 'Unknown Error'); }
		if (method_exists($this->document, 'addBreadcrumb')) { //1.4.x
			$this->redirect((isset($this->session->data['guest'])) ? ($store_url . 'index.php?route=checkout/guest_step_3') : ($store_url . 'index.php?route=checkout/confirm'));
		} else {
			echo '<html><head><script type="text/javascript">';
			echo 'alert("'.addslashes($msg).'");';
			echo 'parent.location="' . ($store_url  . 'index.php?route=checkout/checkout') . '";';
			echo '</script></head></html>';
		}
		exit;
	}
}
?>