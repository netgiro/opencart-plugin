<?php
class ModelPaymentNetgiroPost extends Model {
  	public function getMethod($address, $total = false) {

		$name = basename(__FILE__, '.php');

		$this->load->language('payment/' . $name);
		
		if ($this->config->get($name . '_status')) {
		
			// v14x backwards compatible
			if ($total === false) { $total = $this->cart->getTotal(); }

			// Return if total too low
			if ($this->config->get($name . '_total') > $total) { return array(); }

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get($name . '_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

			if (!$this->config->get($name . '_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
      		  	$status = TRUE;
      		} else {
     	  		$status = FALSE;
			}
      	} else {
			$status = FALSE;
		}

		$method_data = array();

		//Check that current Customer Group ID is allowed
       	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` LIKE '" . $name . "_customer_group_%'");
		if ($query->num_rows) {
			$allowed = array();
			foreach($query->rows as $group) {
				$key = explode('_', $group['key']);
				$allowed[] = end($key);
			}
			if (!in_array($this->customer->getCustomerGroupId(), $allowed)) {
				$status = FALSE;
			}
		}//

		
		if ($status) {
      		$method_data = array(
        		'id'		 => $name, //v14x
				'code'		 => $name, //v15x
        		//'title'      => $this->language->get('text_title'),
				'title'      => ($this->config->get($name . '_title_' . $this->config->get('config_language_id')) ? $this->config->get($name . '_title_' . $this->config->get('config_language_id')) : ucwords(str_replace(array('-','_','.'), " ", $name))),
				'sort_order' => $this->config->get($name . '_sort_order')
      		);
    	}

    	return $method_data;
  	}
}
?>