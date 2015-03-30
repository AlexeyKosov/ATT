<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 21.03.15
 * Time: 1:16
 */

namespace app\models\Letters;


use app\models\Letters;

class LetterTypeProvider {
    const CONFIRMED_EMAIL = 0;
    const NEED_WAITING = 1;
    const SUCCESS_UNLOCK_WITH_ID = 2;
    const SUCCESS_UNLOCK_WITHOUT_ID = 3;
    const FAIL_UNLOCK = -1;
    const UNDEFINED = -10;

    protected $letterContent;

    public function getLetterType($letterContent) {
        $this->letterContent = $letterContent;
        $letter = new Letters();
        $letter->order = $this->getRequestNumber();
        $letter->type = $this->getType();
        $letter->additionalInfo = $this->getAdditionalInfo($letter->type);
        $letter->content = $this->letterContent;
        return $letter;
    }

    protected function getType() {
        if ($this->isNeedWaiting()) {
            return self::NEED_WAITING;
        } elseif ($this->getErrorReason()) {
            return self::FAIL_UNLOCK;
        } elseif ($this->getUnlockCode()) {
            return self::SUCCESS_UNLOCK_WITH_ID;
        } elseif ($this->isSuccessWithoutCode()) {
            return self::SUCCESS_UNLOCK_WITHOUT_ID;
        } elseif ($this->getConfirmedLink()) {
            return self::CONFIRMED_EMAIL;
        } else {
            return self::UNDEFINED;
        }
    }

    /**
     * @return bool|string
     */
    protected function getErrorReason() {
        preg_match('/was canceled because: <\/p><p>(.*)<\/p><p>You can /', $this->letterContent, $matches);
        if ($matches) {
            return $matches[1];
        }
        preg_match('/was canceled because:<\/p><p>(.*)<\/p><p>You can /', $this->letterContent, $matches);
        if ($matches) {
            return $matches[1];
        }
        preg_match('/was denied because: <\/p><p>(.*)<\/p><p>You can /', $this->letterContent, $matches);
        if ($matches) {
            return $matches[1];
        }
        preg_match('/was denied because:<\/p><p>(.*)<\/p><p>You can /', $this->letterContent, $matches);
        if ($matches) {
            return $matches[1];
        }
        preg_match('/was denied because: <\/p><p>(.*)<\/p><p>Questions/', $this->letterContent, $matches);
        if ($matches) {
            return $matches[1];
        }
        preg_match('/was denied because:<\/p><p>(.*)<\/p><p>Questions/', $this->letterContent, $matches);
        return empty($matches) ? false : $matches[1];
    }

    protected function isNeedWaiting() {
        return (bool)substr_count($this->letterContent, 'Your request for device unlock requires additional research to determine eligibility.');
    }

    protected function getRequestNumber() {
        preg_match('/Unlock request number: <strong>([0-9]*)<\/strong>/', $this->letterContent, $matches);
        if (!$matches) {
            preg_match('/<p>Your unlock request number is: <strong>([0-9]*)<\/strong>/', $this->letterContent, $matches);
        }
        return $matches[1];
    }

    protected function getUnlockCode() {
        preg_match('/<p>Your unlock code is: ([0-9]*)<\/p>/', $this->letterContent, $matches);
        return $matches ? $matches[1] : null;
    }

    protected function isSuccessWithoutCode() {
        return (bool)substr_count($this->letterContent, 'Congratulations! Here are your unlock instructions:');
    }

    protected function getConfirmedLink() {
        preg_match('/https%3A%2F%2Fatt\.com%2Fdeviceunlock%2Fverifyemail\.html%3FrequestId%3D(.*)%26transactionId%3D([0-9a-f\-]*)&msgid=/', $this->letterContent, $matches);
        if ($matches) {
            return "https://att.com/deviceunlock/verifyemail.html?requestId={$matches[1]}&transactionId={$matches[2]}";
        }
        return null;
    }

    protected function getAdditionalInfo($letterType) {
        switch ($letterType) {
            case self::FAIL_UNLOCK:
                return $this->getErrorReason();
            case self::SUCCESS_UNLOCK_WITH_ID:
                return $this->getUnlockCode();
            case self::CONFIRMED_EMAIL:
                return $this->getConfirmedLink();
            default:
                return null;
        }
    }
}

//<font face="Arial"><br ><p>Unlock request number: <strong>2014258842</strong></p><p>Thank you for contacting AT&amp;T about unlocking your mobile device.</p><p>Unfortunately,
// your unlock request was denied because:</p><p>Your device is currently active on another AT&amp;T customer&#8217;s account.</p>
//<p>Questions? Please call 800.331.0500, or dial 611 from your AT&amp;T wireless phone.</p><br > Sincerely, </font><br ><font face="Arial">The AT&amp;T Customer Care Team </font><br ><br ><font color="#808080" face="Arial"><b>DO NOT REPLY TO THIS MESSAGE</b><br > All replies are automatically deleted.        </font>