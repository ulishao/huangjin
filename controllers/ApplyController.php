<?php

namespace app\controllers;




use app\models\User;
use app\models\UserMessage;
use app\models\UserRecord;
use app\models\UserRest;
use yii\web\Controller;
use Yii;
use yii\data\Pagination;

class ApplyController extends Controller
{

    public $layout = false;
    public $enableCsrfValidation = false;
    public function actionSet(){

    }
    public function actionIndex()
    {
        if($model = Yii::$app->cache->get ('index')){
            return  $model;
        }else{
            $model = $this->render ('index');
            Yii::$app->cache->set ('index',$model);
            return $model;
        }

    }
    public function actionRegister()
    {
        if(Yii::$app->request->isPost){
            $email = Yii::$app->request->post ('email');

            if(!empty($email)){
                $uuid = new \uuid();
                $check_mail="/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";//定义正则表达式
                if(preg_match($check_mail,$email)){                       //用正则表达式函数进行判断
                    $user_id = sha1 ($uuid->generateuuid ());
                    if($model = User::find ()->where (['username'=>$email])->asArray ()->one()){
                        $user_id = $model['id'];
                    }else{
                        Yii::$app->db->createCommand ()->insert ('user',[
                            'id'=>$user_id,
                            'username'=>$email,
                            'status'=>0,
                            'create_time'=>time ()
                        ])->execute ();
                    }

                    if($user_id){
                        $id = sha1 ($uuid->generateuuid ());
                        Yii::$app->db->createCommand ()->insert ('user_record',[
                            'id'=>$id,
                            'user_id'=>$user_id,
                            'code'=>$id,
                            'create_time'=>time ()
                        ])->execute ();
                        $url = 'http://www.tixing.xyz/apply/code?code='.$id.'&id='.$user_id;
                        $html = "欢迎注册功能提醒网站用户绑定:<a href='".$url."'>点击绑定</a><br><br>复制一下地址:".$url;
                        $this->sendIm ($email,'用户注册绑定',$html);
                        $email = '邮件'.$email.'发送成功请去邮箱查收';
                        return $this->render ('re',['email'=>$email]);
                    }
                }else{
                    return $this->render ('re',['email'=>'电子邮箱格式不正确']);
                }
            }else{
                return $this->render ('re',['email'=>'注册邮箱不能为空']);
            }

        }else{
            return $this->render ('register');
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
            return true;
        else
           return false;
    }

    public function re($code,$message,$data)
    {
        return ['code'=>$code,'message'=>$message,'data'=>$data];
    }
    public function actionCreate()
    {
        $username = Yii::$app->request->post ('username');
        $password = Yii::$app->request->post ('password');

        if($model= User::find ()->where (['username'=>$username,'password'=>md5 ($password)])->asArray ()->one())
        {

            $cookies = Yii::$app->response->cookies;

            $cookies->add(new \yii\web\Cookie([
                'name' => 'id',
                'value' => $model['username'],
                'expire'=>time()+3600
            ]));
            return $this->redirect ('/apply/list');
        }else{

             return '登陆失败';

        }
    }
    public function actionView()
    {
        $cookies = Yii::$app->request->cookies;//注意此处是request
        $language = @$cookies->get('id')->value;
        if(empty($language)){
            return '登陆失败';
        }

        $query = UserMessage::find()->where (['user_id'=>$language]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy ('create_time desc')
            ->all();
        return $this->render('create', [
            'models' => $models,
            'pages' => $pages,
            'email'=>$language,
        ]);
    }

    public function actionList()
    {
        $cookies = Yii::$app->request->cookies;//注意此处是request
        $language = @$cookies->get('id')->value;
        if(empty($language)){
            return '登陆失败';
        }
        $html = file_get_contents ('https://ms.jr.jd.com/gw/generic/hj/h5/m/intervalGoldPrice?reqData=%7B%22type%22%3A%221%22%7D&sid=&source=jrm&_=1520214273680');
        $data= @json_decode ($html,true)['resultData']['data'];

//        $url = 'https://ms.jr.jd.com/gw/generic/hj/h5/m/intervalGoldPrice?reqData=%7B%22type%22%3A%224%22%7D&sid=&source=jrm&_=1521691531168';
//        $htmlD = file_get_contents ($url);
//        $dataNian = @json_decode ($htmlD,true)['resultData']['data'];


        $hj  = file_get_contents ('https://ms.jr.jd.com/gw/generic/hj/h5/m/currentGoldPrice');
        $jdData= json_decode ($hj,true);

        $hjprice = $jdData['resultData']['data']['currentPrice'];


        $price = [];
        $date = [];
        $minPrice = 0;
        $maxPrice = 0;
        foreach ((array)$data as $value)
        {
            $price[]=$value['goldPrice'];
            if($minPrice == 0){
                $minPrice = $value['goldPrice'];
            }else{
                if($minPrice>$value['goldPrice']){
                    $minPrice = $value['goldPrice'];
                }
            }
            if($maxPrice == 0){
                $maxPrice = $value['goldPrice'];

            }else{
                if($maxPrice<$value['goldPrice']){
                    $maxPrice = $value['goldPrice'];
                }
            }
            $date[]= date('H:i:s',$value['date']/1000);
        }
        $datas = [
            'price'=>json_encode ($price),
            'date'=>json_encode ($date)
        ];

//
//        //一年
//        $minPriceNian = 0;
//        $maxPriceNian = 0;
//        $dateNian = [];
//        $priceNian = [];
//        foreach ((array)$dataNian as $value)
//        {
//            $priceNian[]=$value['goldPrice'];
//            if($minPriceNian == 0){
//                $minPriceNian = $value['goldPrice'];
//            }else{
//                if($minPriceNian>$value['goldPrice']){
//                    $minPriceNian = $value['goldPrice'];
//                }
//            }
//            if($maxPriceNian == 0){
//                $maxPriceNian = $value['goldPrice'];
//            }else{
//                if($maxPriceNian<$value['goldPrice']){
//                    $maxPriceNian = $value['goldPrice'];
//                }
//            }
//            $dateNian[]= date('Y-m-d',$value['date']/1000);
//        }
//        $datasNian = [
//            'price'=>json_encode ($priceNian),
//            'date'=>json_encode ($dateNian)
//        ];
        //买入
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $Model = UserRest::find ()->select (['sum(price) as price','rest_price'])->where (['>','create_time',$beginToday])
            ->andWhere (['<','create_time',$endToday])->asArray ()->one ();
        $maxDPrice = UserRest::find ()->where (['>','create_time',$beginToday])
            ->andWhere (['<','create_time',$endToday])->andWhere (['>','price','0'])->asArray ()->count ();
        $minDPrice = UserRest::find ()->where (['>','create_time',$beginToday])
            ->andWhere (['<','create_time',$endToday])->andWhere (['<','price','0'])->asArray ()->count ();
        $rest_price = $Model['price'];
        $rest = $Model['rest_price'];

        return $this->render ('list',[
            'data'=>$datas,
//            'dataNian'=>$datasNian,
            'min'=>$minPrice,
//            'minNian'=>$minPriceNian,
            'max'=>$maxPrice,
//            'maxNian'=>$maxPriceNian,
            'email'=>$language,
            'price'=>$hjprice,
            'rest_price'=>$rest_price,
            'rest'=>$rest,
            'minprice'=>$minDPrice,
            'maxprice'=>$maxDPrice
        ]);
    }
    public function actionCode(){


        $code = Yii::$app->request->get ('code');
        $id   = Yii::$app->request->get ('id');
        if(Yii::$app->request->isPost){
            $password   = Yii::$app->request->post ('password');
            if($model = UserRecord::find ()->where (['code'=>$code,'user_id'=>$id])->one ())
            {
                $userModel = User::find ()->where (['id'=>$id])->one();
                if($userModel){
                    $userModel->password = md5 ($password);
                    $userModel->status = 1;
                    if($userModel->save ()){
                        $model->delete ();
                        return $this->render ('re',['email'=>'密码设置成功']);
                    }else{
                        return $this->render ('re',['email'=>'内部错误']);
                    }
                }else{
                    return $this->render ('re',['email'=>'已过期']);
                }
            }else{
                return $this->render ('re',['email'=>'内部错误']);
            }
        }else{
            return $this->render ('code',['code'=>$code,'id'=>$id]);
        }

    }
}
