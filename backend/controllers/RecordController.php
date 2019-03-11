<?php

namespace backend\controllers;

use backend\components\apisaver\SaveRecordInterface;
use Yii;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * Description of PersonController
 *
 * @author petrovich
 */
class RecordController extends Controller
{
    /**
     * ActiveRecord class name for saving data
     * @var string
     */
    public $modelClass;
    
    /**
     * Class for saving record
     * @var SaveRecordInterface 
     */
    protected $apiSaver;

    /**
     * Constructor
     * @param string $id
     * @param Module $module
     * @param SaveRecordInterface $apiSaver
     * @param array $config
     */
    public function __construct($id, $module, SaveRecordInterface $apiSaver, $config = []) {
        $this->apiSaver = $apiSaver;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post']
                ],
            ],
        ];
    }

    /**
     * Create record action
     * @return mixed
     */
    public function actionCreate()
    {
        $model = Yii::createObject($this->modelClass);
        $params = Yii::$app->request->getBodyParams();
        $model->setAttributes($params);
        
        if ($model->validate()) {
            $this->apiSaver->saveModel($model);
            
            return ['success' => true];
        } else {
            return $model->errors;
        }
    }
}
