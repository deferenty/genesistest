<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;

$model = new common\models\Phonebook;
$cacheKey = 'batchRecordSaver' . crc32(get_class($model));
$res = Yii::$app->cache->get($cacheKey);

echo '<pre>';
echo \yii\helpers\VarDumper::dump($res);
echo '</pre>';


//for($i = 0; $i < (1); $i++){
//    $phoneOne = strval(rand(1000000000, 9999999999));
//    $phoneOne = substr_replace($phoneOne, '-', 6, 0);
//    $phoneOne = substr_replace($phoneOne, ' ', 3, 0);
//
//    $phoneTwo = strval(rand(1000000000, 9999999999));
//    $phoneTwo = substr_replace($phoneTwo, '-', 6, 0);
//    $phoneTwo = substr_replace($phoneTwo, ' ', 3, 0);
//
//    $record = [
//        'firstName' => Yii::$app->security->generateRandomString(10),
//        'lastName' => Yii::$app->security->generateRandomString(10),
//        'phoneNumbers' => [
//            $phoneOne,
//            $phoneTwo
//        ]
//    ];
//    
//    $model = new common\models\Phonebook($record);
//    
//    $job = new backend\components\phonebook\SaveRecordJobBatch();
//    $job->setModel($model);
//    
//
//    Yii::$app->queue->push($job);
//}
    
echo '<pre>';
echo \yii\helpers\VarDumper::dump(Yii::$app->cache->get('batchRecordSaver'));
echo '</pre>';


?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>This is the About page. You may modify the following file to customize its content:</p>

    <code><?= __FILE__ ?></code>
</div>
