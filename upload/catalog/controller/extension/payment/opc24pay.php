<?php

/**
 * Description of paygate_24pay
 *
 * @author durcak
 */

class ControllerExtensionPaymentOpc24Pay extends Controller{

		const PAY_BUTTON = 'https://www.24-pay.sk/wp-content/themes/24pay/images/logo.gif';
        const VERSION = '2.3.0';


        public function index()
        {
            $data['opc24pay_button'] = self::PAY_BUTTON;
            $data['action'] = $this->url->link('extension/payment/opc24pay/pay','', true);

            return $this->load->view('extension/payment/opc24pay', $data);
        }
        
        public function pay(){
            $return = array();
            
            if ($this->session->data['payment_method']['code'] == 'opc24pay') {
                $this->load->model('checkout/order');
                $this->load->model('setting/setting');
                
                $setting = $this->model_setting_setting->getSetting('opc24pay');
				
				/** IF YOU WANT CHANGE ORDER STATUS ON PAYMENT REQUEST **/
				//$this->model_checkout_order->addOrderHistory($order_id, 2, "24-pay: Request");
				
                $data = array();
                $data['Test'] = $setting['opc24pay_test'];
                $data['Mid'] = $setting['opc24pay_mid'];
                $data['EshopId'] = $setting['opc24pay_eshopid'];
                $data['Key'] = $setting['opc24pay_key'];
                
                $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
                
                $data['FirstName'] = $order_info['payment_firstname'];
                $data['FamilyName'] = $order_info['payment_lastname'];
                $data['MsTxnId'] = $order_info['order_id'];
                $data['Amount'] = number_format((float)$order_info['total'], 2, '.', '');
                $data['CurrAlphaCode'] = $order_info['currency_code'];
                
                if ($order_info['customer_id']==0)
                    $data['ClientId'] = "NOREG".$order_info['customer_id'];
                else
                    $data['ClientId'] = "OPC".$order_info['customer_id'];
                $data['Email'] = $order_info['email'];
                $data['Country'] = $order_info['payment_iso_code_3'];
                
                $data['Timestamp'] = date("Y-m-d H:i:s");

                //$order_info['store_url'];
                $data['NURL'] = $this->url->link('extension/payment/opc24pay/nurl','', true);
                $data['RURL'] = $this->url->link('extension/payment/opc24pay/rurl','', true);
                
                $return['form'] = $this->buildForm($data);
                $return['status'] = "SUCCESS";
            }
            else{
                
                $return['message'] = "Something went wrong";
                $return['code'] = $this->session->data['payment_method']['code'];
                $return['status'] = "FAIL";
            }
            
            
            echo json_encode($return);
            exit();
        }
        
        private function buildForm($data){
            $sign = $this->sign($data);
            
            $formStr = "";
            if ($data['Test'] == 1)
                $formStr = "<form id='opc24pay-submitform' method='post' action='https://doxxsl-staging.24-pay.eu/pay_gate/paygt' >";
            else
                $formStr = "<form id='opc24pay-submitform' method='post' action='https://admin.24-pay.eu/pay_gate/paygt' >";
            
            $formStr .= "<input type='hidden' name='Mid' value='".$data['Mid']."' />";
            $formStr .= "<input type='hidden' name='EshopId' value='".$data['EshopId']."' />";
            $formStr .= "<input type='hidden' name='MsTxnId' value='".$data['MsTxnId']."' />";
            $formStr .= "<input type='hidden' name='Amount' value='".$data['Amount']."' />";
            $formStr .= "<input type='hidden' name='CurrAlphaCode' value='".$data['CurrAlphaCode']."' />";
            $formStr .= "<input type='hidden' name='ClientId' value='".$data['ClientId']."' />";
            $formStr .= "<input type='hidden' name='FirstName' value='".$data['FirstName']."' />";
            $formStr .= "<input type='hidden' name='FamilyName' value='".$data['FamilyName']."' />";
            $formStr .= "<input type='hidden' name='Email' value='".$data['Email']."' />";
            $formStr .= "<input type='hidden' name='Country' value='".$data['Country']."' />";
            $formStr .= "<input type='hidden' name='Timestamp' value='".$data['Timestamp']."' />";
            $formStr .= "<input type='hidden' name='NURL' value='".$data['NURL']."' />";
            $formStr .= "<input type='hidden' name='RURL' value='".$data['RURL']."' />";
            /*
            $formStr .= "<input type='hidden' name='NURL' value='".str_replace("localhost","24-pay.sk",$data['NURL'])."' />";
            $formStr .= "<input type='hidden' name='RURL' value='".str_replace("localhost","24-pay.sk",$data['RURL'])."' />";
             */
            $formStr .= "<input type='hidden' name='Sign' value='".$sign."' />";
            $formStr .= "<input type='hidden' name='Debug' value='true' />";
            $formStr .= "</form>";
            
            return $formStr;
        }
        
