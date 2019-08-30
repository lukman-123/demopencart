<?php
class ControllerModuleCatalogruleslogo extends Controller {
	public function index() {

    	if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
	    if (is_file(DIR_IMAGE . $this->config->get('catalogruleslogo_logo'))) {
			$data['catalogruleslogo_logo'] = $server . 'image/' . $this->config->get('catalogruleslogo_logo');
		} else {
			$data['catalogruleslogo_logo'] = '';
		} 

		if ($this->config->get('catalogruleslogo_width')) {
			$data['catalogruleslogo_width'] = $this->config->get('catalogruleslogo_width');
		} else {
			$data['catalogruleslogo_width'] = 50;
		}

		if ($this->config->get('catalogruleslogo_height')) {
			$data['catalogruleslogo_height'] = $this->config->get('catalogruleslogo_height');
		} else {
			$data['catalogruleslogo_height'] = 50;
		}


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/catalogruleslogo.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/catalogruleslogo.tpl', $data);
		} else {
			return $this->load->view('default/template/module/catalogruleslogo.tpl', $data);
		}
	}
}