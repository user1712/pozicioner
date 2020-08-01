<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/proxy.php';
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/vendor/autoload.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/push.php';  
    use Curl\MultiCurl;
    use Curl\Curl;
    use DiDom\Document;
    $multi_curl = new MultiCurl();
    $multi_curl->setProxy($proxy_ip, $proxy_port, $proxy_login, $proxy_pass);
    $multi_curl->setProxyTunnel();
    $push = new push();

    $curl = new Curl();
    $curl->setProxy($proxy_ip, $proxy_port, $proxy_login, $proxy_pass);
    $curl->setProxyTunnel();
    $curl->get('https://vapelife.com.ua/');
    $html = $curl->response;
   
$multi_curl->success(function($instance) {
    global $push;
    $arr = $instance->response;
    $document = new Document($arr);
    $page = $document->find('.breadcrumb a'); 
    $push->addcatalog($page);
});

$document = new Document($html);

$links = $document->find('#menu-v a'); 
foreach($links as $val) {
    /* Добавляем каждую ссылку в мультипоток */
    $multi_curl->addGet($val->href);
}
$arrs = $multi_curl->start();
?>