<?php include("../connect.php")?>
<?php
    session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    if (isset($_SESSION['username'])) {
        if($_SESSION["role"] =="admin"){
            header("Location:http://localhost/webphp/admin/index.php");
            exit();
        }
        else{
            header("Location:../index.php");
            exit();
        }
    }
    $error ="";
    $username = "";
    $password = "";
    if(isset($_POST["submit"])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        $result = $conn->query("SELECT * FROM users WHERE username='$username'");
        
        if($result->num_rows == 0){
            $error = "<span class='font-medium'>Lỗi: </span> tài khoản hoặc mật khẩu không chính xác";
        }
        else if ($result->num_rows == 1){
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['userId'] = $row['user_id'];
                if($row["role"] == "admin"){
                    $_SESSION['role'] = "admin";
                    header("Location:http://localhost/webphp/admin/index.php");
                }
                else{
                    $_SESSION["role"] = "user";
                    header("Location:../index.php");
                }
                exit();
            } else {
                $error = "<span class='font-medium'>Lỗi: </span> tài khoản hoặc mật khẩu không chính xác";
            }
        }
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
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
        <section class="bg-gray-50 dark:bg-gray-900 h-full">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 ">
                <div>
                    <div class="w-full lg:max-w-xl p-6 space-y-8 sm:p-8 bg-white rounded-lg shadow-xl dark:bg-gray-800">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Đăng nhập ShopX
                        </h2>
                        <form method="post" class="mt-8 space-y-6" >
                            <div>
                                <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên đăng nhập</label>
                                <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required />
                            </div>
                            <div>
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mật khẩu</label>
                                <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                <p id="standard_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><?php echo  $error ?></p>
                            </div>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="remember" aria-describedby="remember" name="remember" type="checkbox" class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600" required />
                                </div>
                                <div class="ms-3 text-sm">
                                <label for="remember" class="font-medium text-gray-500 dark:text-gray-400">Nhớ trình duyệt</label>
                                </div>
                            </div>
                            <button type="submit" name="submit" class="w-full px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Đăng nhập</button>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                Chưa có tài khoản? <a href="signup.php" class="cursor-pointer text-blue-600 hover:underline dark:text-blue-500">Tạo ngay</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>