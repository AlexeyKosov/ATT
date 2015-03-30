<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 20.03.15
 * Time: 23:16
 */

namespace app\models\Letters;

use app\models\Letters;

class LetterListProvider {

    /** @var LettersLinkProvider */
    protected $lettersLinkProvider;
    /** @var AuthTokenProvider */
    protected $authTokenProvider;
    /** @var string[] */
    protected $letterLinkList;
    protected $letterPrefix = 'https://e.mail.ru/';

    public function __construct() {
        $this->authTokenProvider = new AuthTokenProvider();
        $this->lettersLinkProvider = new LettersLinkProvider($this->authTokenProvider);
    }

    protected function _init() {
        $this->letterLinkList = $this->lettersLinkProvider->loadNewLetters();
    }

    /**
     * @return Letters[]
     */
    public function loadLetterList() {
        if (empty($this->letterLinkList)) {
            $this->_init();
        }
        $extractedLetterList = array();
        foreach ($this->letterLinkList as $letterLink) {
//        $letterLink = 'message/14266590420000000373/';
            $extractedLetterList[] = $this->getLetter($this->letterPrefix . $letterLink);
        }
        return $extractedLetterList;
    }

    protected function getLetter($letterLink) {
        $letterContent = $this->loadLetter($letterLink);
        return $this->extractLetter($letterContent);
    }

    protected function loadLetter($letterLink) {
        $red_book_cms = $this->authTokenProvider->loadData();

        curl_setopt($red_book_cms, CURLOPT_URL, $letterLink);
        $html = curl_exec($red_book_cms);
        curl_close($red_book_cms);
        return $html;
//        return file_get_contents(__DIR__ . '/letter.html');

    }

    protected function extractLetter($letterContent) {
        $letterContent = $this->clearLetter($letterContent);
        $letterTypeProvider = new LetterTypeProvider();
        return $letterTypeProvider->getLetterType($letterContent);
    }

    protected function clearLetter($letterContent) {
        $letterContent = str_replace("\n", '', $letterContent);
        $letterContent = str_replace("\t", '', $letterContent);
        $letterContent = str_replace("  ", ' ', $letterContent);
        $parts = explode('mr_read__body__content">', $letterContent);
        $parts2 = explode('</div><base target="_self" href="https://e.mail.ru/cgi-bin/" />', $parts[1]);
        return $parts2[0];
    }

    // надо подождать
    //<font face="Arial"><br ><p>Unlock request number: <strong>2013804252</strong></p><p>Hello,</p><p>Your request for device unlock requires additional research to determine eligibility. Please be assured that we are actively working on it. We&#8217;ll send a response as soon as possible.</p><p>We apologize for the inconvenience.</p><p>Questions? Please call 800.331.0500, or dial 611 from your AT&amp;T wireless phone.</p><br >          Sincerely,          <br ></font><font face="Arial">          The AT&amp;T Customer Care Team        </font><br ><br ><font color="#808080" face="Arial"><b>DO NOT REPLY TO THIS MESSAGE</b><br >          All replies are automatically deleted.        </font>
    //подтверждение почты
    //
    //Ошибка отвязки
    //<font face="Arial"><br ><p>Unlock request number: <strong>2013937777</strong></p><p>Thank you for contacting AT&amp;T about unlocking your mobile device.</p><p>We&#8217;re sorry, but your unlock request <strong>2013937777</strong> was canceled because: </p><p>You did not confirm it by email within 24 hours.</p><p>You can <a href="/cgi-bin/link?check=1&cnf=826680&url=https%3A%2F%2Fatt.com%2Fdeviceunlock&msgid=14266590420000000373&x-email=d.reere%40mail.ru" target="_blank" >submit</a> a new request here.</p><p>Questions? Please call 800.331.0500, or dial 611 from your AT&amp;T wireless phone.</p><br > Sincerely, </font><br ><font face="Arial">The AT&amp;T Customer Care Team </font><br ><br ><font color="#808080" face="Arial"><b>DO NOT REPLY TO THIS MESSAGE</b><br > All replies are automatically deleted.        </font>

}