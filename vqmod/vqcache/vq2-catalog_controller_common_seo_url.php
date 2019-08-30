<?php
class ControllerCommonSeoUrl extends Controller {
	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);

			// remove any empty arrays from trailing
			if (utf8_strlen(end($parts)) == 0) {
				array_pop($parts);
			}

			foreach ($parts as $part) {


        	if (strrpos($part, "PR-") !== false) {

					$price_parts=explode("-",$part);

					$part=$price_parts[0];

				}	

				 

      
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");

				if ($query->num_rows) {
					$url = explode('=', $query->row['query']);

					if ($url[0] == 'product_id') {
						$this->request->get['product_id'] = $url[1];
					}

					if ($url[0] == 'category_id') {
						if (!isset($this->request->get['path'])) {
							$this->request->get['path'] = $url[1];
						} else {
							$this->request->get['path'] .= '_' . $url[1];
						}
					}



        if ($url[0] == 'filter_width') {

						$this->request->get['filter_width'] = $url[1];

					}

					if ($url[0] == 'filter_height') {

						$this->request->get['filter_height'] = $url[1];

					}					

					if ($url[0] == 'filter_length') {

						$this->request->get['filter_length'] = $url[1];

					}					

					if ($url[0] == 'filter_model') {

						$this->request->get['filter_model'] = $url[1];

					}					

					if ($url[0] == 'filter_sku') {

						$this->request->get['filter_sku'] = $url[1];

					}					

					if ($url[0] == 'filter_upc') {

						$this->request->get['filter_upc'] = $url[1];

					}					

					if ($url[0] == 'filter_location') {

						$this->request->get['filter_location'] = $url[1];

					}					

					if ($url[0] == 'filter_weight') {

						$this->request->get['filter_weight'] = $url[1];

					}					

					if ($url[0] == 'filter_ean') {

						$this->request->get['filter_ean'] = $url[1];

					}					

					if ($url[0] == 'filter_isbn') {

						$this->request->get['filter_isbn'] = $url[1];

					}					

					if ($url[0] == 'filter_mpn') {

						$this->request->get['filter_mpn'] = $url[1];

					}					

					if ($url[0] == 'filter_jan') {

						$this->request->get['filter_jan'] = $url[1];

					}	

					if ($url[0] == 'filter_rating') {

						$this->request->get['filter_rating'] = $url[1];

					}					

					if ($url[0] == 'filter_special') {

						$this->request->get['filter_special'] = $url[1];

					}	

					if ($url[0] == 'filter_stock') {

						$this->request->get['filter_stock'] = $url[1];

					}					

					if ($url[0] == 'filter_clearance') {

						$this->request->get['filter_clearance'] = $url[1];

					}	

					if ($url[0] == 'filter_arrivals') {

						$this->request->get['filter_arrivals'] = $url[1];

					}			

					$pos = strrpos($url[0], "filter_att");

						if ($pos !== false) {

						$data = array();

		                parse_str($query->row['query'], $data);

						foreach ($data['filter_att'] as $key=>$value){

						 if ($url[0] == 'filter_att['.$key.']') {$this->request->get['filter_att'][$key]= $url[1]; }

						}

						}

					$pos = strrpos($url[0], "filter_opt");

						if ($pos !== false) {

    					$data = array();

		                parse_str($query->row['query'], $data);

						foreach ($data['filter_opt'] as $key=>$value){

						 if ($url[0] == 'filter_opt['.$key.']') {

						$this->request->get['filter_opt'][$key]= $url[1];

						 }

						}

						}	

					if ($url[0] == 'filtering') {

						$this->request->get['filtering'] = $url[1];

					}

      
					if ($url[0] == 'manufacturer_id') {
						$this->request->get['manufacturer_id'] = $url[1];
					}

					if ($url[0] == 'information_id') {
						$this->request->get['information_id'] = $url[1];
					}

					

        	if ($query->row['query'] && strrpos($url[0], "filter_opt")===false && strrpos($url[0], "filter_att")===false && strrpos($url[0], "PR-")===false && $url[0] != 'pr' && $url[0] != 'filtering' && $url[0] != 'filter_width' && $url[0] != 'filter_height' && $url[0] != 'filter_length' && $url[0] != 'filter_model' && $url[0] != 'filter_sku' && $url[0] != 'filter_upc' && $url[0] != 'filter_location' && $url[0] != 'filter_weight' && $url[0] != 'filter_ean' && $url[0] != 'filter_isbn' && $url[0] != 'filter_mpn' && $url[0] != 'filter_jan' && $url[0] != 'filter_rating' && $url[0] != 'filter_special' && $url[0] != 'filter_stock' && $url[0] != 'filter_clearance' && $url[0] != 'filter_arrivals' 

				 

       && $url[0] != 'information_id' && $url[0] != 'manufacturer_id' && $url[0] != 'category_id' && $url[0] != 'product_id') {
						$this->request->get['route'] = $query->row['query'];
					}
				} else {
					$this->request->get['route'] = 'error/not_found';

					break;
				}
			}

			if (!isset($this->request->get['route'])) {
				if (isset($this->request->get['product_id'])) {
					$this->request->get['route'] = 'product/product';
				} elseif (isset($this->request->get['path'])) {

        //FULL lAYERED MENU WITH SEO
          if(isset($this->request->get['dnd'])){
          $this->request->get['route'] = 'module/supercategorymenuadvancedseemore';
          }elseif (isset($this->request->get['filtering']) or isset($this->request->get['pr']) or $this->request->get['path']==0){
          $this->request->get['route'] = 'product/asearch';
          }else{
      
					$this->request->get['route'] = 'product/category';

        }
      
				} elseif (isset($this->request->get['manufacturer_id'])) {

        //FULL lAYERED MENU WITH SEO
          if(isset($this->request->get['dnd'])){
          $this->request->get['route'] = 'module/supercategorymenuadvancedseemore';
          }elseif (isset($this->request->get['filtering']) or isset($this->request->get['pr']) or $this->request->get['manufacturer_id']==0){
          $this->request->get['route'] = 'product/asearch';
          }else{
      
					$this->request->get['route'] = 'product/manufacturer/info';

        }
      
				} elseif (isset($this->request->get['information_id'])) {
					$this->request->get['route'] = 'information/information';
				}
			}

			if (isset($this->request->get['route'])) {
				return new Action($this->request->get['route']);
			}
		}
	}

	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));

		$url = '';

		$data = array();

		parse_str($url_info['query'], $data);

		foreach ($data as $key => $value) {
			if (isset($data['route'])) {


        	if (($data['route'] == 'product/product') ||($data['route'] == 'product/asearch' ) ){

					$filters_array=array('filtering','product_id','manufacturer_id','filter_model','filter_width','filter_isbn','filter_jan','filter_sku','filter_upc','filter_location','filter_length','filter_weight','filter_ean','filter_height','filter_rating',);

					if (in_array($key, $filters_array)) {

					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . $value) . "'");

						if ($query->num_rows && $query->row['keyword']) {

							//$url .= '/' . str_replace("@value@",$value,$query->row['keyword']);

							$url .= '/' . $query->row['keyword'];

							unset($data[$key]);

						}

					}

					if ($key == 'filter_att' || $key == 'filter_opt' ){

						foreach ($value as $key2 => $value2) {

							$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key.'['.$key2.']=' . $value2) . "'");

							if ($query->num_rows && $query->row['keyword']) {

								$url .= '/' . $query->row['keyword'];

           					unset($data[$key]);

							}

						}

					}

					if ($key == 'path') {

					$categories = explode('_', $value);

					foreach ($categories as $category) {

						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");

						if ($query->num_rows && $query->row['keyword']) {

							$url .= '/' . $query->row['keyword'];

						} else {

							$url = '';

							break;

						}

					}

					unset($data[$key]);

					}

				 

      
				

        }elseif (($data['route'] == 'product/product'

       && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");

					if ($query->num_rows && $query->row['keyword']) {
						$url .= '/' . $query->row['keyword'];

						unset($data[$key]);
					}
				} elseif ($key == 'path') {
					$categories = explode('_', $value);

					foreach ($categories as $category) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");

						if ($query->num_rows && $query->row['keyword']) {
							$url .= '/' . $query->row['keyword'];
						} else {
							$url = '';

							break;
						}
					}

					unset($data[$key]);
				}
			}
		}

		if ($url) {
			unset($data['route']);

			$query = '';

			if ($data) {
				foreach ($data as $key => $value) {


        	if ($key == 'filter_att' || $key == 'filter_opt' ){

						foreach ($value as $key2 => $value2) {

							$query .= '&' . $key.'['.(string)$key2.']=' . rawurlencode((string)$value2);

						}

					}else{	

      
					$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((string)$value);


        	}	

      
				}

				if ($query) {
					$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
				}
			}

			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}
}
