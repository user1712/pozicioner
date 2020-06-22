<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/vendor/autoload.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/push.php';  
    use Curl\MultiCurl;
    use DiDom\Document;
    $multi_curl = new MultiCurl();
    $push = new push();
/* В данном блоке все названия и ссылки категорий (подкатегорий) */
$multi_curl->success(function($instance) {
    global $push;
    /* Далее проверяем кажду категорию на вложенность */
    $arr = $instance->response;
    $document = new Document($arr);
    /* Собираем хлебные крошки */
    $page = $document->find('.breadcrumb a'); 
    $push->addcatalog($page);
});
/* Собираем все  */
$document = new Document('https://vapelife.com.ua/', true);
$links = $document->find('#menu-v a'); 
foreach($links as $val) {
    /* Добавляем каждую ссылку в мультипоток */
    $multi_curl->addGet($val->href);
}
$arrs = $multi_curl->start();
?>