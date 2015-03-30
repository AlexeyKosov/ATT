<?php

namespace app\models;

use app\models\Letters\LettersNameProvider;
use app\models\Letters\LetterTypeProvider;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "letters".
 *
 * @property integer $id
 * @property string $content
 * @property integer $type
 * @property integer $order
 */
class Letters extends ActiveRecord
{

    public $additionalInfo;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'letters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'type', 'order'], 'required'],
            [['content'], 'string'],
            [['type', 'order'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'type' => 'Type',
            'order' => 'Order',
        ];
    }

    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->type == LetterTypeProvider::CONFIRMED_EMAIL) {
            $successConfirm = $this->confirmEmail();
            $this->additionalInfo = '';
        } else {
            $successConfirm = true;
        }
        /** @var Orders $order */
        $order = $this->getOrder();
        if (empty($order)) {
            $order = new Orders();
            $order->request_number = $this->order;
        }
        if ($successConfirm) {
            $order->loadLetterWithType($this->type);
        } else {
            $order->stage = Orders::FAIL_CONFIRMED_EMAIL;
        }
        $order->additional_info = $this->additionalInfo;
        $order->save();
        return true;
    }

    public function getName() {
        $letterNameProvider = new LettersNameProvider();
        return $letterNameProvider->getNameByType($this->type);
    }

    /**
     * @return null|Orders
     */
    public function getOrder() {
        return Orders::loadOrderByRequestId($this->order);
    }

    public function getOrderLink() {
        $order = $this->getOrder();
        if ($order) {
            return '/web/index.php?r=orders/view&id=' . $order->id;
        }
        return null;
    }

    protected function confirmEmail() {
        $confirmRequestProvider = new ConfirmRequestProvider();
        return $confirmRequestProvider->confirmRequest($this->additionalInfo);
    }
}
