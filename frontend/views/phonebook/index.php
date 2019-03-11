<?php

use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\PhonebookSearch */

$this->title = 'Phonebook';

?>

<div>
    <h1><?= $this->title ?></h1>
    <?php Pjax::begin() ?>
    <div class="form-group">
        <?php $form = ActiveForm::begin([
            'method' => 'GET',
            'layout' => 'inline',
            'action' => ['/phonebook/index'],
            'options' => [
                'data-pjax' => 1
            ]
        ]); ?>
        <?= $form->field($searchModel, 'firstName')
            ->textInput([
                'placeholder' => 'Search by First Name',
                'maxlength' => true
            ]) ?>
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset Filters', ['/phonebook/index'], ['class' => 'btn btn-default']) ?>
        <?php $form::end(); ?>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Search results</div>
        <div class="panel-body"><?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{summary}\n{items}\n<div align='center'>{pager}</div>",
            'columns' => [
                'firstName',
                'lastName',
                [
                    'attribute' => 'phoneNumbers',
                    'value' => function ($model){
                        return Html::encode(implode(', ', $model->phoneNumbers));
                    }
                ]
            ]
        ]) ?></div>
    </div>
    <?php Pjax::end() ?>
</div>