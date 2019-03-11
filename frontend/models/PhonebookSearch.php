<?php

namespace frontend\models;

use common\models\Phonebook;
use yii\data\ActiveDataProvider;

/**
 * Description of PersonSearch
 *
 * @author petrovich
 */
class PhonebookSearch extends Phonebook
{
    /**
     * @inheritDoc
     */
    public function rules() {
        return [
            ['firstName', 'string', 'max' => 24]
        ];
    }
    
    /**
     * Prepares data provider, accordingly to search parameters
     * @param mixed $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Phonebook::find();
        
        $this->load($params);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        
        if (!$this->validate()){
            $query->where('0=1');
            return $dataProvider;
        }
        
        $query->filterWhere(['like', 'firstName', $this->firstName]);
        
        return $dataProvider;
    }
}
