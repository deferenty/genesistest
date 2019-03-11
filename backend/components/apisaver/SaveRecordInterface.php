<?php

namespace backend\components\apisaver;

use yii\db\ActiveRecordInterface;

/**
 * SaveRecordInterface
 *
 * @author petrovich
 */
interface SaveRecordInterface
{
    /**
     * Save Record
     * @param \yii\db\ActiveRecordInterface $model
     */
    public function saveModel(ActiveRecordInterface $model);
}
