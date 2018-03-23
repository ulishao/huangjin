<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_chat".
 *
 * @property string $id
 * @property string $admin_id
 * @property string $user_id
 * @property string $type
 * @property integer $invalid_time
 * @property integer $create_time
 */
class UserRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_record';
    }

}
