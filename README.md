# pozicioner
⚫ Pozicioner - парсер товаров с интернет-магазинов. 
<br><br>
За ввод и вывод данных отвечают два файла: <br>
✅ Class/Push.php - сохранение данных <br>
✅ Class/Get.php - получение данных <br>
<br>

<b>✅ Files/Catalog.php</b> - первая ступень в сборе данных. Собирает все категории на сайте доноре с сохранением структуры меню и записывает данные в базу данных. Процедура добавления новых категорий в случае появления на сайте доноре предусмотрена. За сохранение данных отвечает: 
<table>
<tbody>
<tr>
<td>⚫ Push/addcatalog()</td>
<td>⏩ Сохраняем категории в базу данных</td>
</tr>

</tbody>
</table>


<b>✅ Files/Product.php</b> - собирает все ссылки на товары проходя по каждой категории и сохраняет в базу данных (таблица product_url). Испольюзуются функции:
<table>
<tbody>
<tr>
<td>⚫ Get/category_links</td>
<td>⏪ Получение ссылок на категории (3 - 2 - 1 уровни)</td>
</tr>
<tr>
<td>⚫ Push/product_url</td>
<td>⏩ Сохранение ссылок и названий категорий</td>
</tr>
</tbody>
</table>

<b>✅ Files/Product_Get.php</b> - собирает все данные о товаре, включая атрибуты. Испольюзуются функции:<br>
<table>
<tbody>
<tr>
<td>⚫ Get/Check</td>
<td>⏪ Проверяет был ли ранее товар сохранен в базу</td>
</tr>
<tr>
<td>⚫ Get/Url_id</td>
<td>⏪ Находит ID ссылки на товар</td>
</tr>
<tr>
<td>⚫ Get/Day</td>
<td>⏪ Вернет количество дней от последнего обновления данных о товаре</td>
</tr>
<tr>
<td>⚫ Push/Product_stock</td>
<td>⏩ Обновляет текущий статус наличия товара в базе</td>
</tr>
<tr>
<td>⚫ Push/Product_data</td>
<td>⏩ Используется для нового товара. Получение и сохранение данных.</td>
</tr>
</tbody>
</table>

<b>✅ Files/Image.php</b> - собирает все связанные изображения. Испольюзуются функции:
<table>
<tbody>
<tr>
<td>⚫ Get/images_ind</td>
<td>⏪ Первый этап, сбор главных изображения товара</td>
</tr>
<tr>
<td>⚫ Get/images</td>
<td>⏪ Второй этап, сбор дополнительных изображения товара</td>
</tr>
</tbody>
</table>

<b>✅ Import/Catalog.php</b> - перенос категорий в базу данных OpenCart. Испольюзуются функции:
<table>
<tbody>
<tr>
<td>⚫ Get/categories1</td>
<td>⏪ Первый этап, получает главные категории</td>
</tr>
<tr>
<td>⚫ Push/add_cat</td>
<td>⏩ Сохранение данных в базе OpenCart</td>
</tr>
<tr>
<td>⚫ Get/categories2</td>
<td>⏪ Первый этап, получает подкатегории</td>
</tr>
<tr>
<td>⚫ Get/check_cat</td>
<td>⏪ Получение ИД родительской категории</td>
</tr>
<tr>
<td>⚫ Push/add_cat2</td>
<td>⏩ Сохранение подкатегорий в базе OpenCart</td>
</tr>
</tbody>
</table>

<b>✅ Import/atr.php</b> - перенос названия атрибутов в базу данных OpenCart. Испольюзуются функции:
<table>
<tbody>
<tr>
<td>⚫ Push/atr_group</td>
<td>⏩ Создает главную группу (Характеристики)</td>
</tr>
<tr>
<td>⚫ Get/atr</td>
<td>⏪ Получаем список уникальных атрибутов</td>
</tr>
<tr>
<td>⚫ Push/atr_add</td>
<td>⏩ Сохранение названий атрибутов в базе OpenCart</td>
</tr>
</tbody>
</table>

<b>✅ Import/brend.php</b> - перенос производителей в базу данных OpenCart. Испольюзуются функции:
<table>
<tbody>
<tr>
<td>⚫ Push/add_brend</td>
<td>⏩ Записываем в базу данных OpenCart</td>
</tr>
<tr>
<td>⚫ Get/brend</td>
<td>⏪ Получаем список производителей</td>
</tr>
</tbody>
</table>

<b>✅ Import/products.php</b> - перенос товара в базу данных OpenCart. Испольюзуются функции:
<table>
<tbody>
<tr>
<td>⚫ Get/products</td>
<td>⏪ Получаем данные о товарах</td>
</tr>
<tr>
<td>⚫ Push/add_product</td>
<td>⏩ Записываем данные о товаре в базу данных OpenCart</td>
</tr>
<tr>
<td>⚫ Push/add_proatr</td>
<td>⏩ Записываем атрибуты в базу данных OpenCart</td>
</tr>
<tr>
<td>⚫ Push/add_images</td>
<td>⏩ Добавляем изображения в OpenCart</td>
</tr>
</tbody>
</table>