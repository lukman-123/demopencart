<?php
class ControllerAccountOrder extends Controller {
	private $error = array();


				public function customerPrintInvoice() {
				
					$this->load->language('account/order');
					
					$this->load->model('account/order');
					$this->load->model('tool/upload');
					
					$data['title'] = $this->language->get('text_invoice');

					if ($this->request->server['HTTPS']) {
						$data['base'] = HTTPS_SERVER;
					} else {
						$data['base'] = HTTP_SERVER;
					}

					$data['direction'] = $this->language->get('direction');
					$data['lang'] = $this->language->get('code');

					$data['text_invoice'] = $this->language->get('text_invoice');
					$data['text_order_detail'] = $this->language->get('text_order_detail');
					$data['text_order_id'] = $this->language->get('text_order_id');
					$data['text_invoice_no'] = $this->language->get('text_invoice_no');
					$data['text_invoice_date'] = $this->language->get('text_invoice_date');
					$data['text_date_added'] = $this->language->get('text_date_added');
					$data['text_telephone'] = $this->language->get('text_telephone');
					$data['text_fax'] = $this->language->get('text_fax');
					$data['text_email'] = $this->language->get('text_email');
					$data['text_website'] = $this->language->get('text_website');
					$data['text_payment_address'] = $this->language->get('text_payment_address');
					$data['text_shipping_address'] = $this->language->get('text_shipping_address');
					$data['text_payment_method'] = $this->language->get('text_payment_method');
					$data['text_shipping_method'] = $this->language->get('text_shipping_method');
					$data['text_comment'] = $this->language->get('text_comment');

					$data['column_product'] = $this->language->get('column_product');
					$data['column_model'] = $this->language->get('column_model');
					$data['column_quantity'] = $this->language->get('column_quantity');
					$data['column_price'] = $this->language->get('column_price');
					$data['column_total'] = $this->language->get('column_total');
					
					$data['button_print_invoice'] = $this->language->get('button_print_invoice');
					
					
					if(isset($this->request->get['order_id'])) {
						$order_id = $this->request->get['order_id'];
					} else {
						$order_id = 0;
					}
					
					$order_info = $this->model_account_order->getOrder($order_id);
					
					$found = 0;
					
					if($order_info) {					
						// check guest customer only print current order which place before some time.					
						if(isset($this->session->data['guest_order_id']) && ($this->session->data['guest_order_id'] == $order_info['order_id'])) {
							$found = 1;
						} else if($this->customer->getId() == $order_info['customer_id']) {
							// Logged customer only print his placed order
							$found = 1;
						} else {
							// Customer try to print other customer order which is not allowed.
							$found = 0;
						}
					}
										
					if($order_info && $found) {
						
						if ($order_info['invoice_no']) {
							$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
						} else {
							$invoice_no = '';
						}
						
						if ($order_info['payment_address_format']) {
							$format = $order_info['payment_address_format'];
						} else {
							$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
						}

						$find = array(
							'{firstname}',
							'{lastname}',
							'{company}',
							'{address_1}',
							'{address_2}',
							'{city}',
							'{postcode}',
							'{zone}',
							'{zone_code}',
							'{country}'
						);

						$replace = array(
							'firstname' => $order_info['payment_firstname'],
							'lastname'  => $order_info['payment_lastname'],
							'company'   => $order_info['payment_company'],
							'address_1' => $order_info['payment_address_1'],
							'address_2' => $order_info['payment_address_2'],
							'city'      => $order_info['payment_city'],
							'postcode'  => $order_info['payment_postcode'],
							'zone'      => $order_info['payment_zone'],
							'zone_code' => $order_info['payment_zone_code'],
							'country'   => $order_info['payment_country']
						);
						
						$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

						if ($order_info['shipping_address_format']) {
							$format = $order_info['shipping_address_format'];
						} else {
							$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
						}

						$find = array(
							'{firstname}',
							'{lastname}',
							'{company}',
							'{address_1}',
							'{address_2}',
							'{city}',
							'{postcode}',
							'{zone}',
							'{zone_code}',
							'{country}'
						);

						$replace = array(
							'firstname' => $order_info['shipping_firstname'],
							'lastname'  => $order_info['shipping_lastname'],
							'company'   => $order_info['shipping_company'],
							'address_1' => $order_info['shipping_address_1'],
							'address_2' => $order_info['shipping_address_2'],
							'city'      => $order_info['shipping_city'],
							'postcode'  => $order_info['shipping_postcode'],
							'zone'      => $order_info['shipping_zone'],
							'zone_code' => $order_info['shipping_zone_code'],
							'country'   => $order_info['shipping_country']
						);
						
						
						$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

						$this->load->model('tool/upload');

						$product_data = array();

						$products = $this->model_account_order->getOrderProducts($order_id);

						foreach ($products as $product) {
							$option_data = array();

							$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

							foreach ($options as $option) {
								if ($option['type'] != 'file') {
									$value = $option['value'];
								} else {
									$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

									if ($upload_info) {
										$value = $upload_info['name'];
									} else {
										$value = '';
									}
								}

								$option_data[] = array(
									'name'  => $option['name'],
									'value' => $value
								);
							}

							$product_data[] = array(
								'name'     => $product['name'],
								'model'    => $product['model'],
								'option'   => $option_data,
								'quantity' => $product['quantity'],
								'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
								'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
							);
						}

						$voucher_data = array();

						$vouchers = $this->model_account_order->getOrderVouchers($order_id);

						foreach ($vouchers as $voucher) {
							$voucher_data[] = array(
								'description' => $voucher['description'],
								'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
							);
						}

						$total_data = array();

						$totals = $this->model_account_order->getOrderTotals($order_id);

						foreach ($totals as $total) {
							$total_data[] = array(
								'title' => $total['title'],
								'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
							);
						}
						
						// check weather store logo need to put on the invoice
						$data['customer_invoice_store_logo'] = $this->config->get('customer_invoice_store_logo');
						
						if ($this->request->server['HTTPS']) {
							$server = $this->config->get('config_ssl');
						} else {
							$server = $this->config->get('config_url');
						}
						
						if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
							$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
						} else {
							$data['logo'] = '';
						}
						
						$data['order_id'] = $order_id;
						$data['invoice_no'] = $invoice_no;
						$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
						$data['store_name'] = $order_info['store_name'];
						$data['store_url']  = rtrim($order_info['store_url'], '/');
						$data['store_address'] = nl2br($this->config->get('config_address'));
						$data['store_email'] = $this->config->get('config_email');
						$data['store_telephone'] = $this->config->get('config_telephone');
						$data['store_fax'] = $this->config->get('config_fax');
						$data['email'] = $order_info['email'];
						$data['telephone'] = $order_info['telephone'];
						$data['shipping_address'] = $shipping_address;
						$data['shipping_method'] = $order_info['shipping_method'];
						$data['payment_address'] = $payment_address;
						$data['payment_method'] = $order_info['payment_method'];
						$data['products'] = $product_data;
						$data['vouchers'] = $voucher_data;
						$data['totals'] = $total_data;
						$data['comment'] = nl2br($order_info['comment']);
						
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_invoice.tpl')) {
							$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_invoice.tpl', $data));
						} else {
							$this->response->setOutput($this->load->view('default/template/account/order_invoice.tpl', $data));
						}
						
					} else {
						$this->document->setTitle($this->language->get('text_order'));

						$data['heading_title'] = $this->language->get('text_order');

						$data['text_error'] = $this->language->get('text_error');

						$data['button_continue'] = $this->language->get('button_continue');

						$data['breadcrumbs'] = array();

						$data['breadcrumbs'][] = array(
							'text' => $this->language->get('text_home'),
							'href' => $this->url->link('common/home')
						);

						$data['breadcrumbs'][] = array(
							'text' => $this->language->get('text_account'),
							'href' => $this->url->link('account/account', '', 'SSL')
						);

						$data['breadcrumbs'][] = array(
							'text' => $this->language->get('heading_title'),
							'href' => $this->url->link('account/order', '', 'SSL')
						);

						$data['breadcrumbs'][] = array(
							'text' => $this->language->get('text_order'),
							'href' => $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL')
						);

						$data['continue'] = $this->url->link('account/order', '', 'SSL');

						$data['column_left'] = $this->load->controller('common/column_left');
						$data['column_right'] = $this->load->controller('common/column_right');
						$data['content_top'] = $this->load->controller('common/content_top');
						$data['content_bottom'] = $this->load->controller('common/content_bottom');
						$data['footer'] = $this->load->controller('common/footer');
						$data['header'] = $this->load->controller('common/header');

						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
							$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
						} else {
							$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
						}
					}
				}
			 
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/order', $url, 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_total'] = $this->language->get('column_total');

		$data['button_view'] = $this->language->get('button_view');
		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}


				$data['customer_invoice_status'] = $this->config->get('customer_invoice_status');
				
				$data['button_print_invoice'] = $this->language->get('button_print_invoice');
			 
		$data['orders'] = array();

		$this->load->model('account/order');

		$order_total = $this->model_account_order->getTotalOrders();

		$results = $this->model_account_order->getOrders(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
			$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

			$data['orders'][] = array(

				'invoice_href' => $this->url->link('account/order/customerPrintInvoice', 'order_id=' . $result['order_id'], 'SSL'),
			 
				'order_id'   => $result['order_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'products'   => ($product_total + $voucher_total),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'href'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
			);
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/order', 'page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

		$data['continue'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/order_list.tpl', $data));
		}
	}

	public function info() {
		$this->load->language('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		if ($order_info) {
			$this->document->setTitle($this->language->get('text_order'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', $url, 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, 'SSL')
			);

			$data['heading_title'] = $this->language->get('text_order');

			$data['text_order_detail'] = $this->language->get('text_order_detail');
			$data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_comment'] = $this->language->get('text_comment');

			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_action'] = $this->language->get('column_action');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_comment'] = $this->language->get('column_comment');

			$data['button_reorder'] = $this->language->get('button_reorder');
			$data['button_return'] = $this->language->get('button_return');
			$data['button_continue'] = $this->language->get('button_continue');

			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['order_id'] = $this->request->get['order_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['payment_method'] = $order_info['payment_method'];

			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['shipping_method'] = $order_info['shipping_method'];

			$this->load->model('catalog/product');
			$this->load->model('tool/upload');

			// Products
			$data['products'] = array();

			$products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				$product_info = $this->model_catalog_product->getProduct($product['product_id']);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
				} else {
					$reorder = '';
				}

				$data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'reorder'  => $reorder,
					'return'   => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL')
				);
			}

			// Voucher
			$data['vouchers'] = array();

			$vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			// Totals
			$data['totals'] = array();

			$totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}

			$data['comment'] = nl2br($order_info['comment']);

			// History
			$data['histories'] = array();

			$results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

			foreach ($results as $result) {
				$data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
				);
			}

			$data['continue'] = $this->url->link('account/order', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');


				
				// check weather customer invoice print module is enable or not
				$data['customer_invoice_status'] = $this->config->get('customer_invoice_status');
				
				$data['button_print_invoice'] = $this->language->get('button_print_invoice');
				
				$data['invoice_href'] = $this->url->link('account/order/customerPrintInvoice', 'order_id=' . $this->request->get['order_id'], 'SSL');
				
			 
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_info.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/order_info.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/order_info.tpl', $data));
			}
		} else {
			$this->document->setTitle($this->language->get('text_order'));

			$data['heading_title'] = $this->language->get('text_order');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL')
			);

			$data['continue'] = $this->url->link('account/order', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}

	public function reorder() {
		$this->load->language('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		if ($order_info) {
			if (isset($this->request->get['order_product_id'])) {
				$order_product_id = $this->request->get['order_product_id'];
			} else {
				$order_product_id = 0;
			}

			$order_product_info = $this->model_account_order->getOrderProduct($order_id, $order_product_id);

			if ($order_product_info) {
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($order_product_info['product_id']);

				if ($product_info) {
					$option_data = array();

					$order_options = $this->model_account_order->getOrderOptions($order_product_info['order_id'], $order_product_id);

					foreach ($order_options as $order_option) {
						if ($order_option['type'] == 'select' || $order_option['type'] == 'radio' || $order_option['type'] == 'image') {
							$option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'checkbox') {
							$option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
							$option_data[$order_option['product_option_id']] = $order_option['value'];
						} elseif ($order_option['type'] == 'file') {
							$option_data[$order_option['product_option_id']] = $this->encryption->encrypt($order_option['value']);
						}
					}

					$this->cart->add($order_product_info['product_id'], $order_product_info['quantity'], $option_data);

					$this->session->data['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $product_info['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				} else {
					$this->session->data['error'] = sprintf($this->language->get('error_reorder'), $order_product_info['name']);
				}
			}
		}

		$this->response->redirect($this->url->link('account/order/info', 'order_id=' . $order_id));
	}
}