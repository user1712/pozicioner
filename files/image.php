<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/vendor/autoload.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/push.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/get.php'; 
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/proxy.php';
    use Curl\MultiCurl;
    use DiDom\Document;
    $multi_curl = new MultiCurl();
    $multi_curl->setProxy($proxy_ip, $proxy_port, $proxy_login, $proxy_pass);
    $multi_curl->setProxyTunnel();
    $push = new push();
    $get = new get();
    
    $multi_curl->success(function($instance) {
        global $push;
        $url = $instance->url;
        $tag = explode('/', $url);
        $ima = array_pop($tag);
        $filename = '../images/'.$ima.'';
        if (file_exists($filename)) {
            echo 'Пропускаю '.$url.'<br>';
        } else {
            $arr = $instance->response;
            echo 'Качаю '.$url.'<br>';
            file_put_contents("../images/$ima", $arr);
        }
    });
    foreach($get->images_ind() as $dd) {
        $link = $dd['img_ind'];
        $multi_curl->addGet($link); 
   
    }

    $arrs = $multi_curl->start();

    foreach($get->images() as $val) {
        if($val['img'] !== '0') {
            $ars = explode('@', $val['img']);
            foreach($ars as $vali) {
                if(!empty($vali)) {                  
                    $multi_curl->addGet($vali); 
                }
            }
        }
    }
    $arrs = $multi_curl->start(); 
    
  
    
   
   
?>