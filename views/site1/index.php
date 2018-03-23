<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>股票牛人买入分析统计图(gupiao.jd.com)</title>
        <!-- 引入 echarts.js -->
        <script src="https://cdn.bootcss.com/echarts/3.8.5/echarts-en.common.js"></script>
    </head>
<body>

<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
<div style="color:#8c8c8c;font-size: 10px">数据更新于：<?php echo $create;?></div>
<div id="main" style="width: 100%;height:600px;"></div>
<div id="main4" style="width: 100%;height:600px;"></div>
<div id="main1" style="width: 100%;height:600px;"></div>
<div id="main2" style="width: 100%;height:600px;"></div>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));


    // 指定图表的配置项和数据
    var option = {
        title: {
            text: '时间k线',
            subtext: 'gupiao.jd.com'
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross'
            }
        },
        toolbox: {
            show: true,
            feature: {
                saveAsImage: {}
            }
        },
        xAxis:  {
            type: 'category',
            boundaryGap: false,
            data: [
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
                '23:45']
        },
        yAxis: {
            type: 'value',
            axisLabel: {
                formatter: '{value} 次'
            },
            axisPointer: {
                snap: true
            }
        },
        visualMap: {
            show: false,
            dimension: 0,
            pieces: [{
                lte: 6,
                color: 'green'
            }, {
                gt: 6,
                lte: 8,
                color: 'red'
            }, {
                gt: 8,
                lte: 14,
                color: 'green'
            }, {
                gt: 14,
                lte: 17,
                color: 'red'
            }, {
                gt: 17,
                color: 'green'
            }]
        },
        series: [
            {
                name:'买入',
                type:'line',
                smooth: true,
                data: <?php echo $count;?>
            }, {
                name:'卖出',
                type:'line',
                smooth: true,
                data: <?php echo $count1;?>
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);


    //创建第二个
    // 基于准备好的dom，初始化echarts实例
    var myChart1 = echarts.init(document.getElementById('main1'));

    // 指定图表的配置项和数据
//    app.title = '世界人口总量 - 条形图';

    option1 = {
        title: {
            text: '买入前10',
            subtext: '数据来自网络'
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        legend: {
            data: ['买入']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: {
            type: 'value',
            boundaryGap: [0, 10]
        },
        yAxis: {
            type: 'category',
            data: <?php echo $user;?>
        },
        series: [
            {
                name: '买入',
                type: 'bar',
                data: <?php echo $user_data;?>
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart1.setOption(option1);
//
//    //创建第3个
//    // 基于准备好的dom，初始化echarts实例
//    var myChart2= echarts.init(document.getElementById('main2'));
//
//    // 指定图表的配置项和数据
//    //    app.title = '世界人口总量 - 条形图';
//
//    option2 = {
//        title: {
//            text: '卖出前10',
//            subtext: '数据来自网络'
//        },
//        tooltip: {
//            trigger: 'axis',
//            axisPointer: {
//                type: 'shadow'
//            }
//        },
//        legend: {
//            data: ['卖出']
//        },
//        grid: {
//            left: '3%',
//            right: '4%',
//            bottom: '3%',
//            containLabel: true
//        },
//        xAxis: {
//            type: 'value',
//            boundaryGap: [0, 10]
//        },
//        yAxis: {
//            type: 'category',
//            data: <?php //echo $user_m;?>
//        },
//        series: [
//            {
//                name: '卖出',
//                type: 'bar',
//                color: 'blue',
//                data: <?php //echo $user_mdata;?>
//            }
//        ]
//    };
//
//    // 使用刚指定的配置项和数据显示图表。
//    myChart2.setOption(option2);

    //
    // 基于准备好的dom，初始化echarts实例
    var myChart4 = echarts.init(document.getElementById('main4'));


    option4 = {
        title: {
            text: '股票买入',
            subtext: '数据来自网络'
        },
        color: ['#3398DB'],
        tooltip : {
            trigger: 'axis',
            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis : [
            {
                type : 'category',
                data : <?php echo $data['name'];?>,
                axisTick: {
                    alignWithLabel: true
                }
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'买入',
                type:'bar',
                barWidth: '60%',
                data:<?php echo $data['count'];?>
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart4.setOption(option4);
</script>
</body>
</html>