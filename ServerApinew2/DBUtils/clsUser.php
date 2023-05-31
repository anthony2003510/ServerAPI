<?php
class clsUser
{
    private string $username;
    private string $pwd;
    private string $email;
    private $errUser;
    private $DBcontroler;
    private $data;
    private $XMLdata;
    private $cid;
    private $bddConnection;
    private $bdd;
    private $XMLUtils;
    function __construct(string $username = "",string $email ="", string $pwd="")
    {
        $this->DoConnection();
        $this->XMLUtils = new clsXMLUtils;
        $this->username = $username;
        $this->email = $email;
        $this->pwd = $pwd;     
    }

    public function DoConnection() : void
    {
        $this->bddConnection = new ConnectDB("Aqui se tendrán que poner los datos para hacer la conexion a la base de datos");
        $this->bdd = $this->bddConnection->getPDODB();
        $this->DBcontroler = new clsControllerDB($this->bdd);
    }

    public function saveSessionCid(Object $XMLobjUser): string
    {
        
        $this->XMLUtils->ApplyXpathToObj('conn_guid',$XMLobjUser);
        $cid = $this->XMLUtils->getResult();
        $cid =  $this->DBcontroler->setStringVal($cid);

        return $cid; 
    }

    public function saveSessionErr(Object $XMLobjUser):string
    {
        $this->XMLUtils->ApplyXpathToObj('error',$XMLobjUser);
        $error = $this->XMLUtils->getResult();
        $error =  $this->DBcontroler->setStringVal($error);
        return $error;
    }

    public function Login():bool
    {
        $this->DBcontroler->prepareProcedure("sp_sap_user_login",[$this->email,$this->pwd]);
        $this->data = $this->DBcontroler->returnedStringXMLValue();
        $this->XMLdata = $this->DBcontroler->returnedXMLValue();
        $this->errUser = $this->saveSessionErr($this->XMLdata);

        if($this->errUser == 0)
        {
           $this->cid = $this->saveSessionCid($this->XMLdata); 
           return true; 
        }
        return false;
    }




    public function GetSessionCid() : string
    {
        return $this->cid;
    }


    public function Register():bool 
    {
        $this->DBcontroler->prepareProcedure("sp_sap_user_register",[$this->email,$this->pwd,$this->username]);
        $this->data = $this->DBcontroler->returnedStringXMLValue();
        $this->XMLdata = $this->DBcontroler->returnedXMLValue();
        
        return true;
    }
    
    public function getData() : string
    {
        return $this->data;
    }
}
?>