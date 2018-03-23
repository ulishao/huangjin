<?php

namespace app\controllers;

use app\models\JrJd;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class Site1Controller extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    public $layout =false;
    public function actionIndex()
    {
        $data = [
            '00:00',
            '01:15',
            '02:30',
            '03:45',
            '05:00',
            '06:15',
            '07:30',
            '08:45',
            '10:00',
            '11:15',
            '12:30',
            '13:45',
            '15:00',
            '16:15',
            '17:30',
            '18:45',
            '20:00',
            '21:15',
            '22:30',
            '23:45'
        ];
        $countTime = [];
        $countTime1 = [];
        $create = time();
        foreach ($data as $vs=>$v)
        {
            $time = strtotime(date ('Ymd').' '.$v.':00');
            $end_time = $time+3600+900;
            $count = JrJd::find ()->where (['>','trade_time',$time])->andWhere (['<','trade_time',$end_time])->andWhere (['status'=>'0'])->count ();
            $count1 = JrJd::find ()->where (['>','trade_time',$time])->andWhere (['<','trade_time',$end_time])->andWhere (['status'=>'1'])->count ();
            $countTime[] = $count;
            $countTime1[] = $count1;


        }


        //

        $time = strtotime(date ('Ymd').'00:00:00');;
        $end_time = strtotime(date ('Ymd').'23:59:59');;
        $user = JrJd::find ()->select ([
            'id',
            'lv',
            'username',
            'create_time',
            'code',
            'name',
            'count(username) as count',
            'trade_date'
        ])->where (['>','trade_time',$time])
            ->andWhere (['<','trade_time',$end_time])
            ->groupBy ('username')
            ->andWhere (['status'=>'0'])
            ->orderBy ('count desc')
            ->addOrderBy ('floor(lv) desc')
          ->asArray ()->limit (10)->all();
//        $userMAI = JrJd::find ()->select ([
//            'id',
//            'username',
//            'count(username) as count',
//            'trade_date'
//        ])->where (['>','trade_time',$time])->andWhere (['<','trade_time',$end_time])->groupBy ('username')->andWhere (['status'=>'1'])->orderBy ('count desc')->asArray ()->limit (10)->all();
//
//        $userMData = [];
//        $userMD = [];
//        foreach ($userMAI as $v)
//        {
//            $userMData[]=$v['username'];
//            $userMD[]=$v['count'];
//        }
        $userData = [];
        $userD = [];

        foreach ($user as $v)
        {
            $Url = 'https://gupiao.jd.com/stock/summary.html?code='.$v['code'];

            $userData[]=$v['username'].'- 收益'.$v['lv'].'%-'.$v['name'];
            $userD[]=$v['count'];
        }
        $v = JrJd::find ()->orderBy ('create_time desc')->asArray ()->one();
        $create = date ('Ymd H:i:s',$v['create_time']);

        $userData = array_reverse($userData);
        $userD = array_reverse($userD);
        $countTime = $this->getjson ($countTime);
        $countTime1 = $this->getjson ($countTime1);
        $user = $this->getjson ($userData);
        $userD = $this->getjson ($userD);
//        $userM = $this->getjson ($userMData);
//        $userMD = $this->getjson ($userMD);
        $data = $this->getCode ();
        $data['name'] = $this->getjson ($data['name']);
        $data['count'] = $this->getjson ($data['count']);

        return $this->render ('index',['count'=>$countTime,'count1'=>$countTime1,
            'user'=>$user,
            'user_data'=>$userD,
            'create'=>$create,
//            'user_mdata'=>$userMD,
            'data'=>$data
        ]);
    }

    function getjson($countTime1)
    {
        $json ='';
        foreach ($countTime1 as $value) {
            $json .= json_encode($value) . ',';
        }
        return '[' . substr($json,0,strlen($json) - 1) . ']';
    }

    function getCode()
    {
        $time = strtotime(date ('Ymd').'00:00:00');;
        $end_time = strtotime(date ('Ymd').'23:59:59');;
        $data = JrJd::find ()->select ([
            'id',
            'username',
            'code',
            'name',
            'price',
            'lv',
            'count(code) as count',
            'trade_date'
        ])->where (['>','trade_time',$time])
            ->andWhere (['<','trade_time',$end_time])
            ->groupBy ('code')->andWhere (['status'=>'0'])
            ->orderBy ('count desc')
            ->addorderBy ('floor(lv) desc')
            ->asArray ()
            ->limit (10)->all();
        $datas = [];
        foreach ($data as $v=>$s)
        {
            $datas['name'][] = $s['name'].':'.$s['code'].'--'.$s['price'];
            $datas['count'][] = $s['count'];
        }
        return $datas;
    }

    function createCode($user_id)
    {
        static $source_string = '0123456789';
        $num = $user_id;
        $code = '';
        while($num)
        {
            $mod = $num % 10;
            $num = ($num - $mod) / 10;
            $code = $source_string[$mod].$code;
        }
        return $code;
    }
    /**
     * 生成vip激活码
     * @param int $nums             生成多少个优惠码
     * @param array $exist_array     排除指定数组中的优惠码
     * @param int $code_length         生成优惠码的长度
     * @param int $prefix              生成指定前缀
     * @return array                 返回优惠码数组
     */
    public function generateCode( $nums,$exist_array='',$code_length = 8,$prefix = '' ) {

        $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz";
        $promotion_codes = array();//这个数组用来接收生成的优惠码

        for($j = 0 ; $j < $nums; $j++) {

            $code = '';

            for ($i = 0; $i < $code_length; $i++) {

                $code .= $characters[mt_rand(0, strlen($characters)-1)];

            }

            //如果生成的4位随机数不再我们定义的$promotion_codes数组里面
            if( !in_array($code,$promotion_codes) ) {

                if( is_array($exist_array) ) {

                    if( !in_array($code,$exist_array) ) {//排除已经使用的优惠码

                        $promotion_codes[$j] = $prefix.$code; //将生成的新优惠码赋值给promotion_codes数组

                    } else {

                        $j--;

                    }

                } else {

                    $promotion_codes[$j] = $prefix.$code;//将优惠码赋值给数组

                }

            } else {
                $j--;
            }
        }

        return $promotion_codes;
    }
