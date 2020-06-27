<?php
/* require ''.$_SERVER['DOCUMENT_ROOT'].'/pozicioner/db.php';  
$data = new base(); */
/* Класс для записи данных в базу */
class get {
    function category_links($level) {
        global $data; /* Пока не научился */
        $result = $data->sql($q  = "
            SELECT * FROM `category` WHERE `catalog$level` != '0';
        ");
        return $result;
    }
    function product_links() {
        global $data; /* Пока не научился */
        $result = $data->sql($q  = "
            SELECT * FROM `product_url`;
        ");
        return $result;
    }
    function check($id) {
        global $data; /* Пока не научился */
        $result = $data->sql($q  = "
            SELECT `id` FROM `product_url` WHERE `url` = '$id';
        ");
        $id_product = $result[0][0];
        $result = $data->sql($q  = "
            SELECT `id_url` FROM `products` WHERE `id_url` = '$id_product';
        ");
        return $result[0][0];
    }
    function url_id($url) {
        global $data; /* Пока не научился */
        $result = $data->sql($q  = "
            SELECT `id` FROM `product_url` WHERE `url` = '$url';
        ");
        return $result[0][0];
    }
    function day($id) {
        global $data; /* Пока не научился */
        $created = date("Y-m-d H:i:s");
        $result = $data->sql($q  = "
            SELECT `update` FROM `products` WHERE `id_url` = '$id';
        ");
        $date = $result[0]['update'];
        $now = time(); // or your date as well
        $your_date = strtotime($date);
        $datediff = $now - $your_date;
        return round($datediff / (60 * 60 * 24));
       
    }
    function images_ind() {
        global $data; /* Пока не научился */
        $result = $data->sql($q  = "
            SELECT `img_ind` FROM `products`;
        ");
        return $result;
    }
    function images() {
        global $data; /* Пока не научился */
        $result = $data->sql($q  = "
            SELECT `img` FROM `products`;
        ");
        return $result;
    }
    function categories1() {
        global $data; /* Пока не научился */
        $result = $data->sql($q  = "
            SELECT DISTINCT `catalog1` FROM `category`;
        ");
        return $result;
    }
    function categories2() {
        global $data; /* Пока не научился */
        $result = $data->sql($q  = "
            SELECT DISTINCT `catalog2` FROM `category`;
        ");
        return $result;
    }
    function check_cat($cat) {
        global $data; /* Пока не научился */
        global $oc; /* Пока не научился */
        $result = $data->sql($q  = "
            SELECT DISTINCT `catalog1` FROM `category` WHERE `catalog2` = '$cat';
        ");
        $ca = $result[0]['catalog1'];
        $result = $oc->sql($q  = "
            SELECT DISTINCT `category_id` FROM `oc_category_description` WHERE `name` = '$ca';
        ");
        return $result[0]['category_id'];
    }

    function products() {
        global $data; /* Пока не научился */
        $result = $data->sql($q  = "
            SELECT * FROM `products`;
        ");
        return $result;
    }

    function atr() {
        global $data; /* Пока не научился */
        $result = $data->sql($q  = "
            SELECT DISTINCT `attribute` FROM `attributes`;
        ");
        return $result;
    }

    function brend() {
        global $data; /* Пока не научился */
        $result = $data->sql($q  = "
            SELECT DISTINCT `brand` FROM `products`;
        ");
        return $result;
    }
}
?>
