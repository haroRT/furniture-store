<?php
    $start = 0;
    $row_per_page =8;
    $records = $conn->query("SELECT products.product_id, products.product_name, categories.category_name, products.price, products.stock_quantity, products.status FROM products INNER JOIN categories ON products.category_id = categories.category_id");
    $number_of_row = $records->num_rows;

    $pages = ceil($number_of_row / $row_per_page);

    if(isset($_GET['pages'])){
        $page = $_GET['pages'] - 1;
        $start = $page * $row_per_page;
    }

    $result1 = $conn->query("SELECT products.product_id, products.product_name, categories.category_name, products.price, products.stock_quantity, products.status FROM products INNER JOIN categories ON products.category_id = categories.category_id LIMIT $start,  $row_per_page");
?>