<?php
// Bắt đầu session
    session_start();
    include_once("connect.php")
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
    <style>
        * {
        box-sizing: border-box;
        }
        html, body {
            height: 100%;
        }
    </style>
    <title>Document</title>
</head>
<body>
    <div class="wrapper relative">
        <?php
            include("pages/header.php");
            include("pages/body.php");
            include("pages/footer.php");
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>