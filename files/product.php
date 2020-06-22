<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/vendor/autoload.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/push.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/get.php'; 
    use Curl\MultiCurl;
    use DiDom\Document;
    $multi_curl = new MultiCurl();
    $push = new push();
    $get = new get();
    
    $multi_curl->success(function($instance) {
        global $push;
        $arr = $instance->response;
        $document = new Document($arr);
        $page = $document->find('.card-body a'); 
      
        foreach($page as $val) {
            $push->product_url($as = $val->href);
        }
    });
    $i = array_reverse(range(1,3));
    /* Тут я столкнулся с проблемой, каждая ссылка в базе не может быть продублированна, для
    этого я использовал обратный прорядок прохода по ссылкам. Сперва прогоняем категории 3 уровня
    вложенности, потом 2 уровня, и далее первого.*/
    foreach($i as $rd) {
        $category_links = $get->category_links($rd); /* Массив с данными категорий */
        foreach($category_links as $val) {

            if($val['catalog2'] !== '0') { 
                if($cat = $val['catalog3'] !== '0') {
                    $cat = $val['catalog3'];
                } else {$cat = $val['catalog2'];}
            } else {$cat = $val['catalog1'];} 
            $GLOBALS["cat"] = $cat; /* Передаем имя категории на глобальном уровне */
            foreach($i = range(1,23) as $dd) {
                $link = ''.$val['link'].'?limit=100&page='.$dd.'';
                $multi_curl->addGet($link); /* Передаем ссылку на категорию в мультипоток */
            }
            $arrs = $multi_curl->start();
    
        }
    
    }
  
    
   
   
?>