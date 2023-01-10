<?php
include_once 'connection.php';

//Delete Record 
if (isset($_GET['del'])) {
    $get_image_data = "SELECT * FROM testimg WHERE id=" . $_GET['del'];
    $result = mysqli_query($cn, $get_image_data);
    $data = mysqli_fetch_array($result);
    $getFilePath = "upload/" . $data['image'];
    if(file_exists($getFilePath)){
    unlink($getFilePath); 
    }
    $sql = "DELETE FROM testimg WHERE id=" . $_GET['del'];
    $data = $cn->query($sql);
    if ($data) {
        echo "Deleted Successfully";
        header("location:index.php?msg=del");
    }
}
if (isset($_POST['btnSave'])) {
    //check for the update records
    if (isset($_GET['id'])) {
        //fetch image records
        $get_image_data = "SELECT * FROM `testimg` WHERE id=" . $_GET['id'];
        $result = mysqli_query($cn, $get_image_data);
        $data = mysqli_fetch_array($result);
        $getFilePath = "upload/" . $data['image'];
        unlink($getFilePath);

        //Set Filename 
        $fileName = $_FILES['fileImg']['name'];

        //Set File Path / Directory 
        $filePath = "upload/" . $_FILES['fileImg']['name'];

        //For update
        if ($fileName != "") {
            $fileName = $_FILES['fileImg']['name'];
            move_uploaded_file($_FILES['fileImg']['tmp_name'], $filePath);
        } else {
            $fileName = $_POST['oldImg'];
        }
        $query = "UPDATE testimg SET image = '" . $fileName  . "' WHERE id=" . $_GET['id'];
        $result = $cn->query($query);
        if (!$result) {
            echo "Sorry, Can't Update the Record, try again later";
        } else {
            header("Location:index.php?msg=upt");
        }
    } else {

        //Set File Path
        $filePath = "upload/" . $_FILES['fileImg']['name'];

        //Name of the File
        $fileName = $_FILES['fileImg']['name'];

        //Image File Path 
        move_uploaded_file($_FILES['fileImg']['tmp_name'], $filePath);

        // Query to Insert Data into Database
        $data = "INSERT into testimg(image) VALUES ('$fileName')";
        $result = $cn->query($data);
        if (!$result) {
            echo "Can't insert record! Try again later";
        } else {
            header("location:index.php?msg=save");
        }
    }

    if (isset($_GET['id'])) {
        $query = "SELECT * FROM testimg WHERE id=" . $_GET['id'];
        $result = $cn->query($query);
        $arr  = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHP Image Upload</title>
</head>

<body>
    <?php
    if (isset($_GET['type'])) {
        if ($_GET['type'] == 'edit' || $_GET['type'] == 'add') {
    ?>
            <!--Form -->
            <div class="data-form" id="data-form">
                <form action="" method="post" enctype="multipart/form-data">
                    <label for='file'>Upload File</label>
                    <input type="file" name="fileImg" id="fileImg">
                    <input type="hidden" name="oldImg" id="oldImg" value="<?php if (isset($_GET['id'])) {
                                                                                echo $arr['id'];
                                                                            } ?>">
                    <br>
                    <br>
                    <input type="submit" name="btnSave" id="btnSave">
                </form>
            </div>
        <?php
        }
    } else {
        ?>
        <a href="index.php?type=add">
            <button style="background-color:blue; color:aliceblue; padding: 10px; margin: 5px; border-color: blue; border-radius:3px;">
                Add new record
            </button>
        </a>
        <br>
        <br>
        <table border="1" style="border-collapse: collapse;">
            <tr>
                <tbody>
                    <th>Sr. No</th>
                    <th>Image</th>
                    <th>Action</th>
                </tbody>
            </tr>
            <?php
            $count = 1;
            $get_data = "SELECT * FROM `testimg` ORDER BY id DESC";
            $result = $cn->query($get_data);
            foreach ($result as $data) {
            ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><img src="upload/<?php echo $data['image'] ?>" height=250 width=320></td>
                    <td>
                        <a href="index.php?type=edit&id=<?php echo $data['id'] ?>">Edit</a>
                        <a href="index.php?del=<?php echo $data['id'] ?>">Delete</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    <?php } ?>

</body>

</html>