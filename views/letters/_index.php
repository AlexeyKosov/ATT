<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Letters;

/* @var $this yii\web\View */
/* @var $modelList Letters[] */

?>
<div class="letters-index">
    <? foreach ($modelList as $key => $letter): ?>
        <?= $key + 1; ?>. <a href='/web/index.php?r=letters/view&id=<?= $letter->id ?>'><?= $letter->getName()?></a><br />
    <? endforeach; ?>

</div>
