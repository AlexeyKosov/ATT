<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 20.03.15
 * Time: 22:36
 */

namespace app\models\Letters;


class AuthTokenProvider {
    protected $login='d.reere';
    protected $password='';
    protected $domain = 'mail.ru';
    protected $pathToCookie;

    public function __construct() {
        $this->pathToCookie = __DIR__ . '/att_unlock/cookies.txt';
    }

    public function getPathToCookie() {
        return $this->pathToCookie;
    }

    public function loadData() {
        $user_agent = 'Opera/9.62 (Windows NT 6.0; U; ru) Presto/2.1.1';
        $cookies = dirname(__DIR__) . '/Letters/cookie.txt';

        $red_book_cms = curl_init();

        curl_setopt($red_book_cms, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($red_book_cms, CURLOPT_REFERER, "http://mail.ru/");
        curl_setopt($red_book_cms, CURLOPT_TIMEOUT, 10);

        curl_setopt($red_book_cms, CURLOPT_URL,
            "https://auth.mail.ru/cgi-bin/auth?Domain={$this->domain}&Login={$this->login}&Password={$this->password}");

        curl_setopt($red_book_cms, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($red_book_cms, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($red_book_cms, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($red_book_cms, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($red_book_cms, CURLOPT_COOKIEFILE, $cookies);
        curl_setopt($red_book_cms, CURLOPT_COOKIEJAR, $cookies);

        curl_exec($red_book_cms);

        curl_setopt($red_book_cms, CURLOPT_URL, "https://e.mail.ru/search/?q_read=2&q_folder=all");
        curl_exec($red_book_cms);
        return $red_book_cms;
    }
}