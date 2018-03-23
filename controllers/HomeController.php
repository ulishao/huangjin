<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\web\Controller;


class HomeController extends Controller
{
    public $layout = false;
    /**
     *
     * @author  lishaowei
     * @access  public actionIndex
     */
    public function actionIndex()
    {
        return $this->render ('index');
    }
    public function actionDe()
    {
        $date = Yii::$app->request->get ('date');
       $a= Yii::$app->cache->delete ($date);
       var_dump ($a);die();
    }
    public function actionAcit()
    {
        $email = [];
        $model = User::find ()->where (['status'=>'1'])->groupBy ('username')->asArray ()->all();
        foreach ($model as $value){
            $email[] = $value['username'];
        }
        $html  = file_get_contents ('https://ms.jr.jd.com/gw/generic/hj/h5/m/currentGoldPrice');
        $data = json_decode ($html,true);
        if($data['resultCode']==0){
            $price = $data['resultData']['data']['currentPrice'];
            $time = $data['resultData']['data']['currentDate']/1000;
            $date = date('Ymd',$time);
            $type = 0;
            if((date('w') == 6) || (date('w') == 0)){
                return '今天是周末';
            }
            if(Yii::$app->cache->get ($date.intval($price))==false){
//                if($price>=270){
//                    Yii::$app->cache->set ($date.intval($price),$price);
//                    $this->sendIm ($date.'黄金价格提醒'.$price,$html);
//                }

                var_dump ($price);

                if((int)$price<=268){
                    $type = 1;
                    foreach ($email as $key=>$value){
                        $model = Yii::$app->db->createCommand ()->insert ('user_message',[
                            'user_id'=>$value,
                            'price'=>$price,
                            'type'=>1,
                            'create_time'=>time ()
                        ])->execute ();
                    }

                    Yii::$app->cache->set ($date.intval($price),$price);
                    $this->sendIm ($email,$date.'黄金价格提醒'.$price,$html);
                }
                if((int)$price>=274){
                    $type = 1;
                    foreach ($email as $key=>$value){
                        Yii::$app->db->createCommand ()->insert ('user_message',[
                            'user_id'=>$value,
                            'price'=>$price,
                            'type'=>1,
                            'create_time'=>time ()
                        ])->execute ();
                    }
                    Yii::$app->cache->set ($date.intval($price),$price);
                    $this->sendIm ($email,$date.'黄金价格提醒'.$price,$html);
                }
                if($type==0){
                    foreach ($email as $key=>$value){
                        Yii::$app->db->createCommand ()->insert ('user_message',[
                            'user_id'=>$value,
                            'price'=>$price,
                            'type'=>0,
                            'create_time'=>time ()
                        ])->execute ();
                    }
                }

                echo '以缓冲';die();
            }else{
                foreach ($email as $key=>$value){
                    Yii::$app->db->createCommand ()->insert ('user_message',[
                        'user_id'=>$value,
                        'price'=>$price,
                        'type'=>$type,
                        'create_time'=>time ()
                    ])->execute ();
                }
                echo '1已发送';die();
            }

        }else{
            echo '错误接口';die();

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
        die();
    }
}
