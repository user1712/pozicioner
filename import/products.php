<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/vendor/autoload.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/push.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/get.php'; 
    use Curl\MultiCurl;
    use DiDom\Document;
    $multi_curl = new MultiCurl();
    $push = new push();
    $get = new get();
    
    foreach($get->products() as $data) {
        $push->add_product($data);
    break;
    }
  
    
   
   
?>