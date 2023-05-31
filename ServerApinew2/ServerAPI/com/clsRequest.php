<?php

class clsRequest{
    private $obj_xmlutil;
   

    function __construct(){
       
    }


    function Exists(string $param): bool
    {
        if (isset($_GET[$param])){
            return true;
        }else{
            return false;
        }
    }

    
    function GetValue(string $param) : string
    {
        return $_GET[$param];
    }
}




?>