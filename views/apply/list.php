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
    <!-- 引入 ECharts 文件 -->
    <script src="https://cdn.bootcss.com/echarts/4.0.2/echarts-en.min.js"></script>
    <title> <?php echo date('Y年m月d日')?>京东黄金价格走势图</title>
    <meta name="keywords" content="黄金价格提醒" />
    <meta name="description" content="京东黄金在线价格提醒" />
</head>
<body>
<div class="container">
 <?php echo date('Y年m月d日')?>京东黄金价格走势图<br>
   今日用户买入黄金<?php echo $rest_price;?>元/克,剩余<?php echo $rest;?>元/克,买入<?php echo $maxprice;?>人,卖出<?php echo $minprice;?>人
    <div class="panel panel-default">
        <div class="panel-heading" style="margin-top: 20px">
            <div class="col-sm-offset-2 col-sm-10">
                你好:<?php echo $email?><br>
            </div>
        </div>
        <div class="panel-body">

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                   <button type="button" class="btn btn-success"> <a href="/apply/view" style="color: aliceblue">黄金提醒设置</a></button>
                    <button type="button" class="btn btn-success"> <a href="/apply/view" style="color: aliceblue">基金设置</a></button>


                </div>
            </div>
<!--            <code>-->

<!--            </code>-->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        黄金实时价格<?php echo $price;?>元/克
                        <br>
                        <!-- 为 ECharts 准备一个具备大小（宽高）的 DOM -->
                        <div id="main" style="width: 100%;height:400px;"></div>
                    </div>
                </div>

            <div class="form-group">
<!--                <div class="col-sm-offset-2 col-sm-10">-->
<!--                    黄金一年走势图-->
<!--                    <br>-->
<!--                    <!-- 为 ECharts 准备一个具备大小（宽高）的 DOM -->
<!--                    <div id="main1" style="width: 100%;height:400px;"></div>-->
<!--                </div>-->
            </div>
        </div>



    </div>
</div>

<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));
//    var myChart1 = echarts.init(document.getElementById('main1'));
    // 指定图表的配置项和数据

//    option = {
//        xAxis: {
//            type: 'category',
//            data:
//        },
//        yAxis: [{
//            type: 'value',
//            min: 270,
//            max: 275,
//            minInterval:2,
//            splitArea: {
//                show: true
//            },
//
//        }],
//        series: [{
//            data:
//            type: 'line',
//            smooth: true
//        }]
//    };
    option = {
        tooltip : {
            trigger: 'axis'
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataZoom : {show: true},
                dataView : {show: true},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        dataZoom : {
            show : true,
            realtime : true,
            //orient: 'vertical',   // 'horizontal'
            //x: 0,
            y: 36,
//            width: 400,
            height: 20,
            //backgroundColor: 'rgba(221,160,221,0.5)',
            //dataBackgroundColor: 'rgba(138,43,226,0.5)',
            //fillerColor: 'rgba(38,143,26,0.6)',
            //handleColor: 'rgba(128,43,16,0.8)',
            //xAxisIndex:[],
            //yAxisIndex:[],
//            start : 40,
//            end : 60
        },
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : <?php echo $data['date']?>
            }
        ],
        yAxis: [{
            type: 'value',
            min: <?php echo $min-1;?>,
            max: <?php echo $max+1;?>,
            minInterval:2,
            splitArea: {
                show: true
            },

        }],
        series : [
            {
                name:'价格',
                type:'line',
                data:<?php echo $data['price']?>
            }
        ],
        calculable:false
    };
//
//    option1 = {
//        tooltip : {
//            trigger: 'axis'
//        },
//        toolbox: {
//            show : true,
//            feature : {
//                mark : {show: true},
//                dataZoom : {show: true},
//                dataView : {show: true},
//                magicType : {show: true, type: ['line', 'bar']},
//                restore : {show: true},
//                saveAsImage : {show: true}
//            }
//        },
//        dataZoom : {
//            show : true,
//            realtime : true,
//            //orient: 'vertical',   // 'horizontal'
//            //x: 0,
//            y: 36,
////            width: 400,
//            height: 20,
//            //backgroundColor: 'rgba(221,160,221,0.5)',
//            //dataBackgroundColor: 'rgba(138,43,226,0.5)',
//            //fillerColor: 'rgba(38,143,26,0.6)',
//            //handleColor: 'rgba(128,43,16,0.8)',
//            //xAxisIndex:[],
//            //yAxisIndex:[],
////            start : 40,
////            end : 60
//        },
//        xAxis : [
//            {
//                type : 'category',
//                boundaryGap : false,
//                data : <?php //echo $dataNian['date']?>
//            }
//        ],
//        yAxis: [{
//            type: 'value',
//            min: <?php //echo $minNian-1;?>//,
//            max: <?php //echo $maxNian+1;?>//,
//            minInterval:2,
//            splitArea: {
//                show: true
//            },
//
//        }],
//        series : [
//            {
//                name:'价格',
//                type:'line',
//                data:<?php //echo $dataNian['price']?>
//            }
//        ],
//        calculable:false
//    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
//    myChart1.setOption(option1);
</script>

</body>
</html>
