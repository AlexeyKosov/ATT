<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 17.03.15
 * Time: 23:38
 */

namespace app\models;


use app\models\ConfirmRequestProvider\Params;

class ConfirmRequestProvider {

    public function confirmRequest($href) {
        $requestSender = new RequestSender();
        $params = $this->_extractParams($href);
        $dataForSend = $this->_collectParams($params);
        $url = 'https://www.att.com/apis/deviceunlock/UnlockUtility/Verify/VerifyEmail';
        $result = $requestSender->send($url, json_encode($dataForSend));
        return $this->_extractResponse($result);
    }

    protected function _extractParams($href) {
        parse_str(parse_url($href, PHP_URL_QUERY), $params);
        $extractedParams = new Params();
        if (!empty($params['requestId'])) {
            $extractedParams->requestId = $params['requestId'];
        }
        if (!empty($params['transactionId'])) {
            $extractedParams->transactionId = $params['transactionId'];
        }
        return $extractedParams;
    }

    protected function _collectParams(Params $params) {
        return array('unlockVerifyEmailRequest' => array(
            'requestId' => $params->requestId,
            'transactionId' => $params->transactionId
        ));
    }

    protected function _extractResponse($response) {
        $extractedResponse = json_decode($response, true);
        return $this->_isValidResponse($extractedResponse);
    }

    protected function _isValidResponse($response) {
        $serviceStatus = $response['unlockVerifyEmailResponse']['serviceStatus'];
        return $serviceStatus['code'] == 0;
    }

}