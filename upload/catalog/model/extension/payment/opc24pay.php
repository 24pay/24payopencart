<?php

/* Moneybrace online payment
 *
 * @version 1.0
 * @date 11/03/2012
 * @author george zheng <xinhaozheng@gmail.com>
 * @more info available on mzcart.com
 */


class ModelExtensionPaymentOpc24Pay extends Model
{
    public function getMethod($address, $total)
    {
        $this->load->language('extension/payment/opc24pay');

        $status = true;
        
        $method_data = array();
        if ($status) {
            $method_data = array(
                'code' => 'opc24pay',
                'title' => '24-pay',
                'terms' => 'Toto sú podmienky',
                //'sort_order' => $this->config->get('payu_sort_order')
                'sort_order' => 1
            );
        }

        return $method_data;
    }
}