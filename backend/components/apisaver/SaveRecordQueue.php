<?php

namespace backend\components\apisaver;

use backend\components\apisaver\SaveRecordInterface;
use backend\components\apisaver\SaveRecordJobInterface;
use Yii;
use yii\db\ActiveRecordInterface;

/**
 * Description of SaveRecordQueue
 *
 * @author petrovich
 */
class SaveRecordQueue implements SaveRecordInterface
{
    /**
     * @var SaveRecordJobInterface 
     */
    private $job;
    
    /**
     * Constructor
     * @param SaveRecordJobInterface $job
     */
    public function __construct(SaveRecordJobInterface $job)
    {
        $this->job = $job;
    }

    /**
     * Push job to queue, so the model will be saved later (in queue)
     * @param ActiveRecordInterface $model
     */
    public function saveModel(ActiveRecordInterface $model)
    {
        $this->job->setModel($model);
        $delay = $this->job->getDelay();
        
        $this->job->beforeJobPushed();
        $queueId = Yii::$app->queue->delay($delay)->push($this->job);
    }
}
