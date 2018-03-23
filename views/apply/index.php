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
    <title>京东黄金在线价格提醒</title>
    <meta name="keywords" content="黄金价格提醒,提醒,价格提醒" />
    <meta name="description" content="京东黄金在线价格提醒" />
</head>
<body>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading" style="margin-top: 20px">

        </div>
        <div class="panel-body">
            <form class="form-horizontal" role="form" method="post" action="/apply/create">
                <div class="form-group">
                    <label for="firstname" class="col-sm-2 control-label">帐号</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="firstname" name="username" placeholder="请输入帐号">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">密码</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="lastname" name="password"  placeholder="请输入密码">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox">请记住我
                            </label>
                        </div>
                        <div style="color:#999;margin:1em 0">
                            如果您忘记密码，请<a href="/apply/register">点此重置or注册</a>。
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">登录</button>
                    </div>
                </div>

                <code>
                    <div class="col-sm-offset-2 col-sm-10">
                        黄金价格将会在小于268 大于275发送邮箱提醒买入卖出
                    </div>

                </code>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div style="color:#999;margin:1em 0">
                            <a href="" target="_blank" style="display: inline-block; margin: 12px 0px;">有问题要反馈？请联系980167048@qq.com</a>
                        </div>
                    </div>

                </div>

            </form>
        </div>



    </div>
</div>



</body>
</html>
