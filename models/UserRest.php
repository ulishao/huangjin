<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_rest".
 *
 * @property integer $id
 * @property string $price
 * @property string $rest_price
 * @property string $date
 * @property integer $create_time
 */
class UserRest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_rest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price', 'rest_price'], 'number'],
            [['date'], 'safe'],
            [['create_time'], 'required'],
            [['create_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price' => 'Price',
            'rest_price' => 'Rest Price',
            'date' => 'Date',
            'create_time' => 'Create Time',
        ];
    }
}
