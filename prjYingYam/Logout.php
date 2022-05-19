<?php
ob_start();
if(isset($_SESSION)){
    $_SESSION['Role_ID'] = null;
}
header("Location: index.php");
?>