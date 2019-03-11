<?php

namespace backend\components\apisaver;

use backend\components\apisaver\SaveRecordJobInterface;

/**
 * Separately saves every single record
 *
 * @author petrovich
 */
class SaveRecordJobSingle implements SaveRecordJobInterface
{
    public $delay = 0;
    
    /**
     * @var yii\db\ActiveRecordInterface
     */
    protected $model;


    /**
     * Simply saves one record per one insert
     * @param Queue $queue
     */
    public function execute($queue)
    {
        $this->model->insert(false);
    }
    
    /**
     * Gets delay
     * @return int
     */
    public function getDelay(): int
    {
        return $this->delay;
    }
    
    /**
     * Do nothing, just to implement interface
     * @return null
     */
    public function beforeJobPushed()
    {
        return;
    }
    
    /**
     * Sets model
     * @param \yii\db\ActiveRecordInterface $model
     */
    public function setModel(\yii\db\ActiveRecordInterface $model)
    {
        $this->model = $model;
    }
}
