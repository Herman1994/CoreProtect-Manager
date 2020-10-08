<?php
session_start();
unset($_SESSION["logged_in_cp"]);
unset($_SESSION["uname_cp"]);
unset($_SESSION["uuid_cp"]);
unset($_SESSION["mcname_cp"]);
unset($_SESSION["id_cp"]);
echo "<script>window.location.replace('../../hmanager/pages/login')</script>";
exit();
?>