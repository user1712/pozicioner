<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/proxy.php';
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/vendor/autoload.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/push.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/get.php'; 
    use Curl\MultiCurl;
    use DiDom\Document;
    $multi_curl = new MultiCurl();
    $multi_curl->setProxy($proxy_ip, $proxy_port, $proxy_login, $proxy_pass);
    $multi_curl->setProxyTunnel();
    $push = new push();
    $get = new get();
    
$multi_curl->success(function($instance) {
    global $push;
    global $get;
    $url = $instance->url;
    if($get->check($url) > 0) {
        /* Если продукт есть в базе, мы обновим наличие  */
        $id = $get->url_id($url);
        if ($get->day($id) > 5) {
            $push->product_stock($instance);
        }
    } else {
        /* Если в базе нет, создаем новый продукт */
        $push->product_data($instance);
    }
  
});

$i=0;
foreach($get->product_links() as $val) {
    $i++;
    $multi_curl->addGet($val['url']);
    /* if($i == 10) {break;} */
}
$arrs = $multi_curl->start();
?>