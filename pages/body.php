<?php
    include_once("connect.php");
    include_once("pages/list.php");

    $category_search = null;
    $search ="";
    $sortBy = "";
    if(isset($_POST["submit-search"]) && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']){
        $category_search = $_POST["category_search"];
        $search = htmlspecialchars($_POST["search"], ENT_QUOTES, 'UTF-8');
        $sortBy = $_POST["list"];
        if($category_search != 0){
            if($sortBy == "Tăng dần"){
                $resultProduct = $conn->query("SELECT * FROM products WHERE category_id = $category_search AND product_name like '%$search%' ORDER BY price ASC LIMIT $start,  $row_per_page");
            }
            else{
                $resultProduct = $conn->query("SELECT * FROM products WHERE category_id = $category_search AND product_name like '%$search%' ORDER BY price DESC LIMIT $start,  $row_per_page");
            }
        }
        else{
            if($sortBy == "Tăng dần"){
                $resultProduct = $conn->query("SELECT * FROM products WHERE product_name like '%$search%' ORDER BY price ASC LIMIT $start,  $row_per_page");
            }
            else{
                $resultProduct = $conn->query("SELECT * FROM products WHERE product_name like '%$search%' ORDER BY price DESC LIMIT $start,  $row_per_page");
            }
        }
    }
?>

<form method="post" class="body mt-12 w-full mx-auto h-auto" >
    <div class="max-w-lg mx-auto">
        <div class="flex">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <select id="dropdown-select" name="category_search" class="block w-2/5 py-2.5 px-4 text-sm font-medium text-gray-900 bg-gray-100 border border-gray-300 rounded-l-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600">
                <option value="0">Tất cả mặt hàng</option>
                <?php
                    $resultCategory = $conn->query("SELECT * FROM categories");
                    while($row = $resultCategory->fetch_assoc()){
                        echo '<option value="'.$row["category_id"].'">'.$row["category_name"].'</option>';
                    }
                ?>
            </select>
            <div class="relative w-full">
                <input type="search" name="search" id="search-dropdown" class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500" placeholder="Tìm kiếm quần áo, đồ gia dụng, thiết bị điện tử..."  />
                <button type="submit" name="submit-search" class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
            </div>
        </div>
    </div>
    <div class ="flex flex-row mt-12">
        <ul class="items-center w-36 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white mx-4">
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class="flex items-center ps-3">
                    <input id="react-checkbox-list" type="radio" name="list" value="Tăng dần" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                    <label for="react-checkbox-list" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Giá tăng dần</label>
                </div>
            </li>
            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class="flex items-center ps-3">
                    <input id="angular-checkbox-list" type="radio" name="list" value="Giảm dần" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                    <label for="angular-checkbox-list" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Giá giảm dần</label>
                </div>
            </li>
        </ul>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php 
                while($row = $resultProduct->fetch_assoc()){
                    echo '<div class="w-64 max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <div  class="min-h-64">
                                 <a href="http://localhost/webphp/pages/product.php?productId='.$row["product_id"].'">
                                    <img class="p-4 rounded-t-lg max-w-full h-auto" src="'.$row["img_url"].'" alt="product image" />
                                </a>
                            </div>
                            <div class="px-5 pb-5">
                                <a href="http://localhost/webphp/pages/product.php?productId='.$row["product_id"].'">
                                    <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">'.$row["product_name"].'</h5>
                                </a>
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold text-gray-900 dark:text-white">'.number_format($row["price"], 0, '', '.').'</span>
                                    <a href="http://localhost/webphp/pages/product.php?productId='.$row["product_id"].'" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-sm rounded-lg text-sm px-3 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Xem thêm</a>
                                </div>
                            </div>
                        </div>';
                }
            ?>
        </div>
        
    </div>
    <nav class="flex flex-col md:flex-row justify-between mx-36 items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
        <?php 
        if(!isset($_GET['pages'])){
            $page = 1;
        }else{
            $page = $_GET['pages'];
        }
        ?>
        <span class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">Showing <span class="font-semibold text-gray-900 dark:text-white"><?php echo $page ?></span> of <span class="font-semibold text-gray-900 dark:text-white"><?php echo $pages ?></span></span>
        <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
            <li>
                <?php
                    if(isset($_GET['pages']) && $_GET['pages'] > 1){
                        ?>
                            <a href="?pages=<?php echo $_GET['pages'] - 1 ?>" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                        <?php
                    }else{
                        ?>
                            <a class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                        <?php
                    }
                ?>
            </li>
            <?php
                for($i = 1; $i <= $pages; $i++){
                    if(isset($_GET['pages']) && $_GET['pages'] == $i){
                        ?>
                            <a href="?pages=<?php echo $i?>" aria-current="page" class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white"><?php echo $i ?></a>
                        <?php
                    }else{
                        ?>
                            <a href="?pages=<?php echo $i?>" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"><?php echo $i ?></a>
                        <?php
                    }
                    ?>
                    
                    <?php
                }
            ?>
                <?php
                if(!isset($_GET['pages'])) {
                ?>
                    <a href="?pages=2" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                <?php
                } else {
                    if($_GET['pages'] >= $pages) {
                ?>
                    <a class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                <?php
                    } else {
                ?>
                        <a href="?pages=<?php echo $_GET['pages'] + 1 ?>" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                <?php
                    }
                }
                ?>
            </li>
        </ul>
    </nav>
</form>