<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 15.03.15
 * Time: 11:12
 */

namespace app\models;


use app\models\PhoneModelProvider\PhoneModel;

class PhoneModelProvider {

    public function getPhoneModel($imei) {
        return $this->_getPhoneModel($imei);
    }

    protected function _getPhoneModel($imei) {
        $phoneModelJson = $this->_getPhoneModelJSON($imei);
        return $this->_getExtractedPhoneModel($phoneModelJson);
    }

    protected function _getPhoneModelJSON($imei) {
        $requestSender = new RequestSender();
        $url = 'https://www.att.com/apis/deviceunlock/UnlockUtility/lookupimei';
        $data = array('imeiLookupRequest' => array('imei' => $imei));
        $dataString = json_encode($data);
        return $requestSender->send($url, $dataString);
    }

    protected function _getExtractedPhoneModel($phoneModelJson) {
        $phoneModel = json_decode($phoneModelJson, true);
        if ($this->_isSuccessResponse($phoneModel)) {
            $imeiDetail = $phoneModel['imeiSearchResponse']['imeiDetail'];
            $extractedPhoneModel = new PhoneModel();
            $extractedPhoneModel->make = $imeiDetail['make'];
            $extractedPhoneModel->model = $imeiDetail['model'];
            return $extractedPhoneModel;
        }
        return null;
    }

    protected function _isSuccessResponse(array $phoneModelResponse) {
        $serviceStatus = $phoneModelResponse['imeiSearchResponse']['serviceStatus'];
        return $serviceStatus['code'] == 0;
    }
}