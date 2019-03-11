<?php

namespace backend\components\apisaver;

use backend\components\apisaver\SaveRecordJobInterface;
use Yii;

/**
 * Job to aggregate models in cache to be inserted to the database in 
 * one query
 *
 * @author petrovich
 */
class SaveRecordJobBatch implements SaveRecordJobInterface
{
    /**
     * @inheritDoc
     */
    public $cacheKeyPrefix = 'batchRecordSaver';
    
    /**
     * @inheritDoc
     */
    public $delay = 20;
    
    /**
     * Number of records that will be inserted in one query
     * @var int
     */
    public $batchSize = 20;
    
    /**
     * @var yii\db\ActiveRecordInterface
     */
    protected $model;
    
    /**
     * Gets delay
     * @return int
     */
    public function getDelay(): int
    {
        return $this->delay;
    }
    
    /**
     * Sets model
     * @param \yii\db\ActiveRecordInterface $model
     */
    public function setModel(\yii\db\ActiveRecordInterface $model)
    {
        $this->model = $model;
    }
    
    /**
     * Saves to the cache timestamp before pushing job. If records are saved
     * in batch and with delay, it will help us to save all records
     */
    public function beforeJobPushed()
    {
        $cache = Yii::$app->cache;
        $cacheKey = $this->getCacheKey();
        $savedModels = $cache->get($cacheKey);
        $savedModels['lastPushedJobTime'] = time();
        $cache->set($cacheKey, $savedModels);
    }
    
    /**
     * Saves to the cache data that must be saved to database.
     * Data is saved in one of two cases
     * First - number of models reaches batch size. In this case number of
     * saved records is equal to batch size.
     * Second - No new models were pushed during delay. In this case all records
     * from cache are saved to database.
     * @param Queue $queue
     */
    public function execute($queue) {
        $cache = Yii::$app->cache;
        $cacheKey = $this->getCacheKey();
        $storedModels = $cache->get($cacheKey);
        
        if (empty($storedModels)) {
            $storedModels['models'] = [];
        }
        
        $storedModels['models'][] = array_values($this->model->attributes);
        
        if (
            sizeof($storedModels['models']) >= $this->batchSize
            || $storedModels['lastPushedJobTime'] <= (time() - $this->getDelay())
        ) {
            $this->doBatchInsert(
                $this->model->tableName(),
                $this->model->attributes(),
                $storedModels['models']
            );
            
            $storedModels['models'] = [];
        }
        
        $cache->set($cacheKey, $storedModels);
    }
    
    /**
     * Saves all models in single query
     * @param string $tableName
     * @param array $columns
     * @param array $data
     */
    private function doBatchInsert(string $tableName, array $columns, array $data)
    {
        $connection = Yii::$app->db;
        $rows = $connection
            ->createCommand()
            ->batchInsert($tableName, $columns, $data)
            ->execute();
    }

    /**
     * Get cache key
     * @return string
     */
    private function getCacheKey(): string
    {
        $modelKey = crc32(get_class($this->model));
        
        return $this->cacheKeyPrefix . $modelKey;
    }
}
