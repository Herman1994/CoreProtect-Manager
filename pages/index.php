<?php
session_start();
if(!isset($_SESSION['logged_in_cp'])){
    echo "<script>window.location.replace('../../manager/pages/login')</script>";
    exit();
}

include("../php/msql.php");

$target = null;
$limit = null;
$search_type = "co_block";

if(isset($_GET["type"]) || isset($_GET["len"]) || isset($_GET["s"])) {
    $search_type = urldecode($_GET["type"]);
    $limit = $_GET["len"];
    $target = urldecode($_GET["s"]);
    if(!is_numeric($limit)){
        $limit = 50;
    }
    if($limit > 500){
        $limit = 500;
    }
}

$uuid = $_SESSION['uuid_cp'];
$id = $_SESSION['id_cp'];

$web_name = null;
$favicon_url = null;
$profile_plyskin_url = null;

function startsWith ($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

$records = 0;
if(isset($_GET['action']) == "core"){
    echo "Loading...";
    $limit = $_POST['limit_len'];
    $search_type = $_POST['search_type'];
    $search_bar = $_POST['search'];
    $trim = trim($search_bar, " ");
    $target = urlencode($trim);
    $search_type = urlencode($search_type);
    if($limit > 500){
        $limit = 500;
    }
    echo "<script>window.location.replace('index?s=$target&type=$search_type&len=$limit')</script>";
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

$rowids = [];
$user_rows = null;
$sql = "SELECT * FROM co_user WHERE user NOT LIKE '#%'";
$result = mysqli_query($conn, $sql);
//print_r($result);
// output data of each row
if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result))
    {
        array_push($rowids, $row['user']);
    }
    $user_rows = count($rowids);
}

/* // PAGE SYSTEM

$total_rows = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM co_block LIMIT $limit"));
$perpage = 35;
$page_numbers = ceil($total_rows/$perpage);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['first_page'])){
        $page = 1;
        echo "<script>window.location.replace('./')</script>";
    }
    if(isset($_POST['prev_page'])){
        $page--;
        if($page <= 1){
            echo "<script>window.location.replace('./')</script>";
        }
        else {
            echo "<script>window.location.replace('./?p=$page')</script>";
        }
    }
    if(isset($_POST['next_page'])){
        $page++;
        if($page >= $page_numbers){
            $page = $page_numbers;
        }
        echo "<script>window.location.replace('./?p=$page')</script>";
    }
    if(isset($_POST['last_page'])){
        $page = $page_numbers;
        echo "<script>window.location.replace('./?p=$page')</script>";
    }
}

// END OF PAGE SYSTEM */

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <title>Watchdog • Hakuni Manager</title>
  <!-- Favicon -->
  <link href="../assets/img/favicon/logo.png" rel="icon" type="image/png">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <!-- Icons -->
  <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
  <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
  <link href="../assets/css/custom.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="../assets/css/mc-icons.min.css" media="screen" title="no title" charset="utf-8">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"/>
</head>

