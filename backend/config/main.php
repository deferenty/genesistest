<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
    'params' => $params,
    'container' => [
        'definitions' => [
            'backend\controllers\RecordController' => [
                'modelClass' => 'common\models\Phonebook'
            ],
            'backend\components\apisaver\SaveRecordInterface' => [
                'class' => 'backend\components\apisaver\SaveRecordQueue',
            ],
            /*Select one of two implementations of SaveRecordJobInterface*/
            'backend\components\apisaver\SaveRecordJobInterface' => [
                /*
                First implementation
                save every record separately
                */
//                'class' => 'backend\components\apisaver\SaveRecordJobSingle',
                /*
                Second one
                save records in batch query
                 */
                'class' => 'backend\components\apisaver\SaveRecordJobBatch',
                'batchSize' => 10,
                'delay' => 10,
            ] ,
        ]
    ]
];