        private function sign($data){
            $message = $data['Mid'].$data['Amount'].$data['CurrAlphaCode'].$data['MsTxnId'].$data['FirstName'].$data['FamilyName'].$data['Timestamp'];

            $hash = hash("sha1", $message, true);
            $iv = $data['Mid'] . strrev($data['Mid']);

            $key = pack('H*', $data['Key']);

            if ( PHP_VERSION_ID >= 50303 && extension_loaded( 'openssl' ) ) {
                    $crypted = openssl_encrypt( $hash, 'AES-256-CBC', $key, 1, $iv );
            } else {
                    $crypted = mcrypt_encrypt( MCRYPT_RIJNDAEL_128, $key, $hash, MCRYPT_MODE_CBC, $iv );
            }
            $sign = strtoupper(bin2hex(substr($crypted, 0, 16)));
            
            return $sign;
        }
        
        public function rurl(){
            $order_id = $this->request->get["MsTxnId"];
            $total = $this->request->get["Amount"];
            $currency_code = $this->request->get["CurrCode"];
            $result = $this->request->get["Result"];

            switch ($result) {
                case "OK":
                case "PENDING":
                        $this->response->redirect($this->url->link('checkout/success'));
                        break;

                case "FAIL":
                        $this->response->redirect($this->url->link("checkout/cart"));
                        break;
            }
            die("Invalid arguments");
        }
        
        
        public function nurl(){
            if (isset($_POST["params"])) {
                $params = $_POST["params"];	
            } else {
                echo "Invalid notification params";
                die();
            }

            $twentyfourpay_notification = $this->parseNotification($params);

            if (!$twentyfourpay_notification['Valid']){
                die("Invalid response from 24pay gateway");
                print_r($twentyfourpay_notification);
            }

            $this->load->model('checkout/order');

            $order_id = $twentyfourpay_notification['MsTxnId'];
            
            $order = $this->model_checkout_order->getOrder($order_id);
            $result = $twentyfourpay_notification['Result'];

            switch ($result) {
                    case "PENDING":
                            if (!$order["order_status_id"])
                                    // change order status to pending (waiting for payment transation to be completed)
                                    $this->model_checkout_order->addOrderHistory($order_id, 1, "24-pay: Notification with result PENDING");
                            break;

                    case "OK":
                            if (!$order["order_status_id"] || $order["order_status_id"] == 1)
                                    // change order status to precessing (already payed, waiting for shippment to be expedited)
                                    $this->model_checkout_order->addOrderHistory($order_id, 2, "24-pay: Notification with result OK");
                                    $this->model_checkout_order->addOrderHistory($order_id, 2, "24-pay: PAYMENT SUCCESSFULLY RECIEVED");
                            break;

                    case "FAIL": default:
                            if (!$order["order_status_id"] || $order["order_status_id"] == 1)
                                    // change to pending sttau with note about failed transaction
                                    $this->model_checkout_order->addOrderHistory($order_id, 1, "24-pay: Notification with result FAIL");
                            break;
            }

            die("OK");
        }
        
        private function computeSign($message, $mid, $key){
            $hash = hash("sha1", $message, true);
            $iv = $mid . strrev($mid);

            $key = pack('H*', $key);

            if ( PHP_VERSION_ID >= 50303 && extension_loaded( 'openssl' ) ) {
                    $crypted = openssl_encrypt( $hash, 'AES-256-CBC', $key, 1, $iv );
            } else {
                    $crypted = mcrypt_encrypt( MCRYPT_RIJNDAEL_128, $key, $hash, MCRYPT_MODE_CBC, $iv );
            }
            $sign = strtoupper(bin2hex(substr($crypted, 0, 16)));
            
            return $sign;
        }
        
        private function parseNotification($params){
            if (get_magic_quotes_gpc())
                $params = stripslashes($params);

            $params = trim(preg_replace("/^\s*<\?xml.*?\?>/i", "", $params));

            $xml = new SimpleXMLElement($params);
            
            $result = array();
            
            if ($xml->count()==1){
                
                $this->load->model('setting/setting');
                
                $setting = $this->model_setting_setting->getSetting('opc24pay');
                
                $mid = $setting['opc24pay_mid'];
                $key = $setting['opc24pay_key'];
                
                // SIGN
                $node = $xml[0];
                $attributes = $node->attributes();
                $result['Sign'] = (string) $attributes["sign"];
                // AMOUNT
                $result['Amount'] = (string) $xml->Transaction->Presentation->Amount;
                // CURRENCY
                $result['Currency'] = (string) $xml->Transaction->Presentation->Currency;
                // PSPTXNID
                $result['PspTxnId'] = $xml->Transaction->Identification->PspTxnId;
                // MSTXNID
                $result['MsTxnId'] = (string) $xml->Transaction->Identification->MsTxnId;
                // TIMESTAMP
                $result['Timestamp'] =  (string) $xml->Transaction->Processing->Timestamp;
                // RESULT
                $result['Result'] = (string) $xml->Transaction->Processing->Result;
                
                $message = $mid.$result['Amount'].$result['Currency'].$result['PspTxnId'].$result['MsTxnId'].$result['Timestamp'].$result['Result'];
                
                $signCandidate = $this->computeSign($message,$mid,$key);
                if ($signCandidate == $result['Sign']){
                    $result['Valid'] = true;
                }
                else{
                    $result['ValidSign'] = $signCandidate;
                }
            }
            else{
                $result['Valid'] = false;
            }
            
            return $result;
        }
    
}