<body>
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
            <a href="plyprofile.php" class="dropdown-item">
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
              <a class="nav-link active" href="../pages/index"><i class="ni ni-bullet-list-67 text-red"></i>Core Protect
                  </a>
            <ul id="sub"> <!-- class="w3-hide" -->
                <li class="menu-list">
                    <a href='#'>
                        Logs
                    </a>
                </li>
              </ul>
              </li>
          <?php if(in_array("register", $user_arr['perms']) || in_array("*", $user_arr['perms'])){echo '<li class="nav-item">
            <a class="nav-link" href="../pages/register">
              <i class="ni ni-circle-08 text-pink"></i> Register
            </a>
          </li>';} ?>
        </ul>
          <style type="text/css">
              .wrapper {
                  display: inline-block;
              }
              .menu-list {
                  list-style: none;
              }
                li.sub ul {
                  display:none;
                  position: absolute; left: 100%; top:0;}

                li.sub:hover ul{display: block;}
            </style>
        <!-- Divider -->
        <!--<hr class="my-3"> -->
      </div>
    </div>
  </nav>
  <div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="../index">Core Protect</a>
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
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
      <div class="container-fluid">
        <div class="header-body">
            <?php
            $rowids = null;
            $sql = "SELECT * FROM co_block ORDER BY rowid DESC LIMIT 1";
            $result = mysqli_query($conn, $sql);
            //print_r($result);
            // output data of each row
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_array($result))
                {
                    $rowids = $row['rowid'];
                }
            }
            ?>
        </div>
      </div>
    </div>
      <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <div class="row">
              <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Total players registered</h5>
                      <span class="h2 font-weight-bold mb-0"><?= $user_rows ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm"></p>
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Block Changes</h5>
                      <?php $sql_rows = number_format($rowids); ?>
                      <span class="h2 font-weight-bold mb-0"><?= $sql_rows ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                        <i class="fas fa-chart-bar"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </div>
          </div>
          </div>
      </div>
    <div class="container-fluid mt--7">
      <!-- Dark table -->
      <div class="row mt-5">
        <div class="col">
          <div class="card bg-default shadow">
            <div class="card-header bg-transparent border-0">
                <h1 class="text-white mb-0">Core Protect</h1>
                <br>
                <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" action="?action=core"  method="post">
                  <div class="form-group mb-0">
                    <div class="input-group input-group-alternative">
                      <div class="input-group-prepend">
                          <style type="text/css">
                              .search-btn{
                                  cursor: pointer;
                              }
                          </style>
                        <button type="submit" class="search-btn input-group-text"><i class="fas fa-search"></i></button>
                      </div>
                      <input autocomplete="off" autofocus=true class="form-control" name="search" placeholder="Search" type="search" value="<?= $target ?>">
                    </div>
                    &nbsp;
                    <div class="float-right">
                     <label class="wrap">
                        <select name="limit_len" class="dropdown">
                            <?php
                            if($lmit >= 500){ echo '<option value="50">50</option><option value="100">100</option><option value="200">200</option><option selected value="500">500</option>';}
                            elseif($limit >= 200){ echo '<option value="50">50</option><option value="100">100</option><option selected value="200">200</option><option value="500">500</option>';}
                            elseif($limit >= 100){ echo '<option value="50">50</option><option selected value="100">100</option><option value="200">200</option><option value="500">500</option>';}
                            else{ echo '<option selected value="50">50</option><option value="100">100</option><option value="200">200</option><option value="500">500</option>';}
                            ?>
                        </select>
                     </label>
                    </div>
                    <br>
                    &nbsp;
                    <div class="flright top">
                     <label class="wrap">
                        <select name="search_type" class="dropdown">
                            <?php
                            if($search_type == "co_chat, co_command"){
                                echo "<option value='co_block'>Block</option><option value='co_chat, co_command' selected>Chat</option>";
                            }
                            else{
                                echo "<option value='co_block' selected>Block</option><option value='co_chat, co_command'>Chat</option>";
                            }
                            ?>
                        </select>
                    </label>
                   </div>
                  </div>
                </form>
            </div>
            <div class="table-responsive">
              <style type="text/css">
                  .chat-table {
                      table-layout: fixed;
                      width: 100%;
                  }
                  .chat-table td{
                      max-width: 100px;
                      overflow: hidden;
                      text-overflow: ellipsis;
                      white-space: nowrap;
                  }
              </style>
              <table class="<?php if($search_type == "co_chat, co_command"){echo "chat-table ";} ?>table align-items-center table-dark table-flush">
                    <?php
                    if($search_type == "co_chat, co_command"){
                        echo "<thead class='thead-dark'>
                                  <tr>
                                    <th scope='col'>User</th>
                                    <th scope='col'>UUID</th>
                                    <th scope='col'>Message</th>
                                    <th scope='col'>Time (GMT)</th>
                                    <th scope='col'>No</th>
                                  </tr>
                                </thead>";
                    }
                    else{
                        echo "<thead class='thead-dark'>
                                  <tr>
                                    <th scope='col'>User</th>
                                    <th scope='col'>UUID</th>
                                    <th scope='col'>Location</th>
                                    <th scope='col'>Entity</th>
                                    <th scope='col'>Time (GMT)</th>
                                    <th scope='col'>Action</th>
                                    <th scope='col'>No</th>
                                  </tr>
                                </thead>";
                    }
                    $uuid = null;
                    $u_rowid = null;
                    $uname = null;
                    $action = null;
                    $loc = null;
                    $target = mysqli_real_escape_string($conn, $target);
                  
                    if(!empty($target)){
                        $sql = "SELECT * FROM co_user WHERE user LIKE '%$target%' OR uuid = '$target)'";
                        $result = mysqli_query($conn, $sql);
                        //print_r($result);
                        // output data of each row
                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_array($result))
                            {
                                $uuid = $row['uuid'];
                                $u_rowid = $row['rowid'];
                                $uname = $row['user'];
                            }
                        }
                    }
                    
                    $blocks = [];
                    $sql = "SELECT * FROM co_material_map";
                    $result = mysqli_query($conn, $sql);
                    //print_r($result);
                    // output data of each row
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result))
                        {
                            $mat = $row['material'];
                            if(!in_array($mat, $blocks)){
                                array_push($blocks, $mat);
                            }
                        }
                    }
                  
                    $entities = [];
                    $sql = "SELECT * FROM co_entity_map";
                    $result = mysqli_query($conn, $sql);
                    //print_r($result);
                    // output data of each row
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result))
                        {
                            $ent = $row['entity'];
                            if(!in_array($ent, $entities)){
                                array_push($entities, $ent);
                            }
                        }
                    }
                    
                    $players = array();
                    $sql = "SELECT * FROM co_user WHERE user NOT LIKE '#%'";
                    $result = mysqli_query($conn, $sql);
                    //print_r($result);
                    // output data of each row
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result))
                        {
                            $ent = $row['rowid'];
                            if(!in_array($ent, $players)){
                                array_push($players, $ent);
                            }
                        }
                    }
                  
                    if(empty($limit) || $limit < 1){ $limit = 50; }
                    if(!empty($u_rowid)){
                        $sql = "SELECT * FROM $search_type WHERE user = '$u_rowid' ORDER BY rowid DESC LIMIT $limit";
                        if($search_type == "co_chat, co_command"){
                            $sql = "SELECT * FROM (SELECT `rowid`, `time`,`user`,`x`,`y`,`z`,`message`, `CHAT` FROM co_chat WHERE user = '$u_rowid' UNION SELECT `rowid`,`time`,`user`,`x`,`y`,`z`,`message`, `CHAT` FROM co_command WHERE user = '$u_rowid') co_command ORDER BY time DESC LIMIT $limit";
                        }
                        $result = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($result) > 0){
                            $limit = 1;
                            while($row = mysqli_fetch_array($result))
                            {
                                // GET BLOCKS
                                if($search_type == "co_block") {
                                    $block_id = $row['type'];
                                    $block = $blocks[$block_id-1];
                                    $x = $row['x'];
                                    $y = $row['y'];
                                    $z = $row['z'];
                                    $loc = "X: $x, Y: $y, Z: $z";
                                    $action = $row['action'];
                                    $block_class = null;
                                    if($action == 0){
                                        $action = "Broke";
                                        $block_class = "text-killed";
                                    }
                                    elseif($action == 1){
                                        $action = "Placed";
                                        $block_class = "text-success";
                                    }
                                    elseif($action == 2){
                                        $action = "Used";
                                        $block_class = "text-yellow";
                                    }
                                    else{
                                        $block_id = $row['type'];
                                        $block = $entities[$block_id -1];
                                        $action = "Killed";
                                        $block_class = "text-danger";
                                    }
                                    //print_r($entities);
                                    $u_rowid = $row['rowid'];

                                    $unix = $row['time'];
                                    $date = gmdate("H:i \- d/m/Y", $unix);
                                    echo "<tbody>
                                                <tr>
                                                    <th scope='row'>
                                                        <div class='media align-items-center'>
                                                            <div class='media-body'>
                                                                <span class='mb-0 {$block_class}'>{$uname}</span>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <td>
                                                        <span class='mb-0 {$block_class}'>{$uuid}</span>
                                                    </td>
                                                    <td>
                                                        <span class='badge badge-dot mr-4'>
                                                            <span class='mb-0 {$block_class}'>{$loc}</span>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class='avatar-group'>
                                                            <span class='mb-0 {$block_class}'>{$block}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class='d-flex align-items-center'>
                                                            <span class='mr-2 {$block_class}'>{$date}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class='d-flex align-items-center'>
                                                            <span class='mr-2 {$block_class}'>{$action}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class='d-flex align-items-center'>
                                                            <span class='mr-2 text-sm'>{$limit}</span>
                                                            <div>
                                                                <span class='mb-0 text-sm'></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>";
                                }

                                // GET CHAT
                                if($search_type == "co_chat, co_command"){
                                    $message = $row['message'];
                                    
                                    $uname = null;
                                    $uuid = null;
                                    
                                    $target_id = $row['user'];
                                    
                                    $cou_sql = "SELECT * FROM co_user WHERE rowid = '$target_id'";
                                    $cou_result = mysqli_query($conn, $cou_sql);
                                    if(mysqli_num_rows($cou_result) > 0){
                                        while($cou_row = mysqli_fetch_array($cou_result))
                                        {
                                            $uname = $cou_row['user'];
                                            $uuid = $cou_row['uuid'];
                                        }
                                    }
                                    
                                    $is_chat = $row['CHAT'];
                                    $block_class = null;
                                    if($is_chat){
                                        $block_class = "text-killed";
                                    }
                                    else{
                                        $block_class = "text-yellow";
                                    }
                                    
                                    $unix = $row['time'];
                                    $date = gmdate("H:i \- d/m/Y", $unix);
                                    echo "<tbody>
                                                <tr>
                                                    <th scope='row'>
                                                        <div class='media align-items-center'>
                                                            <div class='media-body'>
                                                                <span class='mb-0 {$block_class}'>{$uname}</span>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <td>
                                                        <span class='mb-0 {$block_class}'>{$uuid}</span>
                                                    </td>
                                                    <td>
                                                        <span class='badge badge-dot mr-4'>
                                                            <span class='mb-0 {$block_class}'>{$message}</span>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class='d-flex align-items-center'>
                                                            <span class='mr-2 {$block_class}'>{$date}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class='d-flex align-items-center'>
                                                            <span class='mr-2 {$block_class}'>{$limit}</span>
                                                            <div>
                                                                <span class='mb-0 text-sm'></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>";
                                }
                                $limit = $limit + 1;
                            }
                        }
                    }
                    else{
                        $sql = "SELECT * FROM $search_type WHERE user IN (" . implode(',', $players) . ") ORDER BY rowid DESC LIMIT $limit";
                        if($search_type == "co_chat, co_command"){
                            $sql = "SELECT * FROM (SELECT `rowid`, `time`,`user`,`x`,`y`,`z`,`message`, `CHAT` FROM co_chat UNION SELECT `rowid`,`time`,`user`,`x`,`y`,`z`,`message`, `CHAT` FROM co_command) co_command ORDER BY time DESC LIMIT $limit";
                        }
                        
                        $result = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($result) > 0){
                            $limit = 1;
                            while($row = mysqli_fetch_array($result))
                            {
                                // GET BLOCKS
                                if($search_type == "co_block") {
                                    $block_id = $row['type'];
                                    $block = $blocks[$block_id-1];
                                    $x = $row['x'];
                                    $y = $row['y'];
                                    $z = $row['z'];
                                    $loc = "X: $x, Y: $y, Z: $z";
                                    $action = $row['action'];
                                    $block_class = null;
                                    if($action == 0){
                                        $action = "Broke";
                                        $block_class = "text-killed";
                                    }
                                    elseif($action == 1){
                                        $action = "Placed";
                                        $block_class = "text-success";
                                    }
                                    elseif($action == 2){
                                        $action = "Used";
                                        $block_class = "text-yellow";
                                    }
                                    else{
                                        $block_id = $row['type'];
                                        $block = $entities[$block_id -1];
                                        $action = "Killed";
                                        $block_class = "text-danger";
                                    }
                                    //print_r($entities);
                                    $target_id = $row['user'];
                                    
                                    $uname = null;
                                    $uuid = null;
                                    
                                    $cou_sql = "SELECT * FROM co_user WHERE rowid = '$target_id'";
                                    $cou_result = mysqli_query($conn, $cou_sql);
                                    if(mysqli_num_rows($cou_result) > 0){
                                        while($cou_row = mysqli_fetch_array($cou_result))
                                        {
                                            $uname = $cou_row['user'];
                                            $uuid = $cou_row['uuid'];
                                        }
                                    }
                                    
                                    $unix = $row['time'];
                                    $date = gmdate("H:i \- d/m/Y", $unix);
                                    echo "<tbody>
                                                <tr>
                                                    <th scope='row'>
                                                        <div class='media align-items-center'>
                                                            <div class='media-body'>
                                                                <span class='mb-0 {$block_class}'>{$uname}</span>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <td>
                                                        <span class='mb-0 {$block_class}'>{$uuid}</span>
                                                    </td>
                                                    <td>
                                                        <span class='badge badge-dot mr-4'>
                                                            <span class='mb-0 {$block_class}'>{$loc}</span>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class='avatar-group'>
                                                            <span class='mb-0 {$block_class}'>{$block}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class='d-flex align-items-center'>
                                                            <span class='mr-2 {$block_class}'>{$date}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class='d-flex align-items-center'>
                                                            <span class='mr-2 {$block_class}'>{$action}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class='d-flex align-items-center'>
                                                            <span class='mr-2 text-sm'>{$limit}</span>
                                                            <div>
                                                                <span class='mb-0 text-sm'></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>";
                                }

                                // GET CHAT
                                if($search_type == "co_chat, co_command"){
                                    $message = $row['message'];
                                    
                                    $uname = null;
                                    $uuid = null;
                                    
                                    $target_id = $row['user'];
                                    
                                    $cou_sql = "SELECT * FROM co_user WHERE rowid = '$target_id'";
                                    $cou_result = mysqli_query($conn, $cou_sql);
                                    if(mysqli_num_rows($cou_result) > 0){
                                        while($cou_row = mysqli_fetch_array($cou_result))
                                        {
                                            $uname = $cou_row['user'];
                                            $uuid = $cou_row['uuid'];
                                        }
                                    }
                                    
                                    $is_chat = $row['CHAT'];
                                    $block_class = null;
                                    if($is_chat){
                                        $block_class = "text-killed";
                                    }
                                    else{
                                        $block_class = "text-yellow";
                                    }
                                    
                                    $unix = $row['time'];
                                    $date = gmdate("H:i \- d/m/Y", $unix);
                                    echo "<tbody>
                                                <tr>
                                                    <th scope='row'>
                                                        <div class='media align-items-center'>
                                                            <div class='media-body'>
                                                                <span class='mb-0 {$block_class}'>{$uname}</span>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <td>
                                                        <span class='mb-0 {$block_class}'>{$uuid}</span>
                                                    </td>
                                                    <td>
                                                        <span class='badge badge-dot mr-4'>
                                                            <span class='mb-0 {$block_class}'>{$message}</span>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class='d-flex align-items-center'>
                                                            <span class='mr-2 {$block_class}'>{$date}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class='d-flex align-items-center'>
                                                            <span class='mr-2 {$block_class}'>{$limit}</span>
                                                            <div>
                                                                <span class='mb-0 text-sm'></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>";
                                }
                                $limit = $limit + 1;
                            }
                        }
                    }
                    /* close result set */
                    mysqli_free_result($result);
                    ?>
              </table>
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
      

    function dropDown(id) {
  var x = document.getElementById(id);
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else {
    x.className = x.className.replace(" w3-show", "");
  }
}
  </script>
</body>

</html>