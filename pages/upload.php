<?php
if (isset($_POST['submit'])) {
    // Kết nối cơ sở dữ liệu
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
                $imageUrl = "http://localhost/webphp/" . $targetFilePath;

            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.";
        }
    } else {
        echo "No image selected or there was an error uploading the file.";
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Image</title>
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="image">Select image to upload:</label>
        <input type="file" name="image" id="image">
        <input type="submit" name="submit" value="Upload Image">
    </form>
</body>
</html>
