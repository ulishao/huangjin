<?php
/**
 * 操作类名称: PhpStorm.
 * 作者名称: e.p
 * 创建时间: 16/11/8 14:45
 */
namespace app\controllers;


use app\models\Content;
use Yii;

use yii\web\Response;
use yii\rest\ActiveController;


class BaseController extends ActiveController
{
    //初始化类库需要用到的变量,需要直接调用

    public function init()
    {
        parent::init();
    }
    public $modelClass = 'app\models\User';
//    public function actionIndex()
//    {
//       return ['code'=>200,'data'=> Content::find ()->all ()];
//    }

//    public function actionCreate()
//    {
//        //方法4：
//        //方法5：
//
//        $content = Yii::$app->request->post ('content');
//        Content::find ()->createCommand()->insert  ('content',[
//            'content'=>$content,
//            'ip'=>$this->getUserHostAddress(),
//            'create_time'=>time ()
//        ])->execute ();
//        return ['code'=>200,'mes'=>"SUCCESS",'data'=>$this->getUserHostAddress ()];
//    }
    /**
     * 此函数在apache和iis下通用，但速度比getUserHostAddressNoIIS慢
     * @return string user IP address
     */
    public function getUserHostAddress()
    {
        switch(true){
            case ($ip=getenv("HTTP_X_FORWARDED_FOR")):
                break;
            case ($ip=getenv("HTTP_CLIENT_IP")):
                break;
            default:
                $ip=getenv("REMOTE_ADDR")?getenv("REMOTE_ADDR"):'127.0.0.1';
        }
        if (strpos($ip, ', ')>0) {
            $ips = explode(', ', $ip);
            $ip = $ips[0];
        }
        return $ip;
    }

    /**
     * 初始化工具需要类
     *
     * @author e.p
     * @access public
     * @param \yii\base\Action $action
     * @since  1.0
     * @return array
     */
    public function beforeAction($action)
    {
        header('Access-Control-Allow-Headers:X-Requested-With');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');
        Yii::$app->response->format = Response::FORMAT_JSON;

        return true;

    }



    /**
     * 去除无用变量
     * @author e.p
     * @access public
     * @since  1.0
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);

        return $actions;
    }


}