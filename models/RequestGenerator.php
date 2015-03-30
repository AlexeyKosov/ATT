<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 16.03.15
 * Time: 23:54
 */

namespace app\models;


class RequestGenerator {

    protected $tokenProvider;
    protected $sessionIdProvider;

    public function __construct() {
        $this->tokenProvider = new TokenProvider();
    }

    public function sendRequest(Orders $order) {
        $request = $this->_collectDataToSend($order);
//        $request = '{"unlockOrderRequest":{"language":"English","orderDetail":{"customerType":"Non-Customer","imei":"353411064660790","make":"Samsung","model":"SM-G900A","firstName":"mmm","lastName":"mmm","emailAddress":"d.reere@mail.ru","browserID":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36","source":"ATT","tcAccepted":true},"captcha":{"captchaType":"image","captchaId":' . $order->captchaId . ',"captchaResponse":"' . $order->captchaResult . '"}}}';
        $result = $this->_sendRequest($request);
        return $this->_extractRequestNumber($result);
    }

    protected function _collectDataToSend(Orders $order) {
        return array('unlockOrderRequest' => array('language' => 'English', 'orderDetail' => array('customerType' => 'Non-Customer', "imei" => $order->imei, "make" => $order->make, "model" => $order->phone_model, "firstName" => "mmm", "lastName" => "mmm", 'emailAddress' => 'd.reere@mail.ru', 'browserID' => TokenProvider::BROWSER, "source" => "ATT", "tcAccepted" => true,), 'captcha' => array('captchaType' => 'image', 'captchaId' => (int)$order->captchaId, 'captchaResponse' => $order->captchaResult,),));
    }

    protected function _sendRequest($request) {
        $requestSender = new RequestSender();
        $url = 'https://www.att.com/apis/deviceunlock/UnlockOrder/unlockOrder';
        $dataString = json_encode($request);
        return $requestSender->send($url, $dataString);
    }

    protected function _extractRequestNumber($responseFromServer) {
        $data = json_decode($responseFromServer, true);
        if ($this->_isValidResponse($data)) {
            return $this->_extractNumber($data);
        }
        return null;
    }

    protected function _extractNumber($response) {
        return $response['unlockOrderResponse']['unlockOrderDetail']['orderNumber'];
    }

    protected function _isValidResponse($response) {
        $serviceStatus = $response['unlockOrderResponse']['serviceStatus'];
        return $serviceStatus['code'] == 0;
    }

}