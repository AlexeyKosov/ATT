<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 20.03.15
 * Time: 22:32
 */

namespace app\models\Letters;

use app\models\Letters;

class LettersLinkProvider {
    /** @var AuthTokenProvider */
    protected $authTokenProvider;

    public function __construct(AuthTokenProvider $authTokenProvider) {
        $this->authTokenProvider = $authTokenProvider;
    }

    public function loadNewLetters() {
        $this->authTokenProvider->loadData();
        $letterList = $this->loadData();
        return $this->extractData($letterList);
    }

    protected function loadData() {
        $red_book_cms = $this->authTokenProvider->loadData();

        curl_setopt($red_book_cms, CURLOPT_URL, "https://e.mail.ru/search/?q_read=2&q_folder=all");
        $html = curl_exec($red_book_cms);

        curl_close($red_book_cms);
        return $html;
    }

    /**
     * @param string $data
     * @return string[]
     */
    protected function extractData($data) {
        preg_match_all('/message\/[0-9]{1,}\/\?folder=0/', $data, $dirtyLinkList);
        return $this->clearLinkList($dirtyLinkList[0]);
    }

    protected function clearLinkList($dirtyLinkList) {
        $result = array();
        foreach ($dirtyLinkList as $link) {
            if (!in_array($link, $result)) {
                $result[] = $link;
            }
        }
        return $result;
    }

}
