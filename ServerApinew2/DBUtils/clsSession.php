<?php
class clsSession
{
    private $cid;
    private $data;

    private $bddConnection;
    private $bdd;
    private $controler;
    
    function __construct(string $cid)
    {
        $this->DoConnection();
        $this->cid = $cid;  
    }

    

    public function DoConnection() : void
    {
        $this->bddConnection = new ConnectDB("Aqui se tendrán que poner los datos para hacer la conexion a la base de datos");
        $this->bdd = $this->bddConnection->getPDODB();
        $this->DBcontroler = new clsControllerDB($this->bdd);
    }
    public function Logout():bool
    {
        $this->DBcontroler->prepareProcedure("sp_sap_user_logout",[$this->cid]);
        $this->data = $this->DBcontroler->returnedStringXMLValue();
        return true;
    }

    public function getData() : string
    {
        return $this->data;
    }
    

}

?>