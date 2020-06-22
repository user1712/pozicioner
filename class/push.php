<?php
require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/vendor/autoload.php';  
require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/db.php';  
require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/translit.php';  
$data = new base();
$oc = new oc();
use DiDom\Document;
/* Класс для записи данных в базу */
class push {
    function addcatalog($arr) {
        global $data; /* Пока не научился */
        $cat1 = $arr[1]->text();
        $link = $arr[1]->href;
        if(!empty($arr[2])) {$cat2 = $arr[2]->text(); $link = $arr[2]->href;} else {$cat2 = 0;}
        $created = date("Y-m-d H:i:s");
        $ds = $data->sql($q  = "
            INSERT INTO `category` (`catalog1`, `catalog2`, `catalog3`, `created`, `link`) VALUES ('$cat1', '$cat2', 0, '$created', '$link');
        ");
    }
    function product_url($url) {
        global $data; /* Пока не научился */
        $cat = $GLOBALS["cat"]; /* имя категории */
        $created = date("Y-m-d H:i:s");
        $ds = $data->sql($q  = "
            INSERT INTO `product_url` (`url`, `category`, `stat`, `created`) VALUES ('$url', '$cat', 0, '$created');
        ");
    }
    function product_data($url) {
        global $data; /* Пока не научился */
        $url2 = $url->url;
        $url = $url->response;
        $document = new Document($url);
        $page = $document->find('h1::text'); 
        $product_name = $page[0]; /* Наименование продукта */
        $page = $document->find('#tab-description'); 
        $product_text = $page[0]; /* Описание продукта */
        $page = $document->find('.autocalc-product-price::text'); 
        $product_price = $page[0]; /* Цена продукта */
        $product_price = str_replace('.0 грн', '', $product_price);
        $product_price = str_replace(' ', '', $product_price);
        $page = $document->find('.col-sm-8::text'); 
        $product_stock = $page[0]; /* Наличие */
        $page = $document->find('.col-sm-8 a::text'); 
        $product_brand = $page[0]; /* Бренд */
        $page = $document->find('a.thumbnail'); 
        $img_count = count($page); /* Количество изображений */
        $product_index_img = $page[0]->href; /* Главное изображение */
        $created = date("Y-m-d H:i:s");
        if($img_count > 1) {
            $i=0;
            foreach($page as $val) {
                $i++;
                if($i == 1) {continue;}
                $done .= ''.$val->href.'@';
            }
        $images = $done;  /* Блок с дополнительными изрображениями */
        }  else {$images = '0';}
        $result = $data->sql($q  = "
            SELECT * FROM `product_url` WHERE `url` = '$url2';
        ");

        $catalog = $result[0]['category']; /* Категория товара */
        $url_id = $result[0]['id']; /* ID ссылки на товар */
        $page = $document->find('#tab-specification td');
        $i=0;
        foreach($page as $val) {
            $i++;
            $val = strip_tags($val);
            if($i == 1) {continue;}
            if(($i % 2) == 0) {
                $ds = $data->sql($q  = "
                    INSERT INTO `attributes` (`url_id`, `attribute`, `value`) VALUES ('$url_id', '$val', '0');
                ");
            } else {
                $ds = $data->sql($q  = "
                    UPDATE `attributes` SET `value` = '$val' WHERE `url_id` = '$url_id' AND `value` = '0';
                ");
            }
        }
        $ds = $data->sql($q  = "
            INSERT INTO `products` (`name`, `brand`, `category`, `description`, `price`, `img`, `stock`, `img_ind`, `id_url`, `update`) VALUES ('$product_name', '$product_brand', '$catalog', '$product_text', '$product_price', '$images', '$product_stock', '$product_index_img', '$url_id', '$created');
        ");
    }
    function product_stock($id) {
        global $data; /* Пока не научился */
        $link = $id->url;
        $value = $id->response;
        $created = date("Y-m-d H:i:s");
        $result = $data->sql($q  = "
            SELECT `id` FROM `product_url` WHERE `url` = '$link';
        ");
        $id = $result[0]['id'];
        $document = new Document($value);
        $page = $document->find('.col-sm-8::text'); 
        $product_stock = $page[0]; /* Наличие */
        $ds = $data->sql($q  = "
            UPDATE `products` SET `stock` = '$product_stock', `update` = '$created' WHERE `id_url` = '$id';
        ");
    }

    function add_cat($arr) {
        global $oc; /* Пока не научился */
        $created = date("Y-m-d H:i:s");
        $tra = translit($arr);
        $result = $oc->sql($q  = "
            SELECT `name` FROM `oc_category_description` WHERE `name` = '$arr';
        ");
        $check = $result[0]['name'];
        if($check == $arr) {} 
        else {
            $result = $oc->sql($q  = "
                INSERT INTO oc_category SET parent_id = '0', `top` = '1', `column` = '2', sort_order = '0', status = '1', noindex = '1', date_modified = '$created', date_added = '$created', image = '';
                SET @lastID := LAST_INSERT_ID();
                SELECT LAST_INSERT_ID();
                INSERT INTO oc_category_description SET category_id = @lastID, language_id = '1', name = '$arr', description = '', meta_title = '', meta_h1 = '', meta_description = '', meta_keyword = '';
                INSERT INTO oc_category_description SET category_id = @lastID, language_id = '3', name = '$arr', description = '', meta_title = '', meta_h1 = '', meta_description = '', meta_keyword = '';
                INSERT INTO `oc_category_path` SET `category_id` = @lastID, `path_id` = '2', `level` = '0';
                INSERT INTO oc_category_to_store SET category_id = @lastID, store_id = '0';
                INSERT INTO oc_category_to_layout SET category_id = @lastID, store_id = '0', layout_id = '0';
                SELECT `category_id` FROM `oc_category` WHERE `category_id` = @lastID;
            ");
            $result = $oc->sql($q  = "
                SELECT `category_id` FROM `oc_category` GROUP BY category_id DESC;
            ");
            $id = $result[0]['category_id'];
            $result = $oc->sql($q  = "
                INSERT INTO oc_seo_url SET store_id = '0', language_id = '1', query = 'category_id=$id', keyword = 'ru_$tra';
                INSERT INTO oc_seo_url SET store_id = '0', language_id = '3', query = 'category_id=$id', keyword = 'ua_$tra';
            ");
            return $tra;
        }

    }
    function add_cat2($arr) {
        global $oc; /* Пока не научился */
        $created = date("Y-m-d H:i:s");
        $cat = $arr['cat'];
        $par = $arr['parent'];
        $tra = translit($arr);
        $result = $oc->sql($q  = "
            SELECT `name` FROM `oc_category_description` WHERE `name` = '$cat';
        ");
        $check = $result[0]['name'];
        if($check == $arr) {} 
        else {
            $result = $oc->sql($q  = "
                INSERT INTO oc_category SET parent_id = '$par', `top` = '1', `column` = '2', sort_order = '0', status = '1', noindex = '1', date_modified = '$created', date_added = '$created', image = '';
                SET @lastID := LAST_INSERT_ID();
                SELECT LAST_INSERT_ID();
                INSERT INTO oc_category_description SET category_id = @lastID, language_id = '1', name = '$cat', description = '', meta_title = '', meta_h1 = '', meta_description = '', meta_keyword = '';
                INSERT INTO oc_category_description SET category_id = @lastID, language_id = '3', name = '$cat', description = '', meta_title = '', meta_h1 = '', meta_description = '', meta_keyword = '';
                INSERT INTO `oc_category_path` SET `category_id` = @lastID, `path_id` = '2', `level` = '0';
                INSERT INTO oc_category_to_store SET category_id = @lastID, store_id = '0';
                INSERT INTO oc_category_to_layout SET category_id = @lastID, store_id = '0', layout_id = '0';
                SELECT `category_id` FROM `oc_category` WHERE `category_id` = @lastID;
            ");
            $result = $oc->sql($q  = "
                SELECT `category_id` FROM `oc_category` GROUP BY category_id DESC;
            ");
            $id = $result[0]['category_id'];
            $result = $oc->sql($q  = "
                INSERT INTO oc_seo_url SET store_id = '0', language_id = '1', query = 'category_id=$id', keyword = 'ru_$tra';
                INSERT INTO oc_seo_url SET store_id = '0', language_id = '3', query = 'category_id=$id', keyword = 'ua_$tra';
            ");
            return $cat;
        }

    }
}
?>