//    public function actionIndex()
//    {
//        { ["id"]=> string(36) "4665C846-08ED-A71F-C67D-9C884B95461D" ["title"]=> string(6) "衬衫" ["catelog_id"]=> string(36) "01E3FCD6-BFA2-059C-F67D-F54E851E2412" ["first_image"]=> string(87) "https://design007.oss-cn-qingdao.aliyuncs.com/A365C1B9-F94F-9C41-674D-A6E05D799E35.jpeg" ["images"]=> string(283) "{"0":"https://design007.oss-cn-qingdao.aliyuncs.com/A365C1B9-F94F-9C41-674D-A6E05D799E35.jpeg","1":"https://design007.oss-cn-qingdao.aliyuncs.com/2B31871C-67DB-BAD3-81F2-143D73A45D07.jpeg","2":"https://design007.oss-cn-qingdao.aliyuncs.com/F8747026-7E74-2BE5-A330-B8F8FD15CACE.jpeg"}" ["sales"]=> int(4) ["gross_total"]=> int(1302) ["weight"]=> string(3) "120" ["coding"]=> string(5) "99999" ["numbers"]=> string(5) "88888" ["bar_code"]=> string(7) "0000000" ["notice"]=> int(0) ["share_image"]=> string(87) "https://design007.oss-cn-qingdao.aliyuncs.com/AC9D8C61-654F-940C-7371-B57C53114D36.jpeg" ["share_title"]=> string(12) "靓女服饰" ["share_desc"]=> string(6) "漂亮" ["freight_template_id"]=> string(36) "394AE39D-7A38-E655-0074-BE8E07904962" ["label_group_sort"]=> string(145) "[{"id":"B75054EE-7CF5-8E77-5D56-CC7376DB2EDC","name":"颜色","type":""},{"id":"F8384E62-245A-536B-044D-40851C48EE7B","name":"尺码","type":""}]" ["label_ids"]=> string(322) "{"B75054EE-7CF5-8E77-5D56-CC7376DB2EDC":[{"id":"010D5CEC-E1A4-1B53-C38D-6B93A4C93710","name":"红色"},{"id":"469BE8ED-3C45-CF05-6D04-A9FFF734372F","name":"蓝色"}],"F8384E62-245A-536B-044D-40851C48EE7B":[{"id":"78B93B4D-FAE4-CA9E-8CC6-A45A2EE8F0E8","name":"xl"},{"id":"2BF3A496-36CE-0DF5-71F2-6E2A3D1DAD60","name":"l"}]}" ["sku_image"]=> NULL ["price"]=> string(13) "130.00,130.00" ["sku_price"]=> string(785) "{"4665C846-08ED-A71F-C67D-9C884B95461D_B75054EE-7CF5-8E77-5D56-CC7376DB2EDC_010D5CEC-E1A4-1B53-C38D-6B93A4C93710_F8384E62-245A-536B-044D-40851C48EE7B_78B93B4D-FAE4-CA9E-8CC6-A45A2EE8F0E8":"130.00","4665C846-08ED-A71F-C67D-9C884B95461D_B75054EE-7CF5-8E77-5D56-CC7376DB2EDC_010D5CEC-E1A4-1B53-C38D-6B93A4C93710_F8384E62-245A-536B-044D-40851C48EE7B_2BF3A496-36CE-0DF5-71F2-6E2A3D1DAD60":"130.00","4665C846-08ED-A71F-C67D-9C884B95461D_B75054EE-7CF5-8E77-5D56-CC7376DB2EDC_469BE8ED-3C45-CF05-6D04-A9FFF734372F_F8384E62-245A-536B-044D-40851C48EE7B_78B93B4D-FAE4-CA9E-8CC6-A45A2EE8F0E8":"130.00","4665C846-08ED-A71F-C67D-9C884B95461D_B75054EE-7CF5-8E77-5D56-CC7376DB2EDC_469BE8ED-3C45-CF05-6D04-A9FFF734372F_F8384E62-245A-536B-044D-40851C48EE7B_2BF3A496-36CE-0DF5-71F2-6E2A3D1DAD60":"130.00"}" ["label_image"]=> string(259) "{"010D5CEC-E1A4-1B53-C38D-6B93A4C93710":"https://design007.oss-cn-qingdao.aliyuncs.com/10563B48-CD61-FB8D-D3F4-BD151A4AC94A.jpeg","469BE8ED-3C45-CF05-6D04-A9FFF734372F":"https://design007.oss-cn-qingdao.aliyuncs.com/32358D32-DDEB-F052-973B-6D89B8F49450.jpeg"}" ["status"]=> string(1) "1" ["desc"]=> string(93) "https://design007.oss-cn-qingdao.aliyuncs.com/goods/4665C846-08ED-A71F-C67D-9C884B95461D.html" ["enable_time"]=> int(1506665457) ["create_time"]=> int(1506665457) }
//
//            $data = [
//                [
//                    "id"=>"DRESS",
//                    "name"=>"衣长"
//                ],[
//                "id"=>"SLEEVE",
//                "name"=>"袖长"
//            ],[
//                "id"=>"COLLAR",
//                "name"=>"领型"
//            ],[
//                "id"=>"SLEEVETYPE",
//                "name"=>"袖型"
//            ],[
//                "id"=>"SKIRT",
//                "name"=>"裙长"
//            ],[
//                "id"=>"SKIRTTYPE",
//                "name"=>"裙型"
//            ],[
//                "id"=>"WAISTTYPE",
//                "name"=>"腰型"
//            ],[
//                "id"=>"PANTS",
//                "name"=>"裤长"
//            ],[
//                "id"=>"PANTSTYPE",
//                "name"=>"裤型"
//            ],[
//                "id"=>"STYLE",
//                "name"=>"款式"
//            ],[
//                "id"=>"TROUSERS",
//                "name"=>"裤门襟"
//            ],[
//                "id"=>"CLOTHINGPLACKET",
//                "name"=>"衣门禁"
//            ],[
//                "id"=>"WEARINGSTYLE",
//                "name"=>"穿着方式"
//            ],[
//                "id"=>"COMBINATION",
//                "name"=>"组合形式"
//            ],
//            ];
//        $aa = [
//            "衣长",
//            "袖长",
//            "领型",
////            "袖型",
////            "衣门禁",
//            "裙长",
//            "裙型",
////            "腰型",
////            "裤长",
//            "裤型",
//            "裤门襟",
//            "穿着方式",
////            "组合形式",
////            "衣门禁",
////            "款式",
//        ];
////        [
////            "//gju4.alicdn.com/bao/uploaded/i3/0/TB2Hb4Aa2BNTKJjy0FdXXcPpVXa_!!0-juitemmedia.jpg_560x560Q90.jpg",
////            "//gju3.alicdn.com/bao/uploaded/i4/228784630/TB27uEVfEFWMKJjSZFvXXaenFXa_!!228784630.jpg_560x560Q90.jpg",
////            "//gju3.alicdn.com/bao/uploaded/i2/228784630/TB2APwQXqigSKJjSsppXXabnpXa_!!228784630.jpg_560x560Q90.jpg",
////            "//gju4.alicdn.com/bao/uploaded/i1/228784630/TB2kddPgo3IL1JjSZFMXXajrFXa_!!228784630.jpg_560x560Q90.jpg",
////            "//gju1.alicdn.com/bao/uploaded/i3/228784630/TB2yfzebJqUQKJjSZFIXXcOkFXa_!!228784630.jpg_560x560Q90.jpg"
////        ]
////        {
////            "027CA420-E85D-32BC-4AEC-BB7ECAA97E71": "//gju3.alicdn.com/bao/uploaded/i4/228784630/TB27uEVfEFWMKJjSZFvXXaenFXa_!!228784630.jpg_560x560Q90.jpg",
////    "DD782C7-7740-EF37-6AFD-398FD6A7ACAE": "//gju3.alicdn.com/bao/uploaded/i2/228784630/TB2APwQXqigSKJjSsppXXabnpXa_!!228784630.jpg_560x560Q90.jpg"
////}
//        $d = [];
//        foreach ($data as $v=>$s)
//        {
//
//            if(in_array ($s['name'],$aa)){
//                $d[] = $s;
//            }
//        }
//       echo json_encode ($d);
//        die();
//    }
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

//    /**
//     * Displays homepage.
//     *
//     * @return string
//     */
//    public function actionIndex()
//    {
//        return $this->render('index');
//    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
