<?php
class ControllerModuleCatalogrules extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/catalogrules');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		$this->load->model('tool/image');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('catalogrules', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_price'] = $this->language->get('entry_price');

		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_date_start'] = $this->language->get('entry_date_start');

	
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['text_none'] = $this->language->get('text_none');
		$data['entry_type'] = $this->language->get('entry_type');

		$data['entry_discount_tax'] = $this->language->get('entry_discount_tax');

		$data['tax_with_discount'] = $this->language->get('tax_with_discount');
		$data['tax_without_discount'] = $this->language->get('tax_without_discount');

		$data['help_type_discount'] = $this->language->get('help_type_discount');
		$data['help_type_price'] = $this->language->get('help_type_price');
		
		$data['text_amount'] = $this->language->get('text_amount');
		$data['text_percent'] = $this->language->get('text_percent');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/catalogrules', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/catalogrules', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['catalogrules_status'])) {
			$data['catalogrules_status'] = $this->request->post['catalogrules_status'];
		} else {
			$data['catalogrules_status'] = $this->config->get('catalogrules_status');
		}


		if (isset($this->request->post['catalogrules_price'])) {
			$data['catalogrules_price'] = $this->request->post['catalogrules_price'];
		} else {
			$data['catalogrules_price'] = $this->config->get('catalogrules_price');
		}

		if (isset($this->request->post['catalogrules_date_start'])) {
			$data['catalogrules_date_start'] = $this->request->post['catalogrules_date_start'];
		} else {
			$data['catalogrules_date_start'] = $this->config->get('catalogrules_date_start');
		}

		if (isset($this->request->post['catalogrules_date_end'])) {
			$data['catalogrules_date_end'] = $this->request->post['catalogrules_date_end'];
		} else {
			$data['catalogrules_date_end'] = $this->config->get('catalogrules_date_end');
		}

		if (isset($this->request->post['catalogrules_type'])) {
			$data['catalogrules_type'] = $this->request->post['catalogrules_type'];
		} else {
			$data['catalogrules_type'] = $this->config->get('catalogrules_type');
		}

		if (isset($this->request->post['catalogrules_discount_tax'])) {
			$data['catalogrules_discount_tax'] = $this->request->post['catalogrules_discount_tax'];
		} else {
			$data['catalogrules_discount_tax'] = $this->config->get('catalogrules_discount_tax');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['catalogrules_tax_class_id'])) {
			$data['catalogrules_tax_class_id'] = $this->request->post['catalogrules_tax_class_id'];
		} else {
			$data['catalogrules_tax_class_id'] = $this->config->get('catalogrules_tax_class_id');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/catalogrules.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/catalogrules')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}