<?php
error_reporting(E_ALL^E_NOTICE);
require 'Maze.php';
require 'AStar.php';

$maze = new Maze();
$aStar = new AStar();

$w = 6;     // 迷宫宽
$h = 6;     // 迷宫高

$maze->set($w, $h);
$maze->create();
$grids = $maze->get();

// 寻路
$aStar->set($w, $h, $grids);
$path = $aStar->search(0, 35);       // 从零开始找到35

// 画迷宫方法
function div($x, $y, $v){
    global $w, $h, $path;
    //if ( $y > 4 ) $v = 0;
    $k = $y * $w + $x;
    //if ( array_key_exists($k, $this->_enable) ) {
        //echo "<div class=\"grid cell_enable_{$this->_enable[$k]}\" style=\"";
    //} else {
        echo "<div class=\"grid cell_{$v}\" style=\"";
    //}
    echo 'top:'. 34*$y.'px;';
    echo 'left:'. 34*$x.'px;';
    //if ($v & 1)
        //echo "border-top:1px solid #F5F5F5;";
    //if ($v & 2)
        //echo "border-right:1px solid #F5F5F5;";
    //if ($v & 4)
        //echo "border-bottom:solid 1px #F5F5F5;";
    //if ($v & 8)
        //echo "border-left:solid 1px #F5F5F5;";
    if ( in_array($y * $w + $x, $path) )
        echo "color:red;";
    echo '">';
    echo $y * $w + $x;
    echo '</div>'."\n";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-CN" />
<title>php版本的迷宫生成算法及A*寻路算法</title>
<link href="css/mg.css" rel="stylesheet" type="text/css">
</head>
<body>
<div style="width: 212px; height: 212px;margin:20px auto;" class="mg">
<?php
for($y = 0; $y < $h; $y++ ){
    for($x=0; $x < $w; $x++ ){
        $v = $grids[$y * $w + $x];
        div($x, $y, $v);
    }
}
?>
</div>
</body>
</html>
