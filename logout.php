<?php
/**
 * Created by PhpStorm.
 * User: eenah
 * Date: 25/2/2017
 * Time: 4:23 PM
 */

//this php file is jz meant for logout nia
session_start();
if(isset($_SESSION['user'])){
    unset($_SESSION['user']);
    session_destroy();
}

header("Location: login.php");