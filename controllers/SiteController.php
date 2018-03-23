<?php

namespace app\controllers;


use app\models\Jijin;
use app\models\Video;
use app\models\Channel;

use yii\web\Controller;


class SiteController extends Controller
{
//    public $
    public function actionIndexs()
    {
        $id = \Yii::$app->request->get ('id','11022');
        return $this->render ('indexs',['id'=>$id]);
    }
    public $layout = false;
    public function actionIndex()
    {
        $html = file_get_contents ('https://ms.jr.jd.com/gw/generic/hj/h5/m/intervalGoldPrice?reqData=%7B%22type%22%3A%221%22%7D&sid=&source=jrm&_=1520214273680');
        $data= @json_decode ($html,true)['resultData']['data'];
        $price = [];
        $date = [];
        foreach ((array)$data as $value)
        {
            $price[]=$value['goldPrice'];
            $date[]= date('H:i:s',$value['date']/1000);
        }
        $datas = [
            'price'=>json_encode ($price),
            'date'=>json_encode ($date)
        ];
        return $this->render ('index',['data'=>$datas]);
    }
    public function actionCreate()
    {
        $mail = \Yii::$app->request->post ('mail');
        if(!empty($mail)){
            $checkmail="/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";//定义正则表达式
            if(preg_match($checkmail,$mail)){                       //用正则表达式函数进行判断
                if(\Yii::$app->db->createCommand ()->insert ('user',[
                    'mail'=>$mail,
                    'create_time'=>time ()
                ])->execute ()){
                    return "添加成功";
                }
            }else{
                return "电子邮箱格式不正确";
            }
        }else{
            return "添加失败";
        }



    }

    //参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
    protected  function curl_request($url,$post='',$cookie='', $returnCookie=0){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, $url);
        if($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }
        if($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        }else{
            return $data;
        }
    }
}
