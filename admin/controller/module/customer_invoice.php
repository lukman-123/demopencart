<?php
class ControllerModuleCustomerInvoice extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/customer_invoice');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('customer_invoice', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_store_logo'] = $this->language->get('entry_store_logo');
		$data['entry_guest_customer'] = $this->language->get('entry_guest_customer');
		
		$data['help_store_logo'] = $this->language->get('help_store_logo');
		$data['help_guest_customer'] = $this->language->get('help_guest_customer');

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
			'href' => $this->url->link('module/customer_invoice', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/customer_invoice', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['customer_invoice_status'])) {
			$data['customer_invoice_status'] = $this->request->post['customer_invoice_status'];
		} else {
			$data['customer_invoice_status'] = $this->config->get('customer_invoice_status');
		}
		
		if (isset($this->request->post['customer_invoice_store_logo'])) {
			$data['customer_invoice_store_logo'] = $this->request->post['customer_invoice_store_logo'];
		} else {
			$data['customer_invoice_store_logo'] = $this->config->get('customer_invoice_store_logo');
		}
		
		if (isset($this->request->post['customer_invoice_guest'])) {
			$data['customer_invoice_guest'] = $this->request->post['customer_invoice_guest'];
		} else {
			$data['customer_invoice_guest'] = $this->config->get('customer_invoice_guest');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/customer_invoice.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/customer_invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
