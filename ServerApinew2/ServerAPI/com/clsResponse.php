<?php
class clsResponse
{
    private array $url_params = [];
    private static bool  $typeView;
    private clsXMLUtils $obj_utils;
    private string $url;
    private array $paramErrs= [];
    private array $result = [];
    private array $arr_data;
    private static $value = "Soluciona los errores, por favor";
    private $db;
    private $controller;
    private $pdata;
    private $API_Error;

    function __construct(bool $TypeOfView)
    {
        self::$typeView = $TypeOfView;
        $this->url = $this->getURL();
        $this->url_params = $this->getURLParams($this->getURL()); 
    }

    
    public static function setValue($val)
    {
        self::$value = $val;
    }


    private function print_data()
    {
        $str = "<response_data>" . self::$value."</response_data>";  
        return $str;
    }   


    function getURL(): string
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        {
            $url = "https://";
        }  
        else
        {
            $url = "http://";
        }  
        $url.= $_SERVER['HTTP_HOST'];   

        $url.= $_SERVER['REQUEST_URI'];          

        return $url;  
    }


    function getURLParams($url): array
    {
        $url_components = $_GET;
        return $url_components;
    }

    function GenerateXML() : void
    {
        switch(self::$typeView)
        {
            case true:
                $this->XMLResponse();
                break;
        }
    }


    function getMethodName() 
    {
        
        foreach($_GET as $key => $value)
        {
            if($key == "action")
            {
                return $value;
            }
            else
            {
                return "undefined";
            }
            
        } 
        
         return "undefined";  
    }


    function printParamsXML(): string
    {
        $str = "<parameters>";
        foreach($_GET as $key => $value)
        {
            $str = $str ."<param>";

                $str = $str . "<name>".$key. "</name>";
                if($value == "" || $value == null)
                {
                    $value = "undefined";
                }
                $str = $str . "<value>".$value. "</value>";
                
            $str = $str ."</param>";
        }
        $str = $str . "</parameters>";
        return $str;
    }



    function printErrorsXML()
    {
        $str = "<errores>";
        foreach($this->result as $err)
        {
            $str = $str . "<error>";
            $str = $str . "<param>" .$err->paramname. "</param>";
            $str = $str . "<num_error>" .$err->error. "</num_error>";
            $str = $str . "<message_error>" .$err->err_desc. "</message_error>";
            $str = $str . "<severity>" .$err->severity. "</severity>";
            $str = $str . "<user_message>" .$err->user_err_desc."</user_message>";
            $str = $str . "</error>";
        
        }
        $str = $str ."</errores>";
        return $str;
    }

    function XMLResponse() : void
    {
        $time_end = microtime(true);
        $execution_time = $time_end - $GLOBALS['time_start'];
        $xmlstr = " 
            <ws_response>
                <head>
                    <server_id>1</server_id>
                    <server_time>".date("Y-m-d")."</server_time>
                    <execution_time>".$execution_time." miliseconds"."</execution_time>

                    <url>".htmlspecialchars($this->url)."</url>
                    <webmethod>
                        <name>".$this->getMethodName()."</name>
                        ".$this->printParamsXML()."
                    </webmethod>
                    ".$this->printErrorsXML()."
                    
                </head>

                <body>
                    ".$this->print_data()."
                </body>
            </ws_response>
        "; 
        $xml = new SimpleXMLElement($xmlstr);
        header ("Content-Type:text/xml");
        echo $xml->asXML();
    }

    public static function printResponse(string $message) :void
    {
        if(self::$typeView==false)
        {
            print_r("<br>");
            echo $message;
        }
    }
    
    function getParamErrors(array $arr) : void
    {
        $this->method_arr = $arr;
        foreach($this->method_arr as $method)
        {
            $this->result = array_merge($this->result , $method->GetParamErrors());
        }
    }

    function GetActionError(array $ErrArr) : void
    {
        $this->result = array_merge($this->result, $ErrArr);    
    }

    function Get_Num_API_Err()
    {
        foreach($this->result as $err)
        {
            if($err->error == 0)
            {
                $this->API_Error = 0;
            }else
            {
                $this->API_Error = $err->error;
                return $this->API_Error;
            }
        }
        return $this->API_Error;
    }

}
?>