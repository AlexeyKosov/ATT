<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 15.03.15
 * Time: 10:53
 */

namespace app\models;


class TokenProvider {

    const BROWSER = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36';

    protected static $currentToken = '';

    public static function getCurrentToken() {
        if (self::$currentToken != '') {
            return self::$currentToken;
        }
        return self::getAnotherToken();
    }

    public static function getAnotherToken() {
        $scriptContent = self::getDataFromServer();
        self::$currentToken = self::getTokenFromScriptData($scriptContent);
        return self::$currentToken;
    }

    protected static function getDataFromServer() {
        $ch = curl_init('https://www.att.com/apis/deviceunlock/csrfguard/JavaScriptServlet');
        $cookies = __DIR__ . '/cookie.txt';
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, self::BROWSER);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);

        return curl_exec($ch);
    }

    protected static function getTokenFromScriptData($scriptData) {
        if (preg_match('/(.*)injectTokens\("OWASP\-CSRFTOKEN", "(.*)"\);(.*)/', $scriptData, $matches) !== false) {
            return $matches[2];
        }

        return null;
    }
}

// на крайний случай попробовать асинхронный запрос.
// ну и в файл куки записались данные, можно все-таки использовать его