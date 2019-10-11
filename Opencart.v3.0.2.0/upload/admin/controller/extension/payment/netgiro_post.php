<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

class ControllerExtensionPaymentNetgiroPost extends Controller {
	private $error = array();

	public function index() {

		$classname = 'payment_netgiro_post';
		$this->load->language('extension/payment/netgiro_post');
		
		$this->document->setTitle($this->language->get('heading_title'));
	
		$logger = new Log('payment_netgiro_post_debug.log'); 
		if ($this->config->get($classname . '_debug')) { 
			$logger->write('Debug: Netgiro Setttings view opened' . PHP_EOL);
		}		
	
		$errors = array(
			'warning'
		);

		$extension_type = 'payment';
		$classname = str_replace('vq2-' . basename(DIR_APPLICATION) . '_' . strtolower(get_parent_class($this)) . '_' . $extension_type . '_', '', basename(__FILE__, '.php'));

		if (!isset($this->session->data['user_token'])) { $this->session->data['user_token'] = 0; }

		$data['classname'] = $classname;
		$data['user_token'] = $this->session->data['user_token'];
		$data = array_merge($data, $this->load->language('extension/' . $extension_type . '/' . $classname));

		if (isset($data['error_fields'])) {
			$errors = array_merge(explode(",", $data['error_fields']), $errors);
		}		

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_netgiro_post', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['mid'])) {
			$data['error_mid'] = $this->error['mid'];
		} else {
			$data['error_mid'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/twocheckout', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/netgiro_post', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		
		if (isset($this->request->post['payment_netgiro_post_mid'])) {
			$data['payment_netgiro_post_mid'] = $this->request->post['payment_netgiro_post_mid'];
		} else {
			$data['payment_netgiro_post_mid'] = $this->config->get('payment_netgiro_post_mid');
		}

		if (isset($this->request->post['payment_netgiro_post_key'])) {
			$data['payment_netgiro_post_key'] = $this->request->post['payment_netgiro_post_key'];
		} else {
			$data['payment_netgiro_post_key'] = $this->config->get('payment_netgiro_post_key');
		}

		if (isset($this->request->post['payment_netgiro_post_test'])) {
			$data['payment_netgiro_post_test'] = $this->request->post['payment_netgiro_post_test'];
		} else {
			$data['payment_netgiro_post_test'] = $this->config->get('payment_netgiro_post_test');
		}
		
		if (isset($this->request->post['payment_netgiro_post_debug'])) {
			$data['payment_netgiro_post_debug'] = $this->request->post['payment_netgiro_post_debug'];
		} else {
			$data['payment_netgiro_post_debug'] = $this->config->get('payment_netgiro_post_debug');
		}

		if (isset($this->request->post['payment_netgiro_post_total'])) {
			$data['payment_netgiro_post_total'] = $this->request->post['payment_netgiro_post_total'];
		} else {
			$data['payment_netgiro_post_total'] = $this->config->get('payment_netgiro_post_total');
		}

		if (isset($this->request->post['payment_netgiro_post_order_status_id'])) {
			$data['payment_netgiro_post_order_status_id'] = $this->request->post['payment_netgiro_post_order_status_id'];
		} else {
			$data['payment_netgiro_post_order_status_id'] = $this->config->get('payment_netgiro_post_order_status_id');
		}
		
		
        foreach ($errors as $error) {
			if (isset($this->error[$error])) {
				$data['error_' . $error] = $this->error[$error];
			} else {
				$data['error_' . $error] = '';
			}
		}

		# Extension Type Icon
		switch($extension_type) {
			case 'payment':
				$data['icon_class'] = 'credit-card';
				break;
			case 'shipping':
				$data['icon_class'] = 'truck';
				break;
			default:
				$data['icon_class'] = 'puzzle-piece';
		}

		$data['extension_class'] = $extension_type;
		$data['tab_class'] = 'htabs'; //vtabs or htabs

		# Geozones
		$geo_zones = array();
		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['payment_netgiro_post_geo_zone_id'])) {
			$data['payment_netgiro_post_geo_zone_id'] = $this->request->post['payment_netgiro_post_geo_zone_id'];
		} else {
			$data['payment_netgiro_post_geo_zone_id'] = $this->config->get('payment_netgiro_post_geo_zone_id');
		}

		# Order Statuses
		$order_statuses = array();
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();		

		if (isset($this->request->post['payment_netgiro_post_status'])) {
			$data['payment_netgiro_post_status'] = $this->request->post['payment_netgiro_post_status'];
		} else {
			$data['payment_netgiro_post_status'] = $this->config->get('payment_netgiro_post_status');
		}
		
		# Languages
		$languages = array();
		$this->load->model('localisation/language');
		foreach ($this->model_localisation_language->getLanguages() as $language) {
			$languages[$language['language_id']] = $language['name'];
		}

		# Tabs
		$data['tabs'] = array();

		# Fields
		$data['fields'] = array();

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');			
		
		$this->response->setOutput($this->load->view('extension/payment/netgiro_post', $data));			
	}

	private function validate($errors = array()) {
		
		if (!$this->user->hasPermission('modify', 'extension/payment/netgiro_post')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['payment_netgiro_post_mid']) {
			$this->error['mid'] = $this->language->get('error_mid');		
		}
		
		if (!$this->request->post['payment_netgiro_post_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		return !$this->error;			
	}
}
?>