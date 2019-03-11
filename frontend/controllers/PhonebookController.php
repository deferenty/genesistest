<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Phonebook controller
 *
 * @author petrovich
 */
class PhonebookController extends Controller
{
    /**
     * Name of model class
     * @var string
     */
    public $modelClass;

    /**
     * Phonebook index action
     * @return mixed
     */
    public function actionIndex()
    {
        $model = Yii::createObject($this->modelClass);
        
        $dataProvider = $model->search(Yii::$app->request->get());
        
        return $this->render('index', [
            'searchModel' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
}
