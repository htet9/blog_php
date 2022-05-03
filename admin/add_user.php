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
  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4) {
    if (empty($_POST['name'])) {
      $nameError = 'Name cannot be null';
    }
    if (empty($_POST['content'])) {
      $emailError = 'Email cannot be null';
    }
    if (empty($_POST['password'])) {
      $passwordError = 'Password cannot be null';
    }
    if(strlen($_POST['password']) < 4) {
      $passwordError = 'Password should be 4 characters at least';
    }
  }else{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (empty($_POST['role'])) {
      $role = 0;
    }else {
      $role = 1;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");

    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      echo "<script>alert('Email duplicated')</script>";
    }else {
      $stmt = $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES (:name,:email,:password,:role)");
      $result = $stmt->execute(
        array(':name'=>$name, ':email'=>$email, ':password'=>$password, 'role'=>$role)
      );
      if ($result) {
        echo "<script>alert('New User Successfully Created.');window.location.href='user.php';</script>";
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
                <form class="" action="add_user.php" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="<?=$_SESSION["_token"]?>"/>
                  <div class="form-group">
                    <label for="">Name</label><p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError ?></p>
                    <input type="text" class="form-control" name="name" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Email</label><p style="color:red"><?php echo empty($emailError) ? '' : '*'.$emailError ?></p>
                    <textarea name="email" class="form-control" rows="8" cols="80"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="">Password</label><p style="color:red"><?php echo empty($passwordError) ? '' : '*'.$passwordError ?></p>
                    <input type="password" name="password" value="">
                  </div>
                  <div class="form-group">
                    <label for="vehicle3">Admin</label><br>
                    <input type="checkbox" name="role" value="1">
                  </div><br>
                  <div class="from-group">
                    <input type="submit" class="btn btn-success" name="" value="Submit">
                    <a href="user.php" class="btn btn-warning">Back</a>
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
