<?php

namespace app\models;

use app\models\Letters\LetterTypeProvider;
use app\models\Orders\OrdersNameProvider;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property string $imei
 * @property string $make
 * @property string $phone_model
 * @property integer $stage
 * @property string $date_create
 * @property string $captchaId
 * @property string $captchaResult
 * @property string $request_number
 * @property string $additional_info
 */
class Orders extends ActiveRecord
{
    const WAITING_SEND = 0;
    const SEND_REQUEST = 1;
    const CONFIRMED_EMAIL = 2;
    const FAIL_CONFIRMED_EMAIL = 3;
    const SUCCESS_RESULT_RETRIEVE = 4;
    const SENT_SUCCESS_DATA_TO_CLIENT = 5;
    CONST FAIL_RESULT_RETRIEVE = -1;
    CONST SENT_FAIL_DATA_TO_CLIENT = -2;

    public $captchaId;
    public $captchaResult;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['request_number'], 'required'],
            [['stage'], 'integer'],
            [['date_create'], 'safe'],
            [['imei', 'captchaResult', 'captchaId', 'request_number'], 'string', 'max' => 15],
            [['make', 'phone_model'], 'string', 'max' => 40],
            [['additional_info'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'imei' => 'Imei',
            'make' => 'Make',
            'phone_model' => 'Phone Model',
            'stage' => 'Stage',
            'date_create' => 'Date Create',
            'request_number' => 'Request number',
            'additional_info' => 'Additional Info',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (!empty($this->imei)) {
                    $phoneModelProvider = new PhoneModelProvider();
                    $phoneModel = $phoneModelProvider->getPhoneModel($this->imei);
                    $this->make = $phoneModel->make;
                    $this->phone_model = $phoneModel->model;
                    $requestGenerator = new RequestGenerator();
                    $this->request_number = $requestGenerator->sendRequest($this);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function loadLetterWithType($letterType) {
        if (($letterType == LetterTypeProvider::CONFIRMED_EMAIL) &&
            ($this->stage < self::CONFIRMED_EMAIL)) {
            $this->stage = self::CONFIRMED_EMAIL;
        } elseif (($letterType == LetterTypeProvider::SUCCESS_UNLOCK_WITH_ID) ||
            ($letterType == LetterTypeProvider::SUCCESS_UNLOCK_WITHOUT_ID) &&
            ($this->stage < self::SUCCESS_RESULT_RETRIEVE)) {
            $this->stage = self::SUCCESS_RESULT_RETRIEVE;
        } elseif (($letterType == LetterTypeProvider::FAIL_UNLOCK) &&
            ($this->stage > self::FAIL_RESULT_RETRIEVE)) {
            $this->stage = self::FAIL_RESULT_RETRIEVE;
        }
    }

    public static function loadOrderByRequestId($requestId) {
        return Orders::findOne(array('request_number' => $requestId));
    }

    public function getLetterList() {
        return Letters::findAll(array('order' => $this->request_number));
    }

    public function getStageName() {
        $ordersNameProvider = new OrdersNameProvider();
        return $ordersNameProvider->getNameByType($this->stage);
    }
}
