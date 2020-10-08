<?php
session_start();
$id = $_SESSION["id_cp"];
$logged_in = $_SESSION["logged_in_cp"];
$date = date("Y-m-d h:i:sa");
$unix_time = strtotime($date);

include("../php/msql.php");

$user_arr = [];
if(isset($logged_in) == true) {
    $sql = "SELECT * FROM man_users WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    //print_r($result);
    // output data of each row
    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_array($result))
        {
            $perms = $row["permissions"];
        }
    }
    $perms = (explode(",", strtolower($perms)));
    $user_arr = [
        'perms' => $perms
    ];
}
else{
    echo "<script>window.location.replace('../../hmanager/')</script>";
    exit();
}
if(in_array("register", $user_arr['perms']) || in_array("*", $user_arr['perms'])){
    // Has access and the right permissions
}
else{
   echo "<script>window.location.replace('../../hmanager/')</script>";
    exit(); 
}

if(isset($_GET['action']) == "reg"){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $link = "https://api.mojang.com/users/profiles/minecraft/$name?at=$unix_time";
    $obj = json_decode(file_get_contents($link), true);
    $uuid = $obj['id'];
    
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $pw = '';
    for ($i = 0; $i < 11; $i++) {
        $pw .= $characters[rand(0, $charactersLength - 1)];
    }
    
    $pw_md5 = md5($pw);
    $pw_crypt = crypt($pw_md5, "c2¤pt_*md5\$£12{$pw_md5}^666¨/843mSH\"DWVs2");
    $sql = "SELECT * FROM man_users WHERE email = '$email' OR uuid = '$uuid'";
    $result = mysqli_query($conn, $sql);
    //print_r($result);
    if(!mysqli_num_rows($result)) {
        $sql = "INSERT INTO man_users (uuid, email, password) VALUES ('" . mysqli_real_escape_string($conn, $uuid) . "', '" . mysqli_real_escape_string($conn, $email) . "', '" . $pw_crypt . "')";
        if (mysqli_query($conn, $sql)) {
            $msg = "<center><div class='box-small success mb-3'><h3>Password: $pw</h3></div></center>";
        }
        else {
            $msg = "<center><div class='box-small fail mb-3'><h3 class='text-white'>Error: " . $sql . "<br>" . $conn->error . "</h3></div></center>";
        }
    }
    else{
        $msg = "<center><div class='box-small fail mb-3'><h3 class='text-white'>User already exists</h3></div></center>";
    }
}
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

<body class="bg-default">
  <div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-horizontal navbar-expand-md navbar-dark">
      <div class="container px-4">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-collapse-main">
          <!-- Collapse header -->
          <div class="navbar-collapse-header d-md-none">
            <div class="row">
              <div class="col-6 collapse-close">
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                  <span></span>
                  <span></span>
                </button>
              </div>
            </div>
          </div>
          <!-- Navbar items -->
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="../index">
                <i class="ni ni-planet"></i>
                <span class="nav-link-inner--text">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="../pages/register">
                <i class="ni ni-circle-08"></i>
                <span class="nav-link-inner--text">Register</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="../pages/login">
                <i class="ni ni-key-25"></i>
                <span class="nav-link-inner--text">Login</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="../pages/plyprofile">
                <i class="ni ni-single-02"></i>
                <span class="nav-link-inner--text">Profile</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Header -->
    <div class="header bg-gradient-primary py-7 py-lg-8">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
              <h1 class="text-white">Watchdog Admin Manager</h1>
              <p class="text-lead text-light">Register an admin user.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <!-- Table -->
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
          <div class="card bg-secondary shadow border-0">
            <div class="card-header bg-transparent pb-5">
            <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center text-muted mb-4">
                <small>Watchdog Admin Manager</small>
              </div>
              <form action="?action=reg" method="post">
                <div class="form-group">
                  <div class="input-group input-group-alternative mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni"><img src="../assets/img/icons/iconfinder_user-01_186382.png" width="20" height="20"></i></span>
                    </div>
                    <input class="form-control" placeholder="MC name" name="name" type="text">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-alternative mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni"><img src="../assets/img/icons/mail.png" width="22" height="22"></i></span>
                    </div>
                    <input class="form-control" placeholder="Email" name="email" type="email">
                  </div>
                </div>
                  <?php if(!empty($msg)){echo $msg;} ?>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary mt-4">Create account</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer -->
  <footer class="py-5">
    <div class="container">
      <div class="row align-items-center justify-content-xl-between">
        <div class="col-xl-6">
          <div class="copyright text-center text-xl-left text-muted">
            &copy; 2019 <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Hanakacraft</a>
          </div>
        </div>
        <div class="col-xl-6">
          <ul class="nav nav-footer justify-content-center justify-content-xl-end">
            <li class="nav-item">
              <a href="https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md" class="nav-link" target="_blank">MIT License</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
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