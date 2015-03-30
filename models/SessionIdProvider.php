<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 17.03.15
 * Time: 21:17
 */

namespace app\models;


class SessionIdProvider {
    /** @var string */
    protected static $sessionId = '1v1lichadkkfewit26mbsh3h8';

    public static function getSessionId() {
        if (empty(self::$sessionId)) {
            self::_loadSessionId();
        }
        return self::$sessionId;
    }

    protected static function _loadSessionId() {
        $fileContent = self::_loadFile();
        return self::_extractSessionId($fileContent);
    }

    protected static function _loadFile() {
        return file(__DIR__ . '/cookie.txt');
    }

    protected static function _extractSessionId(array $data) {
        foreach ($data as $row) {
            if (substr_count($row, 'JSESSIONID')) {
                return self::_extractDataFromRow($row);
            }
        }
        return null;
    }

    protected static function _extractDataFromRow($row) {
        $partList = explode("	", $row);
        return $partList[6];
    }
}