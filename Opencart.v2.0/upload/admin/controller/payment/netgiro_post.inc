<?php

		$data['tabs'][] = array(
			'id'		=> 'tab_general',
			'title'		=> $this->language->get('tab_general')
		);

		$data['tabs'][] = array(
			'id'		=> 'tab_debug',
			'title'		=> $this->language->get('tab_debug')
		);

		$data['tabs'][] = array(
			'id'		=> 'tab_support',
			'title'		=> $this->language->get('tab_support')
		);

		$data['fields'][] = array(
			'tab'			=> 'tab_general',
			'entry' 		=> $this->language->get('entry_status'),
			'type'			=> 'select',
			'name' 			=> 'status',
			'value' 		=> (isset($this->request->post['status'])) ? $this->request->post['status'] : $this->config->get($classname . '_status'),
			'required' 		=> false,
			'options'		=> array(
				'0' => $this->language->get('text_disabled'),
				'1' => $this->language->get('text_enabled')
			),
			'help'			=> ($this->language->get('help_status') != 'help_status' ? $this->language->get('help_status') : ''),
			'tooltip'		=> ($this->language->get('tooltip_status') != 'tooltip_status' ? $this->language->get('tooltip_status') : '')
		);

		foreach ($languages as $language_id => $language_name) {
			$data['fields'][] = array(
				'entry' 		=> '[ ' . $language_name . ' ] ' . $this->language->get('entry_title'),
				'type'			=> 'text',
				'size'			=> '20',
				'name' 			=> 'title_' . $language_id,
				'value' 		=> ((isset($this->request->post['title_' . $language_id])) ? $this->request->post['title_' . $language_id] : $this->config->get($classname . '_title_' . $language_id) ? $this->config->get($classname . '_title_' . $language_id) : ucwords(str_replace(array('-','_','.'), " ", $classname))),
				'required' 		=> false,
				'help'			=> ($this->language->get('help_title') != 'help_title' ? $this->language->get('help_title') : ''),
				'tooltip'		=> ($this->language->get('tooltip_title') != 'tooltip_title' ? $this->language->get('tooltip_title') : '')
			);
		}

		$data['fields'][] = array(
			'tab'			=> 'tab_general',
			'entry' 		=> $this->language->get('entry_test'),
			'type'			=> 'select',
			'name' 			=> 'test',
			'value' 		=> (isset($this->request->post['test'])) ? $this->request->post['test'] : $this->config->get($classname . '_test'),
			'required' 		=> false,
			'options'		=> array(
				'0' => $this->language->get('text_no'),
				'1' => $this->language->get('text_yes')
			),
			'help'			=> ($this->language->get('help_test') != 'help_test' ? $this->language->get('help_test') : ''),
			'tooltip'		=> ($this->language->get('tooltip_test') != 'tooltip_test' ? $this->language->get('tooltip_test') : '')
		);

		$data['fields'][] = array(
			'tab'			=> 'tab_general',
			'entry' 		=> $this->language->get('entry_mid'),
			'type'			=> 'text',
			'name' 			=> 'mid',
			'value' 		=> (isset($this->request->post['mid'])) ? $this->request->post['mid'] : $this->config->get($classname . '_mid'),
			'required' 		=> true,
			'help'			=> ($this->language->get('help_mid') != 'help_mid' ? $this->language->get('help_mid') : ''),
			'tooltip'		=> ($this->language->get('tooltip_mid') != 'tooltip_mid' ? $this->language->get('tooltip_mid') : '')
		);

		$data['fields'][] = array(
			'tab'			=> 'tab_general',
			'entry' 		=> $this->language->get('entry_key'),
			'type'			=> 'text',
			'name' 			=> 'key',
			'value' 		=> (isset($this->request->post['key'])) ? $this->request->post['key'] : $this->config->get($classname . '_key'),
			'required' 		=> true,
			'help'			=> ($this->language->get('help_key') != 'help_key' ? $this->language->get('help_key') : ''),
			'tooltip'		=> ($this->language->get('tooltip_key') != 'tooltip_key' ? $this->language->get('tooltip_key') : '')
		);

		$data['fields'][] = array(
			'tab'			=> 'tab_general',
			'entry' 		=> $this->language->get('entry_payment_option'),
			'type'			=> 'select',
			'name' 			=> 'payment_option',
			'value' 		=> (isset($this->request->post['payment_option'])) ? $this->request->post['payment_option'] : $this->config->get($classname . '_payment_option'),
			'required' 		=> false,
			'options'		=> array(
				'1' => $this->language->get('text_po_one'),
				'2' => $this->language->get('text_po_two'),
				'3' => $this->language->get('text_po_three')
			),
			'help'			=> ($this->language->get('help_payment_option') != 'help_payment_option' ? $this->language->get('help_payment_option') : ''),
			'tooltip'		=> ($this->language->get('tooltip_payment_option') != 'tooltip_payment_option' ? $this->language->get('tooltip_payment_option') : '')
		);
		
		$data['fields'][] = array(
			'tab'			=> 'tab_general',
			'entry' 		=> $this->language->get('entry_max_install'),
			'type'			=> 'select',
			'name' 			=> 'max_install',
			'value' 		=> (isset($this->request->post['max_install'])) ? $this->request->post['max_install'] : $this->config->get($classname . '_max_install'),
			'required' 		=> false,
			'options'		=> array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5,
			),
			'help'			=> ($this->language->get('help_max_install') != 'help_max_install' ? $this->language->get('help_max_install') : ''),
			'tooltip'		=> ($this->language->get('tooltip_max_install') != 'tooltip_max_install' ? $this->language->get('tooltip_max_install') : '')
		);		
		
		$data['fields'][] = array(
			'tab'			=> 'tab_general',
			'entry'			=> $this->language->get('entry_total'),
			'type'			=> 'text',
			'name'			=> 'total',
			'value'			=> (isset($this->request->post['total'])) ? $this->request->post['total'] : $this->config->get($classname . '_total'),
			'required'		=> false,
			'help'			=> ($this->language->get('help_total') != 'help_total' ? $this->language->get('help_total') : ''),
			'tooltip'		=> ($this->language->get('tooltip_total') != 'tooltip_total' ? $this->language->get('tooltip_total') : '')
		);
		
		$data['fields'][] = array(
			'tab'			=> 'tab_general',
			'entry' 		=> $this->language->get('entry_debug'),
			'type'			=> 'select',
			'name' 			=> 'debug',
			'value' 		=> (isset($this->request->post['debug'])) ? $this->request->post['debug'] : $this->config->get($classname . '_debug'),
			'required' 		=> false,
			'options'		=> array(
				'0' => $this->language->get('text_disabled'),
				'1' => $this->language->get('text_enabled')
			),
			'help'			=> ($this->language->get('help_debug') != 'help_debug' ? $this->language->get('help_debug') : ''),
			'tooltip'		=> ($this->language->get('tooltip_debug') != 'tooltip_debug' ? $this->language->get('tooltip_debug') : '')
		);
		
		$data['fields'][] = array(
			'tab'			=> 'tab_general',
			'entry' 		=> $this->language->get('entry_order_status'),
			'type'			=> 'select',
			'name' 			=> 'order_status_id',
			'value' 		=> (isset($this->request->post['order_status_id'])) ? $this->request->post['order_status_id'] : $this->config->get($classname . '_order_status_id'),
			'required' 		=> false,
			'options'		=> $order_statuses,
			'help'			=> ($this->language->get('help_order_status') != 'help_order_status' ? $this->language->get('help_order_status') : ''),
			'tooltip'		=> ($this->language->get('tooltip_order_status') != 'tooltip_order_status' ? $this->language->get('tooltip_order_status') : '')
		);

		$data['fields'][] = array(
			'tab'			=> 'tab_general',
			'entry' 		=> $this->language->get('entry_tax_class'),
			'type'			=> 'select',
			'name' 			=> 'tax_class_id',
			'value' 		=> (isset($this->request->post['tax_class_id'])) ? $this->request->post['tax_class_id'] : $this->config->get($classname . '_tax_class_id'),
			'required' 		=> false,
			'options'		=> $tax_classes,
			'help'			=> ($this->language->get('help_tax_class') != 'help_tax_class' ? $this->language->get('help_tax_class') : ''),
			'tooltip'		=> ($this->language->get('tooltip_tax_class') != 'tooltip_tax_class' ? $this->language->get('tooltip_tax_class') : '')
		);

		$data['fields'][] = array(
			'tab'			=> 'tab_general',
			'entry' 		=> $this->language->get('entry_geo_zone'),
			'type'			=> 'select',
			'name' 			=> 'geo_zone_id',
			'value' 		=> (isset($this->request->post['geo_zone_id'])) ? $this->request->post['geo_zone_id'] : $this->config->get($classname . '_geo_zone_id'),
			'required' 		=> false,
			'options'		=> $geo_zones,
			'help'			=> ($this->language->get('help_geo_zone') != 'help_geo_zone' ? $this->language->get('help_geo_zone') : ''),
			'tooltip'		=> ($this->language->get('tooltip_geo_zone') != 'tooltip_geo_zone' ? $this->language->get('tooltip_geo_zone') : '')
		);

		$data['fields'][] = array(
			'tab'			=> 'tab_general',
			'entry'			=> $this->language->get('entry_sort_order'),
			'type'			=> 'text',
			'name'			=> 'sort_order',
			'value'			=> (isset($this->request->post['sort_order'])) ? $this->request->post['sort_order'] : $this->config->get($classname . '_sort_order'),
			'required'		=> false,
			'help'			=> ($this->language->get('help_sort_order') != 'help_sort_order' ? $this->language->get('help_sort_order') : ''),
			'tooltip'		=> ($this->language->get('tooltip_sort_order') != 'tooltip_sort_order' ? $this->language->get('tooltip_sort_order') : '')
		);

		$data['fields'][] = array(
			'tab'			=> 'tab_debug',
			'entry'			=> $this->language->get('entry_debug_file'),
			'type'			=> 'label',
			'name'			=> '',
			'value'			=> DIR_LOGS . $classname . '_debug.txt',
			'help'			=> ($this->language->get('help_debug_file') != 'help_debug_file' ? $this->language->get('help_debug_file') : ''),
			'tooltip'		=> ($this->language->get('tooltip_debug_file') != 'tooltip_debug_file' ? $this->language->get('tooltip_debug_file') : '')
		);

		$data['fields'][] = array(
			'tab'			=> 'tab_debug',
			'entry'			=> '',
			'type'			=> 'textarea',
			'cols'			=> '160',
			'rows'			=> '100',
			'name'			=> '',
			'value'			=> (file_exists(DIR_LOGS . $classname . '_debug.txt')) ? file_get_contents(DIR_LOGS . $classname . '_debug.txt') : 'empty'
		);

		$data['fields'][] = array(
			'tab'			=> 'tab_support',
			'entry'			=> 'Troubleshooting Info: ',
			'type'			=> 'label',
			'name'			=> 'troubleshooting',
			'value'			=> '',
			'help'			=> '',
			'tooltip'		=> ''
		);

		$data['fields'][] = array(
			'tab'			=> 'tab_support',
			'entry'			=> 'Support Info:',
			'type'			=> 'label',
			'name'			=> 'support',
			'value'			=> 'For support questions, contact me at qphoria@gmail.com or on skype: taqmobile',
			'help'			=> '',
			'tooltip'		=> ''
		);

?>