<?php
class ControllerModuleCatalogruleslogo extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/catalogruleslogo');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		$this->load->model('tool/image');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('catalogruleslogo', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');


		$data['entry_logo'] = $this->language->get('entry_logo');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_status'] = $this->language->get('entry_status');


        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_height'] = $this->language->get('entry_height');


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
			'href' => $this->url->link('module/catalogruleslogo', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/catalogruleslogo', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');


		if (isset($this->request->post['catalogruleslogo_status'])) {
			$data['catalogruleslogo_status'] = $this->request->post['catalogruleslogo_status'];
		} else {
			$data['catalogruleslogo_status'] = $this->config->get('catalogruleslogo_status');
		}


		if (isset($this->request->post['catalogruleslogo_logo'])) {
			$data['catalogruleslogo_logo'] = $this->request->post['catalogruleslogo_logo'];
		} else {
			$data['catalogruleslogo_logo'] = $this->config->get('catalogruleslogo_logo');
		}

		if (isset($this->request->post['catalogruleslogo_width'])) {
			$data['catalogruleslogo_width'] = $this->request->post['catalogruleslogo_width'];
		} else {
			$data['catalogruleslogo_width'] = $this->config->get('catalogruleslogo_width');
		}


		if (isset($this->request->post['catalogruleslogo_height'])) {
			$data['catalogruleslogo_height'] = $this->request->post['catalogruleslogo_height'];
		} else {
			$data['catalogruleslogo_height'] = $this->config->get('catalogruleslogo_height');
		}


		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['catalogruleslogo_logo']) && is_file(DIR_IMAGE . $this->request->post['catalogruleslogo_logo'])) {
			$data['logo'] = $this->model_tool_image->resize($this->request->post['catalogruleslogo_logo'], 100, 100);
		} elseif ($this->config->get('catalogruleslogo_logo') && is_file(DIR_IMAGE . $this->config->get('catalogruleslogo_logo'))) {
			$data['logo'] = $this->model_tool_image->resize($this->config->get('catalogruleslogo_logo'), 100, 100);
		} else {
			$data['logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/catalogruleslogo.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/catalogruleslogo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}