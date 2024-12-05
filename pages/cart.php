<?php include_once("../auth/verify.php") ?>
<?php 
  include_once("../connect.php");

  $message = "";
  $user_id = $_SESSION["userId"];
  $total = 0;
  $cart_id = null;
  $resultCart = $conn->query("SELECT * FROM cart INNER JOIN products ON cart.product_id = products.product_id WHERE user_id = $user_id");
  if(isset($_POST["submit-delete"]) && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']){
    $cart_id = $_POST["cartId"];
    $resultDeleteCart = $conn->query("DELETE FROM cart WHERE cart_id=$cart_id");
    header("Location: " . $_SERVER['REQUEST_URI']);
  }
  if (isset($_POST["update_quantity"]) && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $cart_id = $_POST["cartId"];
    $new_quantity = htmlspecialchars($_POST["quantity"], ENT_QUOTES, 'UTF-8');
    if ($new_quantity > 0) {
        // Cập nhật số lượng trong cơ sở dữ liệu
        $conn->query("UPDATE cart SET quantity = $new_quantity WHERE cart_id = $cart_id");
    } else {
        // Nếu số lượng bằng 0 thì xóa sản phẩm khỏi giỏ hàng
        $conn->query("DELETE FROM cart WHERE cart_id = $cart_id");
    }
    // Sau khi cập nhật, load lại trang
    header("Location: " . $_SERVER['REQUEST_URI']);
  }
  if(isset($_POST["submit-order"]) && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']){
    $checkCart = $conn->query("SELECT * FROM cart WHERE user_id = $user_id");
    if ($checkCart->num_rows >= 1) {
      $conn->query("INSERT INTO orders (user_id) VALUES ($user_id)");
      $order_id = $conn->insert_id;

      $resultCartItems = $conn->query("SELECT * FROM cart WHERE user_id = $user_id");
      if($resultCartItems){
        $message = "thành công";
      }
      else{
        $message = "thất bại";
      }
      while ($rowCart = $resultCartItems->fetch_assoc()) {
        $product_id = $rowCart['product_id'];
        $quantity = $rowCart['quantity'];
        $price = $rowCart['price'];
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $quantity, $price)");
      }
      $conn->query("DELETE FROM cart WHERE user_id = $user_id");
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

  <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
    <form method="post">
      <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Giỏ hàng</h2>

        <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
          <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
            <div class="space-y-6">
              <?php
              while($row = $resultCart->fetch_assoc()){
                echo '          
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                  <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                    <a href="http://localhost/webphp/pages/product.php?productId='.$row["product_id"].'" class="shrink-0 md:order-1">
                      <img class="h-20 w-20 dark:hidden" src="'.$row["img_url"].'" alt="imac image" />
                    </a>
                    <div class="flex items-center justify-between md:order-3 md:justify-end">
                      <form method="post">
                        <div class="hidden">
                            <input type="number" name="cartId" id="update-product-id" value="'.$row["cart_id"].'" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="">
                        </div>
                        <div class="flex items-center">
                          <button type="submit" name="update_quantity" id="decrement-button" data-input-counter-decrement="counter-input" class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                            <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                            </svg>
                          </button>
                          <input type="hidden" name="csrf_token" value="'.$_SESSION['csrf_token'].'">
                          <input type="text" name="quantity" id="counter-input" data-input-counter data-input-counter-min="1" data-input-counter-max="'.$row["stock_quantity"].'" class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0 dark:text-white" placeholder="" value="'.$row["quantity"].'" required />
                          <button type="submit" name="update_quantity" id="increment-button" data-input-counter-increment="counter-input" class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                            <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                            </svg>
                          </button>
                        </div>
                        </form>
                      <div class="text-end md:order-4 md:w-32">
                        <p class="text-base font-bold text-gray-900 dark:text-white">'.number_format($row["price"], 0, '', '.').'</p>
                      </div>
                    </div>

                    <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                      <a href="http://localhost/webphp/pages/product.php?productId='.$row["product_id"].'" class="text-base font-medium text-gray-900 hover:underline dark:text-white">'.$row["product_name"].'</a>
                      <form method="post">
                        <div class="flex items-center gap-4">
                          <div class="hidden">
                              <input type="number" name="cartId" id="update-product-id" value="'.$row["cart_id"].'" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="">
                          </div>
                          <input type="hidden" name="csrf_token" value="'.$_SESSION['csrf_token'].'">
                          <button type="submit" name="submit-delete" class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500">
                            <svg class="me-1.5 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                            </svg>
                            Loại bỏ
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>';
              }
              ?>

            </div>
          </div>

          <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">
            <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
              <p class="text-xl font-semibold text-gray-900 dark:text-white">Tóm tắt đơn hàng</p>

              <div class="space-y-4">
                <div class="space-y-2">
                  <dl class="flex items-center justify-between gap-4">
                    <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Giá trị đơn</dt>
                    <?php 
                    $resultSumCart = $conn->query("SELECT SUM(quantity*price) AS total FROM cart WHERE user_id = $user_id");
                    $rowSumCart = $resultSumCart->fetch_assoc();
                    $total = $rowSumCart["total"] ?? 0;
                    echo '<dd class="text-base font-medium text-gray-900 dark:text-white">'. number_format($total, 0, '', '.') .'</dd>'; 
                    ?>
                  </dl>

                  <dl class="flex items-center justify-between gap-4">
                    <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Phí giao hàng</dt>
                    <dd class="text-base font-medium text-gray-900 dark:text-white">0</dd>
                  </dl>

                  <dl class="flex items-center justify-between gap-4">
                    <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Thuế</dt>
                    <dd class="text-base font-medium text-gray-900 dark:text-white">0</dd>
                  </dl>
                </div>

                <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                  <dt class="text-base font-bold text-gray-900 dark:text-white">Tổng</dt>
                  <?php echo '<dd class="text-base font-bold text-gray-900 dark:text-white">'. number_format($total, 0, '', '.') .'</dd>'; ?>
                </dl>
              </div>

              <button type="submit" name="submit-order" class="flex w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Đặt hàng</button>

              <div class="flex items-center justify-center gap-2">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> hoặc </span>
                <a href="http://localhost/webphp/index.php" title="" class="inline-flex items-center gap-2 text-sm font-medium text-primary-700 underline hover:no-underline dark:text-primary-500">
                  Tiếp tục mua hàng
                  <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
  <div id="toastMessage" class="hidden fixed top-16 right-12">
    <?php
        if($message === "thành công"){
            echo '<div id="toast-success" class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <span class="sr-only">Check icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">Đặt hàng thành công.</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-success" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>';
        }
        else if($message === "thất bại"){
            echo '<div id="toast-danger" class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                </svg>
                <span class="sr-only">Error icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">Đặt hàng thất bại.</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-danger" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>';
        }
        else{
            echo "";
        }
    ?>
  </div>
    <?php include("footer.php"); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    <script src="tailwindconfig/cart.tailwind.config.js"></script>
    <script>
      window.onload = function() {
          const message = "<?php echo $message ?>";
          if (message === "thành công" || message === "thất bại") {
              const toast = document.getElementById('toastMessage');
              toast.classList.remove('hidden');
              setTimeout(() => {
                  toast.classList.add('hidden');
                  window.location.href = window.location.pathname;
              }, 2000);
          }
      }
    </script>
</body>
</html>