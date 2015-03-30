<?php
/**
 * Created by PhpStorm.
 * User: Vasiliy
 * Date: 21.03.15
 * Time: 13:20
 */

namespace app\commands;

use yii\console\Controller;
use app\models\Letters\LetterListProvider;

class ConsoleController extends Controller {
    public function actionIndex() {
        echo '11';
    }

    public function actionUpdate() {
        $letterListProvider = new LetterListProvider();
        $letterList = $letterListProvider->loadLetterList();
        foreach ($letterList as $letter) {
            $letter->save();
        }
    }
}