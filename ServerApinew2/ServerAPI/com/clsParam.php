<?php
class clsParam{
    private int $err ;
    public int $imp_err;
    private $value;
    private string $NameParametro;
    private array $arrErrors = [];
    private clsError $obj_err;

    function __construct(SimpleXMLElement $pParamXML)
    {
        $this->Request = new clsRequest;
        $this->Utils = new clsXMLUtils();
        clsResponse::printResponse("estoy en el constructor del parametro <br>");

        $this->paramXML=$pParamXML;
        $this->NameParametro = $this->paramXML->attributes()->name->__toString();
    }

    function Validate() : array
    {
        $this->ParamExists();
        return $this->getParamErrors();
    }

    function ParamExists() : void
    {
        if($this->Request->Exists($this->paramXML->attributes()->name->__toString()))
        {
          //  print_r($this->paramXML->attributes()->name->__toString());
            $this->value = $this->Request->GetValue($this->paramXML->attributes()->name->__toString());
            $this->ValidateType();
        }
        else
        {
            $obj_err = new clsError(10,$this->NameParametro);
            array_push($this->arrErrors,  $obj_err);
            $this->imp_err = 10;    
        }
    }

    function ValidateDefault() : void
    {
        if($this->paramXML->default!="")
        {
            if($this->value == $this->paramXML->default)
            {
                    
            }else
            {
                $obj_err = new clsError(20,$this->NameParametro);
                array_push($this->arrErrors,  $obj_err);
                $this->imp_err = 20;
            }
        }
    }

    function ValidateType() : void
    {
        
        if($this->paramXML->type!="")
        {
            if(gettype($this->value) == $this->paramXML->type)
            {
                $this->ValidateMandatory();
            }else
            {
                $obj_err = new clsError(30,$this->NameParametro);
                array_push($this->arrErrors,  $obj_err);
                $this->imp_err = 30;
            }
        }
    }

    function ValidateMandatory() :void 
    {

        if($this->paramXML->mandatory=="yes")
        {
            if(strlen($this->value) != 0)
            {
                
                $this->ValidateLength();

            }else
            {
                $obj_err = new clsError(40,$this->NameParametro); 
                array_push($this->arrErrors,  $obj_err);
            }
        }
    }

    function ValidateLength() : void
    {        
        if($this->paramXML->min_length!="")
        {
            if(strlen($this->value) >= (int)$this->paramXML->min_length)
            {
                $obj_err = new clsError(0,$this->NameParametro);
                $this->imp_err = 0;
                array_push($this->arrErrors,  $obj_err);
            }else
            {
                $obj_err = new clsError(50,$this->NameParametro); 
                array_push($this->arrErrors,  $obj_err);
                $this->imp_err = 50;
            }
        }
        else if($this->paramXML->attributes()->name->__toString() =="cid")
        {
            $obj_err = new clsError(0,$this->NameParametro);
            $this->imp_err = 0;
            array_push($this->arrErrors,  $obj_err); 
        }
    }

    function getParamErrors()
    {        
        return $this->arrErrors;
    }

}
?>