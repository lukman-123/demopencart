<?php
class ControllerModuleLatest extends Controller {
	public function index($setting) {
		$this->load->language('module/latest');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_product->getProducts($filter_data);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					
       	        /*if($this->config->get('catalogrules_status') == 1)
				{
					$discoutPrice = $this->config->get('catalogrules_price');
					$afterDiscount = $result['price'] - $discoutPrice;
					$price = $this->currency->format($this->tax->calculate($afterDiscount, $result['tax_class_id'], $this->config->get('config_tax')));
			    }else
			    {
			        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			    }*/

                
                $date_now = date("Y-m-d");
                if($this->config->get('catalogrules_status') == 1 && ($this->config->get('catalogrules_date_start') <= $date_now) && ($this->config->get('catalogrules_date_end') >= $date_now))
				{
				  $discoutPrice = $this->config->get('catalogrules_price');
				  $discoutType = $this->config->get('catalogrules_type');

                  if($discoutType == 'P')
                  {

                    if($result['tax_class_id'] != 0 && $this->config->get('catalogrules_discount_tax') == 1)
                    {
	                  $Taxprice = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
	                  $afterDiscountPercentage = $Taxprice * $discoutPrice/100;
	                  $afterDiscount = $Taxprice - $afterDiscountPercentage;
	                  $price =  '<B>In Tax Discount </B>'.$this->currency->format($afterDiscount);

                     }else
                     {

                      $Taxprice = $this->tax->calculate($result['price'], 0 , $this->config->get('config_tax'));
	                  $afterDiscountPercentage = $Taxprice * $discoutPrice/100;
	                  $afterDiscount = $Taxprice - $afterDiscountPercentage;
	                  $price =  '<B>Ex. Tax Discount </B>'.$this->currency->format($afterDiscount);

                     }
                  }
                  else
                  {

                  if($result['tax_class_id'] != 0 && $this->config->get('catalogrules_discount_tax') == 1)
                  {

                  $Taxprice = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
                  $afterDiscount = $Taxprice - $discoutPrice;
                  $price =  '<B>In Tax Discount </B>'.$this->currency->format($afterDiscount);
                  }else{

                  $Taxprice = $this->tax->calculate($result['price'], 0, $this->config->get('config_tax'));
                  $afterDiscount = $Taxprice - $discoutPrice;
                  $price =  '<B>Ex. Tax Discount </B>'.$this->currency->format($afterDiscount);

                  }

                  }			  
			    }else
			    {
			      $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			    }


			
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					
                /*if($this->config->get('catalogrules_status') == 1)
				{
					$discoutPrice = $this->config->get('catalogrules_price');
					$afterDiscount = $result['special'] - $discoutPrice;
					$special = $this->currency->format($this->tax->calculate($afterDiscount, $result['tax_class_id'], $this->config->get('config_tax')));
			    }else
			    {
			        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			    }*/

			    $date_now = date("Y-m-d");
                if($this->config->get('catalogrules_status') == 1 && ($this->config->get('catalogrules_date_start') <= $date_now) && ($this->config->get('catalogrules_date_end') >= $date_now))
				{
				  $discoutPrice = $this->config->get('catalogrules_price');
				  $discoutType = $this->config->get('catalogrules_type');

                  if($discoutType == 'P')
                  {

                    if($result['tax_class_id'] != 0 && $this->config->get('catalogrules_discount_tax') == 1)
                    {
	                  $Taxprice = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
	                  $afterDiscountPercentage = $Taxprice * $discoutPrice/100;
	                  $afterDiscount = $Taxprice - $afterDiscountPercentage;
	                  $special =  '<B>In Tax Discount </B>'.$this->currency->format($afterDiscount);

                     }else
                     {

                      $Taxprice = $this->tax->calculate($result['special'], 0 , $this->config->get('config_tax'));
	                  $afterDiscountPercentage = $Taxprice * $discoutPrice/100;
	                  $afterDiscount = $Taxprice - $afterDiscountPercentage;
	                  $special =  '<B>Ex. Tax Discount </B>'.$this->currency->format($afterDiscount);

                     }
                  }
                  else
                  {

                  if($result['tax_class_id'] != 0 && $this->config->get('catalogrules_discount_tax') == 1)
                  {

                  $Taxprice = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
                  $afterDiscount = $Taxprice - $discoutPrice;
                  $special =  '<B>In Tax Discount </B>'.$this->currency->format($afterDiscount);
                  }else{

                  $Taxprice = $this->tax->calculate($result['special'], 0, $this->config->get('config_tax'));
                  $afterDiscount = $Taxprice - $discoutPrice;
                  $special =  '<B>Ex. Tax Discount </B>'.$this->currency->format($afterDiscount);

                  }

                  }			  
			    }else
			    {
			      $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			    }
			
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/latest.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/latest.tpl', $data);
			} else {
				return $this->load->view('default/template/module/latest.tpl', $data);
			}
		}
	}
}
