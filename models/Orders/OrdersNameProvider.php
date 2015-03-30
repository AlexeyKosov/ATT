<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 21.03.15
 * Time: 10:22
 */

namespace app\models\Orders;

use app\models\Orders;

class OrdersNameProvider {

    protected static $assoc = array(
        Orders::WAITING_SEND => 'Ожидает отправки',
        Orders::SEND_REQUEST => 'Отправлен запрос',
        Orders::CONFIRMED_EMAIL => 'Подтвержден email',
        Orders::FAIL_CONFIRMED_EMAIL => 'Ошибка подтверждения email',
        Orders::SUCCESS_RESULT_RETRIEVE => 'Успешно отвязан',
        Orders::SENT_SUCCESS_DATA_TO_CLIENT => 'Информация по успешному заказу отпарвлена клиенту',
        Orders::FAIL_RESULT_RETRIEVE => 'Ошибка отвязки',
        Orders::SENT_FAIL_DATA_TO_CLIENT => 'Информация о провале отвязки отправлена клиенту',
    );

    public static function getNameByType($orderStage) {
        if (isset(self::$assoc[$orderStage])) {
            return self::$assoc[$orderStage];
        }
        return null;
    }
}