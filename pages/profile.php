<?php include_once("../connect.php");
// Bắt đầu session
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location:http://localhost/webphp/auth/signin.php");
    }

    $message = "";
    $userId = $_SESSION["userId"];
    $username = "";
    $email = "";
    $phone_number = "";
    $imageUrl = "";
    $address = "";
    if(isset($_POST["submit"]) && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']){
        $email = htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
        $phone_number = htmlspecialchars($_POST["phone_number"], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($_POST["address"], ENT_QUOTES, 'UTF-8');
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            // Đường dẫn thư mục lưu ảnh
            $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/webphp/public/img/";
    
            $imageName = basename($_FILES['image']['name']);
            $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    
            $newFileName = pathinfo($imageName, PATHINFO_FILENAME) . "_" . time() . "." . $imageFileType;
            $targetFilePath = $targetDir . $newFileName;
            $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($imageFileType, $allowedTypes)) {
                // Di chuyển file ảnh vào thư mục public/img
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    // Lưu đường dẫn ảnh vào cơ sở dữ liệu
                    $imageUrl = "http://localhost/webphp/public/img/" . $newFileName;
    
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.";
            }
        } else {
            echo "No image selected or there was an error uploading the file.";
        }
        $result = $conn->query("UPDATE users SET email = '$email', phone_number = '$phone_number', address = '$address',img_url = '$imageUrl' WHERE user_id = '$userId'");
        if($result){
            $message = "thành công";
        }
        else{
            $message = "thất bại";
        }
        
    }
    $result = $conn->query("SELECT * FROM users WHERE user_id=$userId");
    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
        $username = $row["username"];
        $email = $row["email"];
        $phone_number = $row["phone_number"];
        $imageUrl = $row["img_url"];
        $address = $row["address"];
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
        <?php
            include("header.php");
            ?>
            <section class="bg-white dark:bg-gray-900">
            <div class="max-w-2xl px-4 py-8 mx-auto lg:py-16">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Thông tin tài khoản</h2>
                <form method="post" enctype="multipart/form-data">
                    <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="sm:col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tên đăng nhập</label>
                            <input type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="<?php echo $username ?>" placeholder="Type product name" disabled>
                        </div>
                        <div class="w-full">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Địa chỉ email</label>
                            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo $email ?>" placeholder="email@gmail.com" />
                        </div>
                        <div class="w-full">
                            <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone number</label>
                            <input type="tel" name="phone_number" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo $phone_number ?>" placeholder="099999999" pattern="[0-9]{9}"  />
                        </div>
                        <div class="w-full">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
                            <input name="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="image" type="file">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Địa chỉ giao hàng</label>
                            <input type="text" id="addre" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="<?php echo $address ?>" placeholder="Số nhà, đường, phường, quận, thành phố..">
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button type="submit" name ="submit" class="text-blue-600 inline-flex items-center hover:text-white border border-blue-600 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-900">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
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
                        <div class="ms-3 text-sm font-normal">Cập nhật thành công.</div>
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
                        <div class="ms-3 text-sm font-normal">Cập nhật thất bại.</div>
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
        <?php
            include("footer.php");
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    <script>
        window.onload = function() {
            const message = "<?php echo $message ?>";
            if (message) {
                const toast = document.getElementById('toastMessage');
                toast.classList.remove('hidden');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 2000);
            }
        }
    </script>
</body>
</html>