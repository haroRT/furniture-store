<?php include_once("../connect.php");
// Bắt đầu session
    session_start();
    $user_id = $_SESSION["userId"];
    $product_id = null;
    $product_name = "";
    $price = null;
    $stock_quantity = null;
    $description = "";
    $img_url = "";
    $status = null;
    if(isset($_POST["submit"]) && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']){
      $product_id = htmlspecialchars($_POST["productId"], ENT_QUOTES, 'UTF-8');
      $price = htmlspecialchars($_POST["price"], ENT_QUOTES, 'UTF-8');
      $quantity = htmlspecialchars($_POST["quantity"], ENT_QUOTES, 'UTF-8');

      $resultCheckCart = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");
      
      if($resultCheckCart->num_rows == 1){
        $row = $resultCheckCart->fetch_assoc();
        $newQuantity = $row["quantity"] + $quantity;
        $conn->query("UPDATE cart SET quantity = $newQuantity, price = $price WHERE user_id = $user_id AND product_id = $product_id");
      }
      else{
        $conn->query("INSERT INTO cart (user_id, product_id, quantity, price) VALUES($user_id, $product_id, $quantity, $price)");
      }
    }
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
    <?php include("header.php"); ?>
    <section class="py-8 bg-white md:py-16 dark:bg-gray-900 antialiased">
      <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
        <?php
          if(isset($_GET["productId"])){
            $product_id = htmlspecialchars($_GET["productId"], ENT_QUOTES, 'UTF-8');
            $productDetail = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
            if($productDetail->num_rows == 1){
              $row = $productDetail->fetch_assoc();
              $product_name = $row["product_name"];
              $price = $row["price"];
              $stock_quantity = $row["stock_quantity"];
              $description = $row["description"];
              $img_url = $row["img_url"];
              $status = $row["status"];
          }
            echo '
          <form method="post" class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
            <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
              <img class="w-full dark:hidden max-w-full h-auto" src="'.$img_url.'" alt="" />
            </div>
            <div class="hidden">
                <input type="number" name="productId" id="update-product-id" value="'.$product_id.'" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="">
                <input type="number" name="price" id="update-product-id" value="'.$price.'" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="">
            </div>
            <div class="mt-6 sm:mt-8 lg:mt-0">
              <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                '.$product_name.'
              </h1>
              <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
                <p
                  class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-white"
                >
                  '.number_format($row["price"], 0, '', '.').'
                </p>
                
              </div>
              <div class="my-2">
                <span>Còn lại: <span class="font-semibold">'.$stock_quantity.' sản phẩm</span></span>
              </div>
              <div class="mt-4 sm:gap-4 sm:items-center sm:flex sm:mt-4">
                <div class="flex flex-row items-center space-x-2 max-w-xs ">
                  <div class="relative flex items-center max-w-[8rem]">
                    <button type="button" id="decrement-button" data-input-counter-decrement="quantity-input" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
                      <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                      </svg>
                    </button>
                    <input type="hidden" name="csrf_token" value="'.$_SESSION['csrf_token'].'">
                    <input type="text" name="quantity" id="quantity-input" data-input-counter data-input-counter-min="1" data-input-counter-max="'.$stock_quantity.'" aria-describedby="helper-text-explanation" class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="999" value="1" required />
                    <button type="button" id="increment-button" data-input-counter-increment="quantity-input" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
                        <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                        </svg>
                    </button>
                  </div>
                  <button type="submit" name="submit" class="h-10 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Thêm vào giỏ hàng</button>
                </div>
              </div>
              <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-800" />
              <p class="mb-6 text-gray-500 dark:text-gray-400">
                '.$description.'
              </p>
            </div>
          </form>';
          }
        ?>
      </div>
    </section>
    <?php include("footer.php"); ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>