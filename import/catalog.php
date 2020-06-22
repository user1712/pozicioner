<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/vendor/autoload.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/push.php';  
    require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/get.php'; 
    use Curl\MultiCurl;
    use DiDom\Document;
    $multi_curl = new MultiCurl();
    $push = new push();
    $get = new get();
 
    foreach ($get->categories1() as $val) {
        $cat = $val['catalog1'];
        $push->add_cat($cat);
    }
    foreach ($get->categories2() as $val) {
        $cat = $val['catalog2'];
        $par = $get->check_cat($cat); /* Родитель */
        $arr = array(
            "cat" => "$cat",
            "parent" => "$par"
        );
        echo $push->add_cat2($arr);
    }

  
    
   
   
?>