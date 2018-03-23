<?php
use yii\widgets\LinkPager;
/**
 * Created by PhpStorm.
 * User: lishaowei
 * Date: 2018/3/17
 * Time: 11:53
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>用户信息页面</title>
</head>
<body>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            你好:<?php echo $email?> uid(<?php echo md5($email);?>)        当前状态:<font id="status" style="color: #8c8c8c"></font>

            <br><code>一分钟后加入队列显示运行信息</code>
            <br>接口地址来源:https://ms.jr.jd.com/gw/generic/hj/h5/m/currentGoldPrice
        </div>
 <div class="panel-body">
            <table class="table">

                <thead>
                <tr>
                    <th>id</th>
                    <th>用户uid</th>
                    <th>邮箱接收地址</th>
                    <th>发送黄金价格</th>
                    <th>发送时间</th>
                    <th>发送状态</th>
                </tr>
                </thead>
    <?php
    //循环展示数据
    function substr_cut($user_name){
        $strlen     = mb_strlen($user_name, 'utf-8');
        $firstStr     = mb_substr($user_name, 0, 1, 'utf-8');
        $lastStr     = mb_substr($user_name, -1, 1, 'utf-8');
        return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
    }
    foreach ($models as $model) {

        echo '<tr>';

        echo '<td>';
        echo $model->id;
        echo '</td>';
        echo '<td>';
        echo md5 ($model->user_id);
        echo '</td>';
        echo '<td>';
        echo substr_cut($model->user_id);
        echo '</td>';
        echo '<td>';
        echo $model->price;
        echo '</td>';
        echo '<td>';
        echo date('Ymd H:i:s',$model->create_time);
        echo '</td>';
        echo '<td>';
        if($model->type==0){
            echo '<button type="button" class="btn btn-danger">';
            echo '未发送';
            echo '</button>';
        }else{
            echo '<button type="button" class="btn btn-success">';
            echo '发送成功';
            echo '</button>';
        }
        echo '</td>';

        echo '</tr>';

    }
    echo '</table>';
    //显示分页页码
    echo LinkPager::widget([
        'pagination' => $pages,
    ])
    ?>


        </div>
    </div>
</div>
<script>
    var html =  '<button style="border-radius: 50%;height: 16px;width: 16px;background-color:blue;order:0px solid red"></button>运行中     <a href="javascript:void(0)" onclick="set(this)">关闭邮件提醒</a>';
    var code =  '<button style="border-radius: 50%;height: 16px;width: 16px;background-color: red;order:0px solid red"></button>已停止     <a href="javascript:void(0)" onclick="set(this)">开启邮件提醒</a>';

    function set(e) {
        $.post("/login/edit-user",{
            email:'<?php echo $email;?>'
        },function(result){
            console.log(result)
            if(result.data.status==1){
                $("#status").html(html)
            }else{
                $("#status").html(code)
            }
        })
        return false;
    }
    $.get("/login/user",{
        email:'<?php echo $email;?>'
    },function(result){
        console.log(result)

         if(result.data.status==1){
            $("#status").html(html)
        }else{
            $("#status").html(code)
        }

    });
</script>


</body>
</html>
