<?php
require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/vendor/autoload.php';  
require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/db.php';  
require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/class/translit.php';  
$oc = new oc();
$data = new base();
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
                INSERT INTO `oc_category_path` SET `category_id` = @lastID, `path_id` = @lastID, `level` = '0';
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
        $tra = translit($cat);
        $result = $oc->sql($q  = "
            SELECT `name` FROM `oc_category_description` WHERE `name` = '$cat';
        ");
        $check = $result[0]['name'];
        if($check == $cat) {} 
        else {
            $result = $oc->sql($q  = "
                INSERT INTO oc_category SET parent_id = '$par', `top` = '1', `column` = '2', sort_order = '0', status = '1', noindex = '1', date_modified = '$created', date_added = '$created', image = '';
                SET @lastID := LAST_INSERT_ID();
                SELECT LAST_INSERT_ID();
                INSERT INTO oc_category_description SET category_id = @lastID, language_id = '1', name = '$cat', description = '', meta_title = '', meta_h1 = '', meta_description = '', meta_keyword = '';
                INSERT INTO oc_category_description SET category_id = @lastID, language_id = '3', name = '$cat', description = '', meta_title = '', meta_h1 = '', meta_description = '', meta_keyword = '';
                INSERT INTO `oc_category_path` SET `category_id` = @lastID, `path_id` = @lastID, `level` = '0';
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

    function atr_group() {
        global $oc; /* Пока не научился */
        $result = $oc->sql($q  = "
            SELECT `name` FROM `oc_attribute_group_description` WHERE `name` = 'Характеристики';
        ");
        $check = $result[0]['name'];
        if(empty($check)) {
            $result = $oc->sql($q  = "
                INSERT INTO oc_attribute_group SET sort_order = '1';
                SET @lastID := LAST_INSERT_ID();
                INSERT INTO oc_attribute_group_description SET attribute_group_id = @lastID, language_id = '1', name = 'Характеристики';
                INSERT INTO oc_attribute_group_description SET attribute_group_id = @lastID, language_id = '3', name = 'Характеристики';
            ");
        } 
    }

    function atr_add($val) {
        global $oc; /* Пока не научился */
        $result = $oc->sql($q  = "
            SELECT `attribute_group_id` FROM `oc_attribute_group_description` WHERE `name` = 'Характеристики';
        ");
        $check = $result[0]['attribute_group_id'];
        $result = $oc->sql($q  = "
            INSERT INTO oc_attribute SET attribute_group_id = '$check', sort_order = '0';
            SET @lastID := LAST_INSERT_ID();
            INSERT INTO oc_attribute_description SET attribute_id = @lastID, language_id = '1', name = '$val';
            INSERT INTO oc_attribute_description SET attribute_id = @lastID, language_id = '3', name = '$val';
        ");
    }

    function add_brend($val) {
        global $oc; /* Пока не научился */
        $result = $oc->sql($q  = "
            SELECT `name` FROM `oc_manufacturer`  WHERE `name` = '$val';
        ");
        $check = $result[0]['name'];
        if($check !== $val) {
            $result = $oc->sql($q  = "
                INSERT INTO oc_manufacturer SET name = '$val', sort_order = '0', noindex = '0';
                SET @lastID := LAST_INSERT_ID();
                INSERT INTO oc_manufacturer_to_layout SET manufacturer_id = @lastID, store_id = '0', layout_id = '0';
                UPDATE oc_manufacturer SET image = '' WHERE manufacturer_id = @lastID;
                INSERT INTO oc_manufacturer_description SET manufacturer_id = @lastID, language_id = '1', description = '', description3 = '', meta_title = '', meta_h1 = '', meta_description = '', meta_keyword = '';
                INSERT INTO oc_manufacturer_description SET manufacturer_id = @lastID, language_id = '3', description = '', description3 = '', meta_title = '', meta_h1 = '', meta_description = '', meta_keyword = '';
                INSERT INTO oc_manufacturer_to_store SET manufacturer_id = @lastID, store_id = '0';
            ");
            $result = $oc->sql($q  = "
                SELECT `manufacturer_id` FROM `oc_manufacturer`  WHERE `name` = '$val';
            ");
            $id = $result[0]['manufacturer_id'];
            $tra = translit($val);
            $result = $oc->sql($q  = "
                INSERT INTO oc_seo_url SET store_id = '0', language_id = '1', query = 'manufacturer_id=1', keyword = 'ru_$tra';
                INSERT INTO oc_seo_url SET store_id = '0', language_id = '3', query = 'manufacturer_id=1', keyword = 'ua_$tra'
            ");
            echo $tra;
        }
    }

    function add_product($val) {
        global $oc; /* Пока не научился */
        $name =  $val['name'];
        $model =  $val['id'];
        if( $val['stock'] == 'В наличии') {
            $stock = 100;
        } else {
            $stock = 0;
        }
        $brend =  $val['brand'];
        $result = $oc->sql($q  = "
            SELECT `manufacturer_id` FROM `oc_manufacturer`  WHERE `name` = '$brend';
        ");
        $manufacturer_id = $result[0]['manufacturer_id'];
        echo $manufacturer_id;
        $img_ind =  $val['img_ind'];
        $temp = array_reverse(explode('/', $img_ind));
        $img_ind = $temp[0];
        $description = htmlspecialchars($val['description'], ENT_QUOTES);

        $temp =  $val['category'];
        $result = $oc->sql($q  = "
            SELECT `category_id` FROM `oc_category_description`  WHERE `name` = '$temp';
        ");
        $category_id = $result[0]['category_id'];
        $price =  $val['price'];

        $result = $oc->sql($q  = "
            INSERT INTO oc_product SET model = '$model', sku = '', upc = '', ean = '', jan = '', isbn = '', mpn = '', location = 'г. Киев', quantity = $stock, minimum = '1', subtract = '1', stock_status_id = '7', date_available = '2020-06-27', manufacturer_id = '$manufacturer_id', shipping = '1', price = '$price', points = '0', weight = '0', weight_class_id = '1', length = '0', width = '0', height = '0', length_class_id = '1', status = '1', noindex = '1', tax_class_id = '0', sort_order = '1', date_added = NOW(), date_modified = NOW(), cost = '0.0000';
            SET @lastID := LAST_INSERT_ID();
            UPDATE oc_product SET image = 'catalog/products/$img_ind' WHERE product_id = @lastID;
            INSERT INTO oc_product_description SET product_id = @lastID, language_id = '1', name = '$name', description = '$description', tag = '', meta_title = '', meta_h1 = '', meta_description = '', meta_keyword = '';
            UPDATE oc_product SET image = 'catalog/products/$img_ind' WHERE product_id = @lastID;
            INSERT INTO oc_product_description SET product_id = @lastID, language_id = '3', name = '$name', description = '$description', tag = '', meta_title = '', meta_h1 = '', meta_description = '', meta_keyword = '';
            INSERT INTO oc_product_to_store SET product_id = @lastID, store_id = '0';
            INSERT INTO oc_product_to_category SET product_id = @lastID, category_id = '$category_id';
            UPDATE oc_product_to_category SET main_category = 1 WHERE product_id = @lastID AND category_id = '$category_id';
        ");
        $result = $oc->sql($q  = "
            SELECT `product_id` FROM `oc_product_description`  WHERE `name` = '$name';
        ");
        $temp = $result[0]['product_id'];
        $tra = translit($name);
        $result = $oc->sql($q  = "
            INSERT INTO oc_seo_url SET store_id = '0', language_id = '1', query = 'product_id=$temp', keyword = 'ru_$tra';
            INSERT INTO oc_seo_url SET store_id = '0', language_id = '3', query = 'product_id=$temp', keyword = 'ua_$tra';
            INSERT INTO oc_product_to_layout SET product_id = '$temp', store_id = '0', layout_id = '0';
        ");
    }

    function add_proatr($val) {
        global $data; 
        global $oc; 
        $temp =  $val['id_url'];
        $product = $val['name']; 
        $result = $data->sql($q  = "
            SELECT * FROM `attributes` WHERE `url_id` = '$temp';
        ");
        foreach($result as $val) {
            $name = $val['attribute']; 
            $value = $val['value']; 
            $result = $oc->sql($q  = "
                SELECT `attribute_id` FROM `oc_attribute_description` WHERE `name` = '$name';
            ");
            $attribute_id = $result[0]['attribute_id'];

            $result = $oc->sql($q  = "
                SELECT `product_id` FROM `oc_product_description` WHERE `name` = '$product';
            ");
            $product_id = $result[0]['product_id'];
            $result = $oc->sql($q  = "
                DELETE FROM oc_product_attribute WHERE product_id = '$product_id' AND attribute_id = '$attribute_id';
                DELETE FROM oc_product_attribute WHERE product_id = '$product_id' AND attribute_id = '$attribute_id' AND language_id = '1';
                INSERT INTO oc_product_attribute SET product_id = '$product_id', attribute_id = '$attribute_id', language_id = '1', text = '$value';
                DELETE FROM oc_product_attribute WHERE product_id = '$product_id' AND attribute_id = '$attribute_id' AND language_id = '3';
                INSERT INTO oc_product_attribute SET product_id = '$product_id', attribute_id = '$attribute_id', language_id = '3', text = '$value';
            ");

        }
       
    }

    function add_images($val) {
        global $oc; 
        global $data; 
        
        $temp =  $val['img'];
        $arr = explode('@', $temp);
        $temp =  $val['name'];
        $result = $oc->sql($q  = "
            SELECT `product_id` FROM `oc_product_description` WHERE `name` = '$temp';
        ");
        $product_id = $result[0]['product_id'];
        foreach($arr as $val) {
            if(!empty($val)) {
                $temp = array_reverse(explode('/', $val));
                $img = $temp[0];
                $result = $oc->sql($q  = "
                    INSERT INTO oc_product_image SET product_id = '$product_id', image = 'catalog/products/$img', sort_order = '0';
                ");
            } 
        }
    }
    
}
?>
