<?php
include_once __DIR__."../../interfaces/ConnectionDBInterface.php";

class ConnectDB implements ConnectionDBInterface{
    private $_sqlServer; //Server=172.17.0.1
    private $_port; //14333
    private $_databaseName; //myTest
    private $_userName; //SA
    private $_password; //@C0d3n4m3164**/

    
    public function __construct($pSqlServer,$pPort,$_databaseName,$pUserName,$pPassword) {
        // print_r($pSqlServer);
        $this->_sqlServer = $pSqlServer;
        $this->_port = $pPort;
        $this->_databaseName = $_databaseName;
        $this->_userName = $pUserName;
        $this->_password = $pPassword;
        $this->initConnection();
    }


    private function initConnection(): void 
    {
        try {
            $this ->createObjectToConnectPDO();
            $this ->setConnectionAttribute();

        } catch (Exception $error) {
            echo "No se ha podido conectar a la bd: ". $error -> getMessage();
            die; //no me gusta este die, mas adelante tengo que buscar una forma de terminar el programa si la conexión no es un success
        }
    }


    private function createObjectToConnectPDO(): void 
    {
        $this -> db = new PDO("sqlsrv:".$this->_sqlServer.",".$this->_port.";Database=".$this->_databaseName."","".$this->_userName."","".$this->_password.""); 
    }


    private function setConnectionAttribute(): void
    {
        $this -> db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    public function getPDODB(): PDO
    {
        return $this -> db;
    }


    

    


}
?>
