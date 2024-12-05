<?php
    $user_id = $_SESSION["userId"];
    $start = 0;
    $row_per_page = 8;
    $records = $conn->query("SELECT orders.order_id FROM orders WHERE orders.user_id = $user_id");
    $number_of_row = $records->num_rows;

    $pages = ceil($number_of_row / $row_per_page);

    if(isset($_GET['pages'])){
        $page = $_GET['pages'] - 1;
        $start = $page * $row_per_page;
    }

    $result1 = $conn->query("SELECT orders.order_id, SUM(order_items.quantity) AS total_products, SUM(order_items.quantity * order_items.price) AS total_value, orders.status FROM orders JOIN order_items ON orders.order_id = order_items.order_id WHERE orders.user_id = $user_id GROUP BY orders.order_id LIMIT $start, $row_per_page");
?>