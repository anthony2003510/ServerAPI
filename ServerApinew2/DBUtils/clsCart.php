<?php
class clsCart
{
    private string $idProduct;
    private string $userCid;
    private $bddConnection;
    private $bdd;
    private $DBcontroler;
    private $data;
    private $XMLdata;
    private $errProduct;
    private $XMLUtils;

    function __construct(string $idProduct,string $userCid)
    {
        $this->XMLUtils = new clsXMLUtils;
        $this->idProduct = $idProduct;
        $this->userCid = $userCid;
        $this->DoConnection();
    }

    public function DoConnection() : void
    {
        $this->bddConnection = new ConnectDB("Aqui se tendrán que poner los datos para hacer la conexion a la base de datos");
        $this->bdd = $this->bddConnection->getPDODB();
        $this->DBcontroler = new clsControllerDB($this->bdd);
    }

    public function AddProduct() : bool
    {
        $this->DBcontroler->prepareProcedure("sp_sap_add_product",[$this->idProduct]);
        $this->data = $this->DBcontroler->returnedStringXMLValue();
        $this->XMLdata = $this->DBcontroler->returnedXMLValue();
        $this->errProduct = $this->saveSessionErr($this->XMLdata);
        
        if($this->errProduct == 0)
        {
           return true; 
        }
        return true;
    }

    public function getData() : string
    {
        return $this->data;
    }

    public function saveSessionErr(Object $XMLobjUser):string
    {
        $this->XMLUtils->ApplyXpathToObj('error',$XMLobjUser);
        $error = $this->XMLUtils->getResult();
        $error =  $this->DBcontroler->setStringVal($error);
        return $error;
    }

}
?>
