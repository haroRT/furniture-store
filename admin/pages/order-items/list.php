<?php
    $start = 0;
    $row_per_page = 8;
    $records = $conn->query("SELECT orders.order_id, order_items.order_item_id FROM orders JOIN order_items ON orders.order_id = order_items.order_id  GROUP BY orders.order_id");
    $number_of_row = $records->num_rows;

    $pages = ceil($number_of_row / $row_per_page);

    if(isset($_GET['pages'])){
        $page = $_GET['pages'] - 1;
        $start = $page * $row_per_page;
    }

    $result1 = $conn->query("SELECT orders.order_id, order_items.order_item_id, products.product_name, order_items.quantity, order_items.price FROM orders JOIN order_items ON orders.order_id = order_items.order_id JOIN products ON products.product_id = order_items.product_id");
?>