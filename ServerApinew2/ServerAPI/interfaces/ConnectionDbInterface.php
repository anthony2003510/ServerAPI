<?php


/**
 *  CONSTRUCTOR PARAMS
 * ----------------------------------
 * $this->_sqlServer = $pSqlServer;
 * $this->_port = $pPort;
 * $this->_databaseName = $_databaseName;
 * $this->_userName = $pUserName;
 * $this->_password = $pPassword;
 * 
 * 
 * PRIVATE FUNCTION
 * ----------------------------------
 * createObjectToConnectPDO(): string;
 * connectDB():void;
 * setConnectionAttribute(): void;
 * 
 */
interface ConnectionDbInterface {
    public function getPDODB(): PDO;
    
}


?>