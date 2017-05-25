<?php

class ControllerExtensionPaymentOpc24pay extends Controller
{
    private $error = array();
    private $settings = array();

    //Config page
    public function index()
    {
        $this->load->language('extension/payment/opc24pay');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        //new config
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('opc24pay', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], true));
        }
        
        $this->setting = $this->model_setting_setting->getSetting('opc24pay');
        
        $data['error_warning'] = false;
        //language data
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_order'] = $this->language->get('entry_order');
        $data['entry_test'] = $this->language->get('entry_test');
        $data['entry_mid'] = $this->language->get('entry_mid');
        $data['entry_eshopid'] = $this->language->get('entry_eshopid');
        $data['entry_key'] = $this->language->get('entry_key');
        
		if (isset($this->error['error_mid'])) {
			$data['error_mid'] = $this->error['error_mid'];
		} else {
			$data['error_mid'] = '';
		}
        
		
		if (isset($this->error['error_eshopid'])) {
			$data['error_eshopid'] = $this->error['error_eshopid'];
		} else {
			$data['error_eshopid'] = '';
		}
        
		
		if (isset($this->error['error_key'])) {
			$data['error_key'] = $this->error['error_key'];
		} else {
			$data['error_key'] = '';
		}
        
		
		if (isset($this->error['error_sort_order'])) {
			$data['error_sort_order'] = $this->error['error_sort_order'];
		} else {
			$data['error_sort_order'] = '';
		}
		
		
        
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        
        $data['opc24pay_status'] = $this->setting['opc24pay_status'];
        $data['opc24pay_sort_order'] = $this->setting['opc24pay_sort_order'];
        $data['opc24pay_test'] = $this->setting['opc24pay_test'];
        $data['opc24pay_mid'] = $this->setting['opc24pay_mid'];
        $data['opc24pay_eshopid'] = $this->setting['opc24pay_eshopid'];
        $data['opc24pay_key'] = $this->setting['opc24pay_key'];
        
        $data['text_edit'] = $this->language->get('text_edit');
        // MANDATORY DATA
        
        //Breadcroumbs
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/opc24pay', 'token=' . $this->session->data['token'], true)
        );
        
        $data['action'] = $this->url->link('extension/payment/opc24pay', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true);
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('extension/payment/opc24pay', $data));

    } //index


    //validate
    private function validate()
    {
        //permisions
        if (!$this->user->hasPermission('modify', 'extension/payment/opc24pay')) {
            $this->error['warning'] = $this->language->get('warning');
        }
        //check for errors
		/*
        if (!$this->request->post['opc24pay_test']) {
            $this->error['error_test'] = $this->language->get('error_test');
        }
        if (!$this->request->post['opc24pay_status']) {
            $this->error['error_status'] = $this->language->get('error_status');
        }
		*/
        if (!$this->request->post['opc24pay_mid']) {
            $this->error['error_mid'] = $this->language->get('error_mid');
        }
        if (!$this->request->post['opc24pay_eshopid']) {
            $this->error['error_eshopid'] = $this->language->get('error_eshopid');
        }
        if (!$this->request->post['opc24pay_key']) {
            $this->error['error_key'] = $this->language->get('error_key');
        }
        if (!$this->request->post['opc24pay_sort_order']) {
            $this->error['error_sort_order'] = $this->language->get('error_sort_order');
        }

        return !$this->error;
    }

    public function install()
    {
        $this->load->model('setting/setting');
        $this->settings = array(
            'opc24pay_status' => 0,
            'opc24pay_test' => 1,
            'opc24pay_sort_order' => 1,
            'opc24pay_mid' => "demoOMED",
            'opc24pay_eshopid' => "11111111",
            'opc24pay_key' => "1234567812345678123456781234567812345678123456781234567812345678",
        );
        $this->model_setting_setting->editSetting('opc24pay', $this->settings);
        
    }

    public function uninstall()
    {
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('opc24pay');
    }
}
