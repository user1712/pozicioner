<?php
function translit($category) {
  $category = (string) $category; // преобразуем в строковое значение
  $category = trim($category); // убираем пробелы в начале и конце строки
  $category = function_exists('mb_strtolower') ? mb_strtolower($category) : strtolower($category); // переводим строку в нижний регистр (иногда надо задать локаль)
  $category = strtr($category, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
  $category = str_replace(' ', '_', $category);
  return $category; // возвращаем результат
}

