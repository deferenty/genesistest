<?php

namespace backend\components\apisaver;

use yii\queue\JobInterface;
use yii\db\ActiveRecordInterface;

/**
 * SaveRecordJob Interface
 * 
 * @author petrovich
 */
interface SaveRecordJobInterface extends JobInterface
{
    /**
     * @param ActiveRecordInterface $model
     */
    public function setModel(ActiveRecordInterface $model);
    
    public function getDelay(): int;
    
    public function beforeJobPushed();
}
