<html>
<title>黄金价格提醒邮箱发送
</title>
<head>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-8264407082369693",
            enable_page_level_ads: true
        });
    </script>
</head>
<meta charset="utf-8">
<!-- 引入 ECharts 文件 -->
<script src="https://cdn.bootcss.com/echarts/4.0.2/echarts-en.min.js"></script>
<div style="margin-left: 20%">
    <p>黄金价格提醒黄金价格涨幅低于269高于274会发送邮件</p>
    <form action="http://www.lihai.ink/site" method="post">
        <input type="hidden" name="_csrf" id='csrf' value="<?= Yii::$app->request->csrfToken ?>">
        <p>你的邮箱地址: <input type="text" name="mail" /></p>
        <input type="submit" value="Submit" />
    </form>

    <!-- 为 ECharts 准备一个具备大小（宽高）的 DOM -->
    <div id="main" style="width: 600px;height:400px;"></div>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));

        // 指定图表的配置项和数据

        option = {
            xAxis: {
                type: 'category',
                data: <?php echo $data['date']?>,
            },
            yAxis: [{
                type: 'value',
                min: 270,
                max: 275,
                minInterval:2,
                splitArea: {
                    show: true
                },

            }],
            series: [{
                data: <?php echo $data['price']?>,
                type: 'line',
                smooth: true
            }]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
</div>

</html>