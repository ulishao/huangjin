<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_message".
 *
 * @property string $id
 * @property string $user_id
 * @property string $admin_id
 * @property string $type
 * @property integer $status
 * @property string $open_id
 * @property string $message_type
 * @property string $message
 * @property integer $invalid_time
 * @property integer $create_time
 */
class UserMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_message';
    }

}
