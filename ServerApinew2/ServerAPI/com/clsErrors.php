<?php
class clsError
{
    public int $error;
    public string $err_desc;
    public string $user_err_desc;
    public int $severity;
    public string $paramname;
    
    function __construct(int $error, string $paramname)
    {
        $this->error = $error;
        $this->GetErrorDescription($error);
        $this->paramname = $paramname;
    }      
    
    function GetErrorDescription(int $err) : void
    {
        switch($err)
        {
            case 0:
                $this->severity = 0;
                $this->err_desc = "todo correcto, error 0";
                $this->user_err_desc = "no hay que arreglar nada, todo esta bien";
                break;

            case 10:
                $this->severity = 3;
                $this->err_desc = "param missing";
                $this->user_err_desc = "has de poner el parametro en la URL";
                break;
            case 20:
                $this->severity = 2;
                $this->err_desc = "default!!";
                $this->user_err_desc = "el action no es correcto, has de poner un metodo válido";
                break;
            case 30:
                $this->severity = 2;
                $this->err_desc = "type error";
                $this->user_err_desc = "El tipo de dato introducido no es valido";
                break;
            case 40:
                $this->severity = 3;
                $this->err_desc = "mandatory error";
                $this->user_err_desc = "el parametro no puede ser undefined";
                break;
            case 50:
                $this->severity = 1;
                $this->err_desc = "lenght error";
                $this->user_err_desc = "el parametro requiere de mas carácteres";
                break;
            case 9999:
                $this->severity = 10000;
                $this->err_desc = "error 9999";
                $this->user_err_desc = "ERROR 9999 NOOOO!!!!!";
                break;
        }
    }

}
?>