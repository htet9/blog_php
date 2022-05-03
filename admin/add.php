<?php
session_start();
require '../config/config.php';
require '../config/common.php';

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {

  header('Location: login.php');
}
if ($_SESSION['role'] != 1) {
  header('Location: login.php');
}

if ($_POST) {
  if (!hash_equals($_SESSION['_token'], $_POST['_token'])) die();

  if (empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image'])) {
    if (empty($_POST['title'])) {
      $titleError = 'Title cannot be null';
    }
    if (empty($_POST['content'])) {
      $contentError = 'Content cannot be null';
    }
    if (empty($_FILES['image'])) {
      $imageError = 'Image cannot be null';
    }
  }else {
    $file = 'images/'.($_FILES['image']['name']);
    $imageType = pathinfo($file,PATHINFO_EXTENSION);

    if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
      echo "<script>alert('Image must be png,jpg,jpeg')</script>";
    }else {
      $title = $_POST['title'];
      $content = $_POST['content'];
      $image = $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'],$file);

      $stmt = $pdo->prepare("INSERT INTO posts(title,content,author_id,image) VALUES (:title,:content,:author_id,:image)");
      $result = $stmt->execute(
        array(':title'=>$title, ':content'=>$content, ':author_id'=>$_SESSION['user_id'], ':image'=>$image)
      );
      if ($result) {
        echo "<script>alert('New Blog Successfully Created.');window.location.href='index.php';</script>";
      }
    }
  }
}
?>

<?php include('header.php'); ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form class="" action="add.php" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="token" value="<?=$_SESSION["token"]?>"/>
                  <div class="form-group">
                    <label for="">Title</label><p style="color:red"><?php echo empty($titleError) ? '' : '*'.$titleError ?></p>
                    <input type="text" class="form-control" name="title" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Content</label><p style="color:red"><?php echo empty($contentError) ? '' : '*'.$contentError ?></p>
                    <textarea name="content" class="form-control" rows="8" cols="80"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="">Image</label><p style="color:red"><?php echo empty($imageError) ? '' : '*'.$imageError ?></p><br>
                    <input type="file" name="image" value="">
                  </div>
                  <div class="from-group">
                    <input type="submit" class="btn btn-success" name="" value="Submit">
                    <a href="index.php" class="btn btn-warning">Back</a>
                  </div>
                </form>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include('footer.php'); ?>

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
