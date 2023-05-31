<?php
class clsMethod
{
    private  SimpleXMLElement $xml_node;
    private array $ArrParam = [];
    private array $ErrParam = [];
    function __construct(SimpleXMLElement $XMLMethod)
    {
        $this->xml_node = $XMLMethod;
        $this->Request = new clsRequest;
        $this->obj_xmlutil= new clsXMLUtils;
        $this->Init();
        $this->Validate();
        
    }

    function Init() : void
    {
        clsResponse::printResponse("estoy en el init del metodo <br>");

        $this->ParseWebParams();
    }

    function ParseWebParams() : void
    {
        $this->obj_xmlutil->ApplyXpathToObj('params_collection/param', $this->xml_node);
        $params = $this->obj_xmlutil->GetResult();        
        foreach ($params as $Param)
        {
            $this->addParam($Param);
        }      
    }


    public function addParam(SimpleXMLElement $XMLParam): void
    {
        $cls_param= new clsParam($XMLParam);
        array_push($this->ArrParam, $cls_param);
    }


    public function Validate() : void
    {   
        
        foreach ($this->ArrParam as $Param)
        {
            if($Param->paramXML["name"]->__toString() == "action")
            {
                if($Param->paramXML->default->__toString() ==$this->Request->GetValue("action") )
                {   
                    foreach($this->ArrParam as $param)
                    {       
                        $this->ErrParam = array_merge($this->ErrParam,$param->Validate());
                    }     
                }

            }
        }     
        
           
    }

    public function GetParamErrors() : array
    {
        return $this->ErrParam;
    }

}


?>