<?php
session_start();
require 'config/config.php';
require 'config/common.php';

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

$blogId = $_GET['id'];

$stmtcmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=$blogId");
$stmtcmt->execute();
$cmResult = $stmtcmt->fetchAll();

$auResult =[];
if ($cmResult) {
  foreach ($cmResult as $key => $value) {
    $authorId = $cmResult[$key]['author_id'];
    $stmtau = $pdo->prepare("SELECT * FROM users WHERE id=$authorId");
    $stmtau->execute();
    $auResult[] = $stmtau->fetchAll();
  }
}

if ($_POST) {
  if (empty($_POST['comment'])) {
    if (empty($_POST['comment'])) {
      $cmtError = 'Comment cannot be null';
    }else {
      $comment = $_POST['comment'];

      $stmt = $pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES (:content,:author_id,:post_id)");
      $result = $stmt->execute(
        array(':content'=>$comment, ':author_id'=>$_SESSION['user_id'], ':post_id'=>$blogId)
      );
      if ($result) {
        header('Location: blogdetail.php?id='.$blogId);
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Widgets</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left:0px !important">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <a href="index.php" class="btn btn-default">Go back</a>
            <h1 style="text-align:center"><?php echo escape($result[0]['title']) ?></h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <div class="row">
      <div class="col-md-12">
        <!-- Box Comment -->
        <div class="card card-widget">
          <div class="card-body">
            <img class="img-fluid pad" src="admin/images/<?php echo $result[0]['image']?>"><br><br>
            <p><?php echo escape($result[0]['content']) ?></p><br>
            <h4 style="color: coral;">Comments</h4>
          </div>
          <!-- /.card-body -->

          <div class="card-footer card-comments">
            <div class="card-comment">
              <?php if ($cmResult) { ?>
                <div class="comment-text" style="margin-left: 0px !important">
                  <?php foreach ($cmResult as $key => $value) { ?>
                    <span class="username">
                      <?php echo escape($auResult[$key][0]['name']); ?>
                      <span class="text-muted float-right"><?php echo escape($value['created_at']); ?></span>
                    </span>
                    <?php echo escape($value['content']); ?>
                  <?php
                    }
                  ?>
                </div>

              <?php
                }
              ?>
              <!-- /.comment-text -->
            </div>
            <!-- /.card-comment -->
          </div>
          <!-- /.card-footer -->
          <div class="card-footer">
            <form action="" method="post">
              <input type="hidden" name="token" value="<?=$_SESSION["token"]?>"/>
              <div class="img-push">
                <p style="color:red"><?php echo empty($cmtError) ? '' : '*'.$cmtError ?></p>
                <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
              </div>
            </form>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style="margin-left:0px !important">
    <div class="float-right d-none d-sm-block">
      <b>BlogApp</b>
    </div>
    <strong>Copyright &copy; 2022 <a href="#">THN</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
