<?php
require_once 'init.php';

if (!$currentUser) {
  header('Location: index.php');
  exit();
}

$fullname = $currentUser['fullname'];
$phone = $currentUser['phone'];
$success = true;

// Kiểm tra người dùng có nhập tên
if (isset($_POST['fullname'])) {
  if (strlen($_POST['fullname']) > 0) {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $currentUser['fullname'] = $fullname;
    $currentUser['phone'] = $phone;
    updateUser($currentUser);
  } else {
    $success = false;
  }

  if(isset($_FILES['avatar'])) {
    $fileName = $_FILES['avatar']['name'];
    $fileSize = $_FILES['avatar']['size'];
    $fileTemp = $_FILES['avatar']['tmp_name'];
    $fileSave = 'uploads/avatars/' . $currentUser['id'] . '.jpg';
    // userid.jpg
    $result = move_uploaded_file($fileTemp, $fileSave);
    if (!$result) {
      $success = false;
    } else {
      $newImage = resizeImage($fileSave, 250, 250);
      imagejpeg($newImage, $fileSave);
      $currentUser['hasAvatar'] = 1;
      updateUser($currentUser);
    }
  }
}

?>
<?php include 'header.php' ?>
<h1>Profile</h1>
<?php if (!$success) : ?>
<div class="alert alert-danger" role="alert">
Please insert all information
</div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data">
  <div class="form-group">
    <label for="fullname">Full name</label>
    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full name" value="<?php echo $fullname ?>">
  </div>
  <div class="form-group">
    <label for="phone">Phone Number</label>
    <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone number" value="<?php echo $phone ?>">
  </div>
  <div class="form-group">
    <label for="avatar">Avatars</label>
    <input type="file" class="form-control-file" id="avatar" name="Avatar">
  </div>
  <button type="submit" class="btn btn-primary">Update</button>
</form>
<?php include 'footer.php' ?>