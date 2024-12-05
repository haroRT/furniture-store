<?php
    $start = 0;
    $row_per_page =8;
    $records = $conn->query("SELECT * FROM products");
    $number_of_row = $records->num_rows;

    $pages = ceil($number_of_row / $row_per_page);

    if(isset($_GET['pages'])){
        $page = $_GET['pages'] - 1;
        $start = $page * $row_per_page;
    }

    $resultProduct = $conn->query("SELECT * FROM products LIMIT $start,  $row_per_page");
?>