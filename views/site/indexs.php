<html>
<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.css">
<?php
echo "<script src=http://fund.eastmoney.com/pingzhongdata/".$id.".js></script>";
echo "<script src=https://fundgz.1234567.com.cn/js/".$id.".js></script>";
?>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
<style>
    #di{
        width: 500px;
        margin-left: 30%;
        text-align: center;
    }
    p{
        display: block;
        float: left;
    }
</style>
<body>
<!--<title id="s"></title><p><h1></h1></p>-->
<div style="color: red">连跌1天可不加2天可加</div>
<div id="di">
    <div id="name">基金名称:</div>
    <div id="code">   编号:</div>
    <div id="max"> 历史最高排名:</div>
    <div id="maxd"> 历史连跌:</div>
    <div id="maxz"> 历史连涨:</div>
    <div id="yi">  给出建议:</div>
    <div id="zuo"> 同比昨天:</div>
    <div id="jinri"></div>
</div>
</body>
<script>
    //最小值
    function getMaximin(arr,maximin)
    {
        if(maximin=="max")
        {
            return Math.max.apply(Math,arr);
        }
        else if(maximin=="min")
        {
            return Math.min.apply(Math, arr);
        }
    }
    //    console.log();
    //同步数据
    var data = Data_rateInSimilarType;
    var data_net = Data_netWorthTrend ;
    $maxL = 0; //涨
    $minL = 0; //跌
    maxDataz = []
    maxDatad = []
    for (var r =0;r<data_net.length;r++)
    {
        var rss = data_net[r].equityReturn;
        if(rss>0){
            $maxL = $maxL+1;
            if($minL==0){

            }else{
                maxDatad.push($minL)
                $minL = 0;
            }

        }else{
            $minL = $minL+1;
            if($maxL==0){

            }else{
                maxDataz.push($maxL)
                $maxL = 0;
            }
        }
    }
    $('#maxd').append(getMaximin(maxDatad,'max')+'天')
    $('#maxz').append(getMaximin(maxDataz,'max')+'天')
    if($maxL == 0)
    {
        $('#jinri').append("<font style='font-size: 30px;color: red'>今日连跌:"+$minL+"天</font>")
    }else{
        $('#jinri').append("<font style='font-size: 30px;color: steelblue'>今日连涨:"+$maxL+"天</font>")
    }

    var name = fS_name;  //name
    var code = fS_code;  //name
    lengths = data.length
    y = 2            //30天
    max = 9999999;
    date =589-y
    $('#s').html(name+code)
    for (var i =0;i<data.length;i++)
    {
        var yyd = data[i].y
        if(yyd<max){
            max = data[i].y
        }
    }

    //    for (var s =date;s<data.length;s++)
    //    {
    var zhi = data[lengths-2].y-data[lengths-1].y
    var yys = data[lengths-1].y
    var dataName
    if(yys>=45 && yys<100){
        dataName = "<font style='color: #00aa00;font-size: 26px'>可以持有</font>"+yys
    }else
    if(yys>=100 && yys<300){
        dataName = "<font style='color: #00aa00;font-size: 26px'>建议持有</font>"+yys
    }else
    if(yys>=300){
        dataName = "<font style='color: red;font-size: 26px'>强烈建议持有</font>"+yys
    }else{
        dataName = "<font style='color: #00aa00;font-size: 26px'>稳定</font>"+yys
    }
    //    }
    if(zhi>0){
        zhi = zhi + '<img src="../../web/image/zs.png" style="width: 20px">';
    }else{
        zhi = zhi +'<img src="../../web/image/zd.png"  style="width: 20px">';
    }
    $('#name').append("<h1>"+name+"</h1>")
    $('#code').append(code)
    $('#max').append(max)
    $('#yi').append(dataName)
    $('#zuo').append(zhi)
    function jsonpgz(response){
        console.log(response)
    }
    //    $('h1').html(name+':'+code +'最高排名:'+max+':<font style="color:red;font-size:34px">给出建议</font>('+dataName+')'+':<font style="color:red;font-size:34px">同比昨天</font>('+zhi+')')
</script>
</html>