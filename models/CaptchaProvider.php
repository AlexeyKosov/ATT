<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 15.03.15
 * Time: 10:58
 */

namespace app\models;


use app\models\CaptchaProvider\Captcha;

class CaptchaProvider {

    public function getCaptcha() {
        return $this->_getCaptcha();
    }

    protected function _getCaptcha() {
        $dirtyCaptcha = $this->_getCaptchaResponse();
        return $this->_getCaptchaInfo($dirtyCaptcha);
    }

    protected function _getCaptchaResponse() {
        $requestSender = new RequestSender();
        $url = 'https://www.att.com/apis/deviceunlock/unlockCaptcha/image';
        $data = array('imageCaptchaRequest' => array('captchaType' => 'image'));
        $dataString = json_encode($data);
        return $requestSender->send($url, $dataString);
    }

    protected function _getCaptchaInfo($getCaptchaResponse) {
        $json = json_decode($getCaptchaResponse, true);
        if ($this->_isValidResponse($json)) {
            return $this->_extractCaptchaInfo($json);
        }
        return null;
    }

    protected function _extractCaptchaInfo($getCaptchaResponseJSON) {
        $imageCaptchaResponse = $getCaptchaResponseJSON['imageCaptchaResponse']['captchaDetail'];
        $captcha = new Captcha();
        $captcha->imageContent = $imageCaptchaResponse['captchaChallenge'];
        $captcha->id = $imageCaptchaResponse['captchaID'];
        return $captcha;
    }

    protected function _isValidResponse($response) {
        $serviceStatus = $response['imageCaptchaResponse']['serviceStatus'];
        return $serviceStatus['code'] == 0;
    }
}