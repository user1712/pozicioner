<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/vendor/autoload.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/push.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/get.php'; 
    use Curl\MultiCurl;
    use DiDom\Document;
    $multi_curl = new MultiCurl();
    $push = new push();
    $get = new get();
    $arr = $get->products();
    shuffle($arr);
    foreach($arr as $datas) {
        if($push->add_product($datas) == true) {
            $push->add_proatr($datas);
            $push->add_images($datas);
        }
    }
  
    
   
   
?>