<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 21.03.15
 * Time: 10:22
 */

namespace app\models\Letters;


class LettersNameProvider {

    protected static $assoc = array(
        LetterTypeProvider::CONFIRMED_EMAIL => 'Подтверждение адреса электронной почты',
        LetterTypeProvider::NEED_WAITING => 'Уведомление о необходимости подождать',
        LetterTypeProvider::SUCCESS_UNLOCK_WITH_ID => 'Разблокировка с ID',
        LetterTypeProvider::SUCCESS_UNLOCK_WITHOUT_ID => 'Разблокировка без ID',
        LetterTypeProvider::FAIL_UNLOCK => 'Отказ в разблокировке',
        LetterTypeProvider::UNDEFINED => 'Не известно'
    );

    public static function getNameByType($letterType) {
        if (isset(self::$assoc[$letterType])) {
            return self::$assoc[$letterType];
        }
        return null;
    }
}