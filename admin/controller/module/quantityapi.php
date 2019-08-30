<?php
class ControllerModuleQuantityapi extends Controller {
	private $error = array();
	const getImageName = 32;
	const apiDataGetKey = 3;
	const productName = 4;
	const productDescription = 5;
	const productPrice = 8;
	const manufacturerName = 2;
	const upc = 34;
	const height = 29;
	const width = 30;

	const mainCategory = 23;
	const subCategory = 24;

	const categories = 37;


	public function index() {
       
		$this->load->language('module/quantityapi');

		$newProductsList = array();

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/quantityapi');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			

              $fileName = $_FILES["file"]["tmp_name"];

			 if ($_FILES["file"]["size"] > 0) {
			 	$file = fopen($fileName, "r");

			 	$i=0;


				while (($file_data = fgetcsv($file, 5000, ",")) !== FALSE) {


				if($i>0){

	      			$getImageName = $file_data[self::getImageName];

				    $apiDataGetKey = $file_data[self::apiDataGetKey];    
				  
	                //$this->apiData($file_data);

				    $apiDataGetKey = $file_data[self::apiDataGetKey];    

					$results_api = $this->apiUrl($apiDataGetKey);

					$apiCountData = $results_api->Data[0];

					$quantitesApi = $apiCountData->QtyAvailable;   //Get Quantity From Api Data

					$ItemIDApi = $apiCountData->ItemID; //Get Modal From Api Data

					$implodeItemId = explode(',', ltrim($ItemIDApi,'0'));  //convert in array

				    $query = $this->model_catalog_quantityapi->getcheckModelQuery($implodeItemId);

						$dataImport = array(
							'getImageName'	  => $file_data[self::getImageName],
							'apiDataGetKey'	  => $file_data[self::apiDataGetKey],
							'productName'	  => $file_data[self::productName],
							'productDescription' => $file_data[self::productDescription],
							'productPrice'   => $file_data[self::productPrice],
							'manufacturerName'  => $file_data[self::manufacturerName],
							'upc'           => $file_data[self::upc],
							'metaTitle'           => $file_data[self::productName],
							'height'           => $file_data[self::height],
							'width'           => $file_data[self::width],
							'categoryProducts'           => $file_data[self::categories],
							'quantitesApi'           => $quantitesApi,
							'ItemIDApi'              => $ItemIDApi,
							'implodeItemId'              => $implodeItemId,
							'query'              => $query,
						);


						if($query->num_rows)
						{
						
							if($implodeItemId[0] != 0)
							{

							 $this->model_catalog_quantityapi->updateData($dataImport);	
				             $this->makeLog($implodeItemId,$Status = 'Updated');
				 
							}
					    }else
					    {


							if($implodeItemId[0] != 0)
							{
								$this->model_catalog_quantityapi->insertData($dataImport);
								$this->makeLog($implodeItemId,$Status = 'Inserted');

								$newProductsList[] = array(
				                $file_data[self::productName],$implodeItemId[0]
				                                    );

							
							}
						
					    }

	                $this->imageSave($getImageName);

	                $this->apiUrl($apiDataGetKey);

				}

				$i=1;

				}
        if (count($newProductsList)) {
            $this->sendNewProductsAlertMail($newProductsList);
        } else {
            $this->sendProductUpdateMail();
        }

		//	die();

			 }


			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('module/quantityapi', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_lable_file'] = $this->language->get('entry_lable_file');

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
			'href' => $this->url->link('module/quantityapi', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/quantityapi', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/quantityapi.tpl', $data));
	}


    private function sendProductUpdateMail () {
        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
        $mail->setTo('lukman.shaikh@agileinfoways.com');        
        $subject = 'Harco Product Update';
        $message = "Harco product update complete. No new Harco products have been added to the database."; 
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode('Company Payroll Store Product Import - Harco', ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setText($message);
        $mail->send();
    }
    private function sendNewProductsAlertMail ($products) {
        $inputFileName = DIR_SYSTEM . 'storage/harco_new_products_log'.date("Y-m-d").'.csv';
        $fileHandle = @fopen($inputFileName, "w+");
        // Header
        fputcsv($fileHandle, array('Product Name','Product Model'));
        // Content
        foreach ($products as $product) {
            fputcsv($fileHandle, $product);
        }

        fclose($fileHandle);

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
        $mail->setTo('lukman.shaikh@agileinfoways.com');       
        $subject = 'New Harco Products Alert';
        $message = "New Harco Products have been added to Database.\n Please go through the CSV file attached.";
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode('Company Payroll Store Product Import - Harco', ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setText($message);
        $mail->addAttachment($inputFileName);
        $mail->send();
    }

	public function imageSave($getImageName) {

			$dir = DIR_IMAGE_INSERT_API.$getImageName; 

			    if(!file_exists($dir))
			    {

					$imageUrl = 'http://www.harcoproductimages.com/'.$getImageName;

					$img = DIR_IMAGE_INSERT_API.$getImageName;

					file_put_contents($img, file_get_contents($imageUrl)); 

			    }

	}


	public function makeLog($implodeItemId,$Status) {

            $dir = DIR_APPLICATION;
            $date = date("F d, Y h:i:s", time());

		    $log = 'Status:'.$Status .'   '."Model:".$implodeItemId[0]."        ".$date;
		
			
		    $log_filename = $dir."/log";
		    if (!file_exists($log_filename))
		    {
		     
		        mkdir($log_filename, 0777, true);
		    }
		    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
		    file_put_contents($log_file_data, $log."\n", FILE_APPEND);

	}




	public function apiUrl($apiDataGetKey) {

	    $api_url = 'https://www.harcoapi.com/v3/CheckInventory/'.$apiDataGetKey;
	    
	    $client = curl_init($api_url);
	    curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($client, CURLOPT_HTTPHEADER, array('UserName:CAR0150','Password:CAR0150Harco2018','Account:CAR0150'));
	    $response = curl_exec($client);
	     
	    return json_decode($response);

	}



	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/quantityapi')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}