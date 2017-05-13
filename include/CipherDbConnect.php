<?php

/**
 * Created by PhpStorm.
 * User: chanw
 * Date: 5/13/2017
 * Time: 11:44 AM
 */
class CipherDbConnect
{
    private $connect;
    function __construct()
    {
    }

    function connect(){
        include_once dirname(__FILE__).'/Config.php';
        $this->connect = new mysqli(CipherDB_HOST,CipherDB_USERNAME, CipherDB_PASSWORD,CipherDB_NAME);
        if(mysqli_connect_errno()){
            echo "Failed to connect to MySql: ".mysqli_connect_error();
        }

        return $this->connect;
    }

}