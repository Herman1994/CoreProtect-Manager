<?php
session_start();
if(!isset($_SESSION['logged_in_cp'])){
    echo "<script>window.location.replace('../../hmanager/pages/login')</script>";
    exit();
}

include("../php/msql.php");

$uuid = $_SESSION['uuid_cp'];
$id = $_SESSION['id_cp'];
$sql = "SELECT * FROM man_users WHERE id='$id'";
$result = mysqli_query($conn, $sql);
//print_r($result);
// output data of each row
$email = null;
$cur_pw = null;
$msg = null;

if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result))
    {
        $email = $row['email'];
        $cur_pw = $row['password'];
    }
}
if(isset($_GET["action"]) == "save"){
    $new_email = $_POST['email'];
    
    $old_pw = md5($_POST['old_pw']);
    $old_crypt = crypt($old_pw, "c2¤pt_*md5\$£12{$old_pw}^666¨/843mSH\"DWVs2");
    
    $new_pw = md5($_POST['new_pw']);
    $new_crypt = crypt($new_pw, "c2¤pt_*md5\$£12{$new_pw}^666¨/843mSH\"DWVs2");
    
    $conf_new_pw = md5($_POST['conf_new_pw']);
    $conf_new_crypt = crypt($conf_new_pw, "c2¤pt_*md5\$£12{$conf_new_pw}^666¨/843mSH\"DWVs2");
    
    if(/*$old_crypt == $cur_pw &&*/ $new_crypt == $conf_new_crypt) {
        $sql = "UPDATE man_users SET email='" . mysqli_real_escape_string($conn, $new_email) . "', password='" . mysqli_real_escape_string($conn, $new_crypt) . "' WHERE email = '$email'";
        if (mysqli_query($conn, $sql)) {
            $msg = "<center><div class='box-small success mb-3'><h3 class='text-white'>Profile Saved</h3></div></center>";
        }
        else {
            $msg = "<center><div class='box-small fail mb-3'><h3 class='text-white'>Error: " . $sql . "<br>" . $conn->error . "</h3></div></center>";
        }
    }
    else{
        $msg = "<center><div class='box-small fail mb-3'><h3 class='text-white'>Passwords does not match.</h3></div></center>";
    }
}

$hu_sql = "SELECT * FROM man_users WHERE id = '$id'";
$hu_result = mysqli_query($conn, $hu_sql); 
// print_r($result);
// output data of each row
if(mysqli_num_rows($hu_result) > 0) {
    while($hu_row = mysqli_fetch_array($hu_result))
    {
        $perms = $hu_row["permissions"];
    }
}
$perms = (explode(",", strtolower($perms)));
$user_arr = [
    'perms' => $perms
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Watchdog • HC Manager</title>
  <!-- Favicon -->
  <link href="../assets/img/brand/favicon.png" rel="icon" type="image/png">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <!-- Icons -->
  <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
  <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css">
</head>

<body class="">
  <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- User -->
      <ul class="nav align-items-center d-md-none">
        <li class="nav-item dropdown">
          <a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-bell-55"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img alt="Image placeholder" src="../assets/img/theme/team-1-800x800.jpg">
              </span>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <div class=" dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome!</h6>
            </div>
            <a href="./pages/plyprofile" class="dropdown-item">
              <i class="ni ni-single-02"></i>
              <span>My Profile</span>
            </a>
            <a href="./pages/plyprofile" class="dropdown-item">
              <i class="ni ni-settings-gear-65"></i>
              <span>Settings</span>
            </a>
            <a href="./pages/plyprofile" class="dropdown-item">
              <i class="ni ni-calendar-grid-58"></i>
              <span>Activity</span>
            </a>
            <a href="./pages/plyprofile" class="dropdown-item">
              <i class="ni ni-support-16"></i>
              <span>Support</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-user-run"></i>
              <span>Logout</span>
            </a>
          </div>
        </li>
      </ul>
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="../index">
                <img src="../assets/img/brand/blue.png">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <!-- Form -->
        <form class="mt-4 mb-3 d-md-none">
          <div class="input-group input-group-rounded input-group-merge">
            <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Search" aria-label="Search">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <span class="fa fa-search"></span>
              </div>
            </div>
          </div>
        </form>
        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item active">
          <a class=" nav-link " href=" ../index"> <i class="ni ni-tv-2 text-primary"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="../pages/icons">
              <i class="ni ni-planet text-blue"></i> Icons
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="../pages/index">
              <i class="ni ni-bullet-list-67 text-red"></i> Core Protect
            </a>
          </li>
          <?php if(in_array("register", $user_arr['perms']) || in_array("*", $user_arr['perms'])){echo '<li class="nav-item">
            <a class="nav-link" href="./pages/register">
              <i class="ni ni-circle-08 text-pink"></i> Register
            </a>
          </li>';} ?>
        </ul>
        <!-- Divider -->
        <hr class="my-3">
      </div>
    </div>
  </nav>
  <div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="../index">Settings</a>
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                  <img alt="MC character head" class="pic-profile" src="<?php echo "http://cravatar.eu/avatar/$uuid/200.png"; ?>">
                </span>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
              <a href="../pages/plyprofile" class="dropdown-item">
                <i class="ni ni-single-02"></i>
                <span>Settings</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="../php/logout.php" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span>Logout</span>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- End Navbar -->
    <!-- Header -->
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 600px; background-image: url(../assets/img/theme/plyprofile-cover.jpg); background-size: cover; background-position: center top;">
      <!-- Mask -->
      <span class="mask bg-gradient-default opacity-8"></span>
      <!-- Header container -->
      <div class="container-fluid d-flex align-items-center">
        <div class="row">
          <div class="col-lg-7">
              <h1 class="text-white display-2">Hello!</h1>
              <p class="text-white display-4">&amp; welcome to your account page.</p>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--7">
      <div class="row">
        <div class="col-xl-8 order-xl-1">
          <div class="card bg-secondary shadow">
            <div class="card-header bg-white border-0">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">My account</h3>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form action="?action=save" method="post">
                <h6 class="heading-small text-muted mb-4">User information</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-username">UUID</label>
                        <input type="text" disabled id="input-username" class="form-control form-control-alternative" value="<?= $uuid ?>">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-email">Email address</label>
                        <input autocomplete="email" type="email" name="email" class="form-control form-control-alternative" placeholder="example@mail.com" required value="<?= $email ?>">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-old-pw">Old Password</label>
                        <input type="password" name="old_pw" class="form-control form-control-alternative" required placeholder="Old Password">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-new-pw">New Password</label>
                        <input type="password" name="new_pw" class="form-control form-control-alternative" required placeholder="New Password">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-confirm-new-pw">Confirm New Password</label>
                        <input type="password" name="conf_new_pw" class="form-control form-control-alternative" required placeholder="Confirm New Password">
                      </div>
                    </div>
                  </div>
                    <?= $msg ?>
                </div>
                <input class="btn btn-info" type="submit" value="Save Changes">
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <footer class="footer">
        <div class="row align-items-center justify-content-xl-between">
          <div class="col-xl-6">
            <div class="copyright text-center text-xl-left text-muted">
              &copy; 2018 <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Creative Tim</a>
            </div>
          </div>
          <div class="col-xl-6">
            <ul class="nav nav-footer justify-content-center justify-content-xl-end">
              <li class="nav-item">
                <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
              </li>
              <li class="nav-item">
                <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
              </li>
              <li class="nav-item">
                <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
              </li>
              <li class="nav-item">
                <a href="https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md" class="nav-link" target="_blank">MIT License</a>
              </li>
            </ul>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <!--   Core   -->
  <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
  <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!--   Optional JS   -->
  <!--   Argon JS   -->
  <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
  <script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>
  <script>
    window.TrackJS &&
      TrackJS.install({
        token: "ee6fab19c5a04ac1a32a645abde4613a",
        application: "argon-dashboard-free"
      });
  </script>
</body>

</html>