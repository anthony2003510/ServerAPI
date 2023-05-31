<?php 
class clsSecurityControler
{
    
    private string $url = "";
    private array $url_params = [];
    private string $action = "";
    private string $username;
    private string $email;
    private string $pwd;
    private string $cid;
    private string $userCid;
    private string $productid;
    private bool $error_bool;
    private $error_login;
    
    function __construct()
    {
        $this->url_params = $this->getURLParams($this->getURL());
        $this->set_params();
        $this->action = $this->actionName();
        $this->executeAction();
    }


    function set_params() : void
    {
        foreach($this->url_params as $key => $value)
        {
            switch($key) //revisar mas adelante
            { 
                case "user":
                    $this->username = $value;
                    break;
                case "email":
                    $this->email = $value;
                    break;
                case "pwd":
                    $this->pwd = $value;
                    break;
                case "cid":
                    $this->cid = $value;
                    break;
                case "productid":
                    $this->productid = $value;
                    break;
            }               
        } 
    }


    function actionName():string 
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
    }


    function executeAction() : void
    {
        switch($this->action)
        {
            case "login":
                $this->DoLogin();
                break;
            case "register":
                $this->DoRegister();
                break;
            case "logout":
                $this->DoLogout();
                break;
            case "addtocart":
                $this->AddToCart();
                break;     
        }
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


    function DoLogin() : void
    {
        $user = new clsUser($this->username = "",$this->email,$this->pwd);
        $this->error_bool = $user->Login();
        if($this->error_bool)
        {
            $this->userCid = $user->GetSessionCid();
            $this->setSesionCookie($this->userCid); 
        }
        clsResponse::setValue($user->getData());
    
    }

    
    public function setSesionCookie($sesionGuid){
        setcookie("UserId", $sesionGuid, time() - 86400 );
        header('Set-Cookie: tokenID=' . urlencode($sesionGuid) . '; expires=' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT; path=/');
    }


    public function deleteSesionCookie($sesionGuid){
        setcookie("UserId", $sesionGuid, time() - 0 );
        header('Set-Cookie: tokenID=' . urlencode($sesionGuid) . '; expires=' . gmdate('D, d M Y H:i:s', time() + 0) . ' GMT; path=/');
    }

    function DoRegister() : void
    {
        $user = new clsUser($this->username,$this->email,$this->pwd);
        $this->error_bool = $user->Register();
        clsResponse::setValue($user->getData());
    }


    function DoLogout() :void 
    {
        $session = new clsSession($this->cid);
        $this->error_bool = $session->Logout();
        $this->deleteSesionCookie($this->cid);
        clsResponse::setValue($session->getData());
    
    }

    function AddToCart()
    {
        if($this->ValidateSession())
        {
            $cart = new clsCart($this->productid,$this->cid);
            $this->error_bool = $cart->AddProduct();
            clsResponse::setValue($cart->getData());
        }else
        {
            clsResponse::setValue("has de estar logueado para registrar un producto");
        }
    }

    function ValidateSession():bool
    {
        if(isset($_COOKIE["tokenID"]))
        {
            if($this->cid == $_COOKIE["tokenID"])
            {
                return true;
            }
            else
            {
                return false;
            }
        }else
        {
            return false;
        }
        
    }
}
?>