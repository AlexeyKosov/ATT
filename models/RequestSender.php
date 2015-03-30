<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 17.03.15
 * Time: 22:14
 */

namespace app\models;


class RequestSender {
    protected $token;
    public function __construct() {
        $this->token = TokenProvider::getCurrentToken();
    }

    public function send($url, $dataString, $token = null) {
        $cookies = __DIR__ . '/cookie.txt';

        if (empty($token)) {
            $token = $this->token;
        }
        $user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Host: www.att.com',
                'Connection: keep-alive',
                'Content-Length: ' . strlen($dataString),
                'OWASP-CSRFTOKEN: ' . $token,
                'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36',
                'Content-Type: application/json;charset=UTF-8',
            )
        );

        return curl_exec($ch);
    }
}