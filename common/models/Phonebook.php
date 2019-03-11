<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "phonebook".
 *
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 *
 * @property Phone[] $phones
 */
class Phonebook extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phonebook';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstName', 'lastName'], 'string', 'max' => 30],
            [['firstName'], 'bothNotEmpty', 'skipOnEmpty' => false, 'params' => ['other' => 'lastName']],
            [['phoneNumbers'], 'each', 'rule' => ['string', 'max' => 12]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
        ];
    }
    
    /**
     * Checks if both two attributes are not empty
     * @param string $attribute
     * @param array $params
     */
    public function bothNotEmpty($attribute, $params)
    {
        $label1 = $this->getAttributeLabel($attribute);
        $label2 = $this->getAttributeLabel($params['other']);
        if (empty($this->$attribute) && empty($this->{$params['other']})) {
            $this->addError($attribute, "'{$label1}' or '{$label2}' is required.");
        }
    }
}
