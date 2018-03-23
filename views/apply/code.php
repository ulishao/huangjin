<?php
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
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <script type="text/javascript"  src="https://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <title>用户注册</title>
</head>
<body>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            用户ID: <?php echo $id;?>
        </div>
        <div class="panel-body">
                        <form class="form-horizontal" role="form" method="post" action=""/>
            <div class="form-group">
                <label for="firstname" class="col-sm-2 control-label">请设置密码</label>
                <div class="col-sm-10">
                    <input type="text" id="email" class="form-control" id="firstname" name="password" placeholder="请输入请设置密码">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" onclick="submit()" class="btn btn-default">请设置密码</button>
                </div>
            </div>
                        </form>
        </div>
    </div>

    <!-- 模态框（Modal） -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">邮件发送成功</h4>
                </div>
                <div class="modal-body">请去邮箱查收</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <script>
//        function submit(){
//            var txt = $("#email").val();
//            //注册
//            $.post("/login/add-user",{
//                password:txt,
//                id:'<?php //echo $id;?>//',
//                code:'<?php //echo $code;?>//'
//            },function(result){
//                console.log(result)
//                $("#myModalLabel").html(result.code)
//                $(".modal-body").html(result.data)
//                $('#myModal').modal({
//                    keyboard: true
//                })
//            });
//        }

    </script>


</body>
</html>
