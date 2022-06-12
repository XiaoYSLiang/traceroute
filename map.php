<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>轨迹回放</title>
    <link rel="stylesheet" href="https://a.amap.com/jsapi_demos/static/demo-center/css/demo-center.css"/>
    <style>
        html, body, #container {
            height: 100%;
            width: 100%;
        }

        .input-card .btn{
            margin-right: 1.2rem;
            width: 9rem;
        }

        .input-card .btn:last-child{
            margin-right: 0;
        }
    </style>
</head>
<body>
<div id="container"></div>
<div class="input-card">
    <h4>数据传输动画</h4>
    <div class="input-item">
        <input type="button" class="btn" value="开始动画" id="start" onclick="startAnimation()"/>
        <input type="button" class="btn" value="暂停动画" id="pause" onclick="pauseAnimation()"/>
    </div>
    <div class="input-item">
        <input type="button" class="btn" value="继续动画" id="resume" onclick="resumeAnimation()"/>
        <input type="button" class="btn" value="停止动画" id="stop" onclick="stopAnimation()"/>
    </div>
</div>
<?php
function getip(){
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = "unknown";
    return ($ip);
    }
$location;
$location1;
$location2;
 $ftp = $_POST;
 $syp = $_POST['textarea'];
 $ss = getip();
exec('python main.py '.$syp, $array);
exec('python main.py '.$ss, $array1);
$ff = count($array);
$f1 = count($array1);
for($i= 0 ; $i<$ff;$i++){
$location[$i] = explode(',',$array[$i]);
}
for($i = 0; $i <$ff;$i++){
    $location[$i][0] = str_replace(PHP_EOL, '', $location[$i][0]); 
}
for($i= 0 ; $i<$f1;$i++){
    $location1[$i] = explode(',',$array1[$i]);
    }
    for($i = 0; $i <$f1;$i++){
        $location1[$i][0] = str_replace(PHP_EOL, '', $location1[$i][0]); 
    }
    $location2 = array_merge($location,$location1);
?>
<script src="https://webapi.amap.com/maps?v=1.4.2&key=58a21c1753060ec67806dab865753535"></script>
<script type="text/javascript" src="https://webapi.amap.com/demos/js/liteToolbar.js"></script>
<script type="text/javascript">
var arr = <?php echo json_encode($location2);?>;
var marker, lineArr = arr;
    var lineArr1 = lineArr.reverse();
    var map = new AMap.Map("container", {
        resizeEnable: true,
        center: [116.397428, 39.90923],
        zoom: 17,
        viewMode: '3D' //使用3D视图
    });

    marker = new AMap.Marker({
        map: map,
        position: [116.478935,39.997761],
        icon: "//a.amap.com/jsapi_demos/static/demo-center/icons/poi-marker-default.png",
        offset: new AMap.Pixel(-20, -62),
        autoRotation: true,
        angle:-90,
    });

    // 绘制轨迹
    var polyline = new AMap.Polyline({
        map: map,
        path: lineArr1,
        showDir:true,
        strokeColor: "#28F",  //线颜色
        // strokeOpacity: 1,     //线透明度
        strokeWeight: 6,      //线宽
        // strokeStyle: "solid"  //线样式
    });

    var passedPolyline = new AMap.Polyline({
        map: map,
        // path: lineArr,
        strokeColor: "#AF5",  //线颜色
        // strokeOpacity: 1,     //线透明度
        strokeWeight: 6,      //线宽
        // strokeStyle: "solid"  //线样式
    });


    marker.on('moving', function (e) {
        passedPolyline.setPath(e.passedPath);
    });

    map.setFitView();

    function startAnimation () {
        marker.moveAlong(lineArr1, 2000000);
    }

    function pauseAnimation () {
        marker.pauseMove();
    }

    function resumeAnimation () {
        marker.resumeMove();
    }

    function stopAnimation () {
        marker.stopMove();
    }
    var map = new AMap.Map('meMap', {
        resizeEnable: true,
        center: arr[0],

        zoom: 15,
        viewMode: '3D' //使用3D视图
    });
    AMap.plugin('AMap.ToolBar', function() {
        var toolbar = new AMap.ToolBar();
        map.addControl(toolbar)
    });


    var ico = new AMap.Icon({
        size: new AMap.Size(24, 30), // 图标尺寸
        image: 'static/333.jpeg', // Icon的图像
        imageOffset: new AMap.Pixel(0, 0), // 图像相对展示区域的偏移量，适于雪碧图等
        imageSize: new AMap.Size(24, 30) // 根据所设置的大小拉伸或压缩图片，对应style的width和height
    });
    // 创建一条折线覆盖物
    var path = arr;//传递二维数组的值输出
    var polyline = new AMap.Polyline({
        path: path,
        borderWeight: 2, // 线条宽度，默认为 1
        strokeColor: 'red', // 线条颜色
        lineJoin: 'round' // 折线拐点连接处样式
    });
    map.add(polyline);

    // 创建两个点标记
    var marker1 = new AMap.Marker({
        position: arr[0],   // 经纬度对象，如 new AMap.LngLat(116.39, 39.9); 也可以是经纬度构成的一维数组[116.39, 39.9]
        title: '北京'
    });
    var marker2 = new AMap.Marker({
        position: new AMap.LngLat(116.382122, 39.901176),   // 经纬度对象，如 new AMap.LngLat(116.39, 39.9); 也可以是经纬度构成的一维数组[116.39, 39.9]
        title: '北京'
    });
    map.add(marker1);
    map.add(marker2);

    // 自动适配到合适视野范围
    // 无参数，默认包括所有覆盖物的情况
    map.setFitView();
    // 传入覆盖物数组，仅包括polyline和marker1的情况
    map.setFitView([polyline,marker1]);
    map.add(marker); //将创建好的marker放到地图上面
</script>
</body>
</html>