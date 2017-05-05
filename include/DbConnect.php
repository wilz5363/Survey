<?php

/**
 * Created by PhpStorm.
 * User: eenah
 * Date: 19/2/2017
 * Time: 12:33 PM
 */
class DbConnect
{
    private $connect;
    function __construct()
    {
    }

    function connect(){
        include_once dirname(__FILE__).'/Config.php';
        $this->connect = new mysqli(DB_HOST,DB_USERNAME, DB_PASSWORD,DB_NAME);
        if(mysqli_connect_errno()){
            echo "Failed to connect to MySql: ".mysqli_connect_error();
        }

        return $this->connect;
    }

}