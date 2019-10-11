<?php
class ControllerPaymentNetgiroPost extends Controller {
	private $error = array();
	private $name = '';

	public function index() {

		/* START ERRORS */
		$errors = array(
			'warning',
			'mid',
			'key',
		);
		/* END ERRORS */



		/* START COMMON STUFF */
		$this->name = str_replace('vq2-admin_controller_payment_', '', basename(__FILE__, '.php'));

		if (!isset($this->session->data['token'])) { $this->session->data['token'] = 0; }
		$this->data['token'] = $this->session->data['token'];
		$this->data = array_merge($this->data, $this->load->language('payment/' . $this->name));

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate($errors))) {
			foreach ($this->request->post as $key => $value) {
				if (is_array($value)) { $this->request->post[$key] = implode(',', $value); }
			}
			
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting($this->name, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect((((HTTPS_SERVER) ? HTTPS_SERVER : HTTP_SERVER) . 'index.php?token=' . $this->session->data['token'] . '&route=extension/payment'));
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => (((HTTPS_SERVER) ? HTTPS_SERVER : HTTP_SERVER) . 'index.php?token=' . $this->session->data['token'] . '&route=common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => (((HTTPS_SERVER) ? HTTPS_SERVER : HTTP_SERVER) . 'index.php?token=' . $this->session->data['token'] . '&route=extension/payment'),
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => (((HTTPS_SERVER) ? HTTPS_SERVER : HTTP_SERVER) . 'index.php?token=' . $this->session->data['token'] . '&route=payment/' . $this->name),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = (((HTTPS_SERVER) ? HTTPS_SERVER : HTTP_SERVER) . 'index.php?token=' . $this->session->data['token'] . '&route=payment/' . $this->name);

		$this->data['cancel'] = (((HTTPS_SERVER) ? HTTPS_SERVER : HTTP_SERVER) . 'index.php?token=' . $this->session->data['token'] . '&route=extension/payment');

		$this->id       = 'content';
		$this->template = 'payment/' . $this->name . '.tpl';

		/* 14x backwards compatibility */
		if (method_exists($this->document, 'addBreadcrumb')) { //1.4.x
			$this->document->breadcrumbs = $this->data['breadcrumbs'];
			unset($this->data['breadcrumbs']);
		}//

		$this->children = array(
            'common/header',
            'common/footer'
        );

        foreach ($errors as $error) {
			if (isset($this->error[$error])) {
				$this->data['error_' . $error] = $this->error[$error];
			} else {
				$this->data['error_' . $error] = '';
			}
		}
		/* END COMMON STUFF */




		/* START FIELDS */
		$this->data['extension_class'] = 'payment';
		$this->data['tab_class'] = 'htabs';

		$geo_zones = array();

		$this->load->model('localisation/geo_zone');

		$geo_zones[0] = $this->language->get('text_all_zones');
		foreach ($this->model_localisation_geo_zone->getGeoZones() as $geozone) {
			$geo_zones[$geozone['geo_zone_id']] = $geozone['name'];
		}

		$order_statuses = array();

		$this->load->model('localisation/order_status');

		foreach ($this->model_localisation_order_status->getOrderStatuses() as $order_status) {
			$order_statuses[$order_status['order_status_id']] = $order_status['name'];
		}
		
		$customer_groups = array();

		$this->load->model('sale/customer_group');

		foreach ($this->model_sale_customer_group->getCustomerGroups() as $customer_group) {
			$customer_groups[$customer_group['customer_group_id']] = $customer_group['name'];
		}
		
		$languages = array();

		$this->load->model('localisation/language');
		foreach ($this->model_localisation_language->getLanguages() as $language) {
			$languages[$language['language_id']] = $language['name'];
		}

		$this->data['tabs'] = array();

		$this->data['tabs'][] = array(
			'id'		=> 'tab_general',
			'title'		=> $this->language->get('tab_general')
		);

		$this->data['fields'] = array();

		$this->data['fields'][] = array(
			'entry' 		=> $this->language->get('entry_status'),
			'type'			=> 'select',
			'name' 			=> $this->name . '_status',
			'value' 		=> (isset($this->request->post[$this->name . '_status'])) ? $this->request->post[$this->name . '_status'] : $this->config->get($this->name . '_status'),
			'required' 		=> false,
			'options'		=> array(
				'0' => $this->language->get('text_disabled'),
				'1' => $this->language->get('text_enabled')
			)
		);
		
		foreach ($languages as $language_id => $language_name) {
			$this->data['fields'][] = array(
				'entry' 		=> '[ ' . $language_name . ' ] ' . $this->language->get('entry_title'),
				'type'			=> 'text',
				'size'			=> '20',
				'name' 			=> $this->name . '_title_' . $language_id,
				'value' 		=> ((isset($this->request->post[$this->name . '_title_' . $language_id])) ? $this->request->post[$this->name . '_title_' . $language_id] : $this->config->get($this->name . '_title_' . $language_id) ? $this->config->get($this->name . '_title_' . $language_id) : ucwords(str_replace(array('-','_','.'), " ", $this->name))),
				'required' 		=> false,
				'help'			=> ''
			);
		}

		$this->data['fields'][] = array(
			'entry' 		=> $this->language->get('entry_mid'),
			'type'			=> 'text',
			'size'			=> '60',
			'name' 			=> $this->name . '_mid',
			'value' 		=> (isset($this->request->post[$this->name . '_mid'])) ? $this->request->post[$this->name . '_mid'] : $this->config->get($this->name . '_mid'),
			'required' 		=> true,
			'error'			=> (isset($this->error['mid'])) ? $this->error['mid'] : '',
			'help'			=> $this->language->get('help_mid')
		);
		
		$this->data['fields'][] = array(
			'entry' 		=> $this->language->get('entry_key'),
			'type'			=> 'text',
			'size'			=> '60',
			'name' 			=> $this->name . '_key',
			'value' 		=> (isset($this->request->post[$this->name . '_key'])) ? $this->request->post[$this->name . '_key'] : $this->config->get($this->name . '_key'),
			'required' 		=> true,
			'error'			=> (isset($this->error['key'])) ? $this->error['key'] : '',
			'help'			=> $this->language->get('help_key')
		);
				
		$this->data['fields'][] = array(
			'entry' 		=> $this->language->get('entry_debug'),
			'type'			=> 'select',
			'name' 			=> $this->name . '_debug',
			'value' 		=> (isset($this->request->post[$this->name . '_debug'])) ? $this->request->post[$this->name . '_debug'] : $this->config->get($this->name . '_debug'),
			'required' 		=> false,
			'options'		=> array(
				'0' => $this->language->get('text_disabled'),
				'1' => $this->language->get('text_enabled')
			)
		);
		/*
		$this->data['fields'][] = array(
			'entry' 		=> $this->language->get('entry_txntype'),
			'type'			=> 'select',
			'name' 			=> $this->name . '_txntype',
			'value' 		=> (isset($this->request->post[$this->name . '_txntype'])) ? $this->request->post[$this->name . '_txntype'] : $this->config->get($this->name . '_txntype'),
			'required' 		=> false,
			'options'		=> array(
				'AUTHPOST' => $this->language->get('text_authpost'),
				'AUTH' => $this->language->get('text_auth')
			)
		);
		*/
		
		$this->data['fields'][] = array(
			'entry' 		=> $this->language->get('entry_payment_option'),
			'type'			=> 'select',
			'name' 			=> $this->name . '_payment_option',
			'value' 		=> (isset($this->request->post[$this->name . '_payment_option'])) ? $this->request->post[$this->name . '_payment_option'] : $this->config->get($this->name . '_payment_option'),
			'required' 		=> false,
			'options'		=> array(
				'1' => $this->language->get('text_po_one'),
				'2' => $this->language->get('text_po_two'),
				'3' => $this->language->get('text_po_three')
			)
		);
		
		$this->data['fields'][] = array(
			'entry' 		=> $this->language->get('entry_max_install'),
			'type'			=> 'select',
			'name' 			=> $this->name . '_max_install',
			'value' 		=> (isset($this->request->post[$this->name . '_max_install'])) ? $this->request->post[$this->name . '_max_install'] : $this->config->get($this->name . '_max_install'),
			'required' 		=> false,
			'options'		=> array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5,
			)
		);
		
		
		$this->data['fields'][] = array(
			'entry' 		=> $this->language->get('entry_total'),
			'type'			=> 'text',
			'size'			=> '20',
			'name' 			=> $this->name . '_total',
			'value' 		=> (isset($this->request->post[$this->name . '_total'])) ? $this->request->post[$this->name . '_total'] : $this->config->get($this->name . '_total'),
			'required' 		=> false
		);

		$this->data['fields'][] = array(
			'entry' 		=> $this->language->get('entry_test'),
			'type'			=> 'select',
			'name' 			=> $this->name . '_test',
			'value' 		=> (isset($this->request->post[$this->name . '_test'])) ? $this->request->post[$this->name . '_test'] : $this->config->get($this->name . '_test'),
			'required' 		=> false,
			'options'		=> array(
				'0' => $this->language->get('text_no'),
				'1' => $this->language->get('text_yes')
			)
		);

/*

		$this->data['fields'][] = array(
			'entry' 		=> $this->language->get('entry_customer_group'),
			'type'			=> 'select',
			'multiple'		=> true,
			'name' 			=> $this->name . '_customer_group[]',
			'value' 		=> (isset($this->request->post[$this->name . '_customer_group'])) ? $this->request->post[$this->name . '_customer_group'] : $this->config->get($this->name . '_customer_group'),
			'required' 		=> false,
			'options'		=> $customer_groups
		);
*/
		
		$this->data['fields'][] = array(
			'entry' 		=> $this->language->get('entry_geo_zone'),
			'type'			=> 'select',
			'name' 			=> $this->name . '_geo_zone_id',
			'value' 		=> (isset($this->request->post[$this->name . '_geo_zone_id'])) ? $this->request->post[$this->name . '_geo_zone_id'] : $this->config->get($this->name . '_geo_zone_id'),
			'required' 		=> false,
			'options'		=> $geo_zones
		);

		$this->data['fields'][] = array(
			'entry' 		=> $this->language->get('entry_order_status'),
			'type'			=> 'select',
			'name' 			=> $this->name . '_order_status_id',
			'value' 		=> (isset($this->request->post[$this->name . '_order_status_id'])) ? $this->request->post[$this->name . '_order_status_id'] : $this->config->get($this->name . '_order_status_id'),
			'required' 		=> false,
			'options'		=> $order_statuses
		);

		$this->data['fields'][] = array(
			'entry'			=> $this->language->get('entry_sort_order'),
			'type'			=> 'text',
			'name'			=> $this->name . '_sort_order',
			'value'			=> (isset($this->request->post[$this->name . '_sort_order'])) ? $this->request->post[$this->name . '_sort_order'] : $this->config->get($this->name . '_sort_order'),
			'required'		=> false,
		);
		/* END FIELDS */

        $this->response->setOutput($this->render(TRUE));
	}

	private function validate($errors = array()) {
		if (!$this->user->hasPermission('modify', 'payment/' . $this->name)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($errors as $error) {
			if (isset($this->request->post[$this->name . '_' . $error]) && !$this->request->post[$this->name . '_' . $error]) {
				$this->error[$error] = $this->language->get('error_' . $error);
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>