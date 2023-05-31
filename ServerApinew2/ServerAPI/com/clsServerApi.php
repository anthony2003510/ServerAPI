<?php
class clsServerApi
{
    private string  $configfile;
    private clsXMLUtils $obj_xmlutil;
    private array $ArrMethods2=[] ;
    private  array $arrErrors = [];
    private  int $err;
    private clsResponse $obj_res;

    function __construct(string $configfile)
    {
        $this->obj_xmlutil= new clsXMLUtils;   
        $this->Request = new clsRequest;
        $this->configfile=$configfile;

        if($this->Request->Exists("action") == true)
        {
            $this->Init();
        }
        else
        {
            $obj_err = new clsError(9999,"action");  
            array_push($this->ArrMethods2, $obj_err);
            clsResponse::printResponse(count($this->ArrMethods2)); 
        }
    }


    function setServerErrors($obj_res)
    {
        if(count($this->ArrMethods2) > 1)
        {
            $obj_res->getParamErrors($this->ArrMethods2);
        }else
        {
            $obj_res->GetActionError($this->ArrMethods2);
        }
    }


    function Init() : void
    {
        $this->ReadConfigurationFile();
        $this->ParseWebMethods();
        clsResponse::printResponse(count($this->ArrMethods2)); 
    }


    function ReadConfigurationFile():void
    {
        $this->obj_xmlutil->ReadFile($this->configfile);
    }


    function ParseWebMethods() : void
    {
        $this->obj_xmlutil->ApplyXpath('//web_methods_collection/web_method');
        clsResponse::printResponse("estoy en parse web method <br>");
        $arrMethods=  $this->obj_xmlutil->GetResult();
        foreach ($arrMethods as $Method)
        {
            $this->addMethod($Method);
        }
    }


    public function addMethod(SimpleXMLElement $XMLMethod): void
    {   
        $cls_method= new clsMethod($XMLMethod);
        array_push($this->ArrMethods2, $cls_method);      
    }
}
?>