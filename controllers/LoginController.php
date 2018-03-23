<?php

namespace app\controllers;


use app\models\User;
use app\models\UserRecord;
use app\models\UserRest;
use Yii;

class LoginController extends BaseController
{

    public $layout = false;
    public function actionCreate()
    {
        $username = Yii::$app->request->get ('username');
        $password = Yii::$app->request->get ('password');

        $mail="/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";//定义正则表达式
        if(preg_match($mail,$username)){                       //用正则表达式函数进行判断
            $userModel = new User();
            $model = $userModel::find ()->where (['username'=>$username ])->asArray ()->one();
            if(md5 ($model['password']) == md5 ($password))
            {
                return ['code'=>200,'message'=>'SUCCESS',['id'=>$model['id']]];
            }else{
                return ['code'=>500,'message'=>'FAIL'];
            }
        }else{
            return ['code'=>500,'message'=>'FAIL','data'=>'电子邮箱格式不正确'];
        }
    }
    public function actionEditUser()
    {

        $email = Yii::$app->request->post ('email');
        $model = User::find ()->select (['id','username','status'])->where (['username'=>$email])->one();
        $model->status = ($model->status==1)?0:1;
        if($model->save ()){
            return $this->re (200,'SUCCESS',['status'=>$model->status]);
        }ELSE{
            return ['code'=>500,'message'=>'FAIL'];
        }
    }
    public function actionUser()
    {

        $email = Yii::$app->request->get ('email');
        return $this->re ('200','SUCCESS',User::find ()->select (['id','username','status'])->where (['username'=>$email])->asArray ()->one());
    }
    public function actionAddUser()
    {
        $code = Yii::$app->request->post ('code');
        $id   = Yii::$app->request->post ('id');
        $password   = Yii::$app->request->post ('password');
        if(UserRecord::find ()->where (['code'=>$code,'user_id'=>$id])->exists ())
        {
           $userModel = User::find ()->where (['id'=>$id,'status'=>0])->one();
           if($userModel){
               $userModel->password = md5 ($password);
               $userModel->status = 1;
               if($userModel->save ()){
                   return $this->re (200,'success',['id'=>$userModel->id]);
               }else{
                   return $this->re ( 500, 'FAIL', $userModel->errors);
               }
           }else{
               return $this->re (500,'FAIL','错误id');
           }
        }else{
            return $this->re (500,'FAIL','错误code');
        }
    }

    public function sendIm($email,$title,$content)
    {

        $mail= Yii::$app->mailer->compose();
        $mail->setTo($email);
        $mail->setSubject($title);
        //$mail->setTextBody('zheshisha ');   //发布纯文字文本
        $mail->setHtmlBody($content);    //发布可以带html标签的文本
        if($mail->send())
            echo "success";
        else
            echo "failse";
    }
    /**
     * 注册
     */
    public function actionRegister()
    {
        $mail = Yii::$app->request->post ('username');
        if(!empty($mail)){
            $uuid = new \uuid();
            $check_mail="/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";//定义正则表达式
            if(preg_match($check_mail,$mail)){                       //用正则表达式函数进行判断
                $user_id = sha1 ($uuid->generateuuid ());
                if(Yii::$app->db->createCommand ()->insert ('user',[
                    'id'=>$user_id,
                    'username'=>$mail,
                    'status'=>0,
                    'create_time'=>time ()
                ])->execute ()){
                    $id = sha1 ($uuid->generateuuid ());
                    Yii::$app->db->createCommand ()->insert ('user_record',[
                        'id'=>$id,
                        'user_id'=>$user_id,
                        'code'=>$id,
                        'create_time'=>time ()
                    ])->execute ();
                    $url = 'http://www.tixing.xyz/apply/code?code='.$id.'&id='.$user_id;
                    $html = "欢迎注册功能提醒网站用户绑定:<a href='".$url."'>点击绑定</a><br><br>复制一下地址:".$url;

                    $this->sendIm ($mail,'用户注册绑定',$html);
                    return $this->re (200,'SUCCESS','发送成功');
                }
            }else{
                return $this->re (500,'FAIL','电子邮箱格式不正确');
            }
        }else{
            return $this->re (500,'FAIL','添加失败');
        }
    }

    public function re($code,$message,$data)
    {
        return ['code'=>$code,'message'=>$message,'data'=>$data];
    }

    public function actionRest()
    {
        $data = file_get_contents('https://ms.jr.jd.com/gw/generic/hj/h5/m/currentGoldDetail');
        $model = @json_decode($data,true);
        $price = $model['resultData']['data']['canBuyAmount'];
        $daRest = UserRest::find ()->orderBy  ('create_time desc')->one();
        $RECT = new UserRest();
        $RECT->price = $daRest['rest_price']-$price;
        if($RECT->price==0){
            return 111;
        }
        $RECT->rest_price = $price;
        $RECT->create_time = time();
        if($RECT->save ()){
            echo 1;die();
        }else{
            echo 2;die();
        }
    }
}
