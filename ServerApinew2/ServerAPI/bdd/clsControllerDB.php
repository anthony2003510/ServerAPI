<?php
include_once __DIR__."../../interfaces/ControllerDataBaseInterface.php";

class clsControllerDB implements ControllerDataBaseInterface{
    private $db;
    private $request;
    private string $stringExec;
    public $defRes;
    public function __construct(PDO $Pdb) {
        $this->db = $Pdb;
    }

    public function prepareProcedure(string $name_procedure, array $params = []): void
    {
        $this->stringExec = "EXEC ".$name_procedure;
        $i=0;
        if(count($params) === 0)
        {
         $this->request = $this -> db -> prepare($this->stringExec);   
        }else
        {
            $this->stringExec = $this->putInterrogationMarks($this->stringExec,$params);
            $this->request = $this -> db -> prepare($this->stringExec);
            $this->bindParamsToProcedure($params);
        }

        $this->executeProcedure();
    }

    private function putInterrogationMarks(string $execString,array $params): string
    {
        $i=0;
        $this->stringExec= $this->stringExec." ";
        foreach($params as $valor)
        {
            if($i == 0)
            {
                $this->stringExec= $this->stringExec."?";
            }else{
               $this->stringExec= $this->stringExec.",?"; 
            }
            $i++;
            
        }
        return $this->stringExec; 
    }

    private function bindParamsToProcedure(array $params):void
    {
        foreach($params as $key => &$val)
        {
            $this->request->bindParam($key+1, $val);   
        }
    }

    public function returnedStringXMLValue():string
    {
        foreach($this->defRes[0] as $value)
        {
            return $value;
        }
    }

    public function returnedXMLValue():object
    {
        foreach($this->defRes[0] as $value)
        {
            $obj = simplexml_load_string($value);
        }

        return $obj;
    }

    public function setStringVal($objarr):string
    {
        foreach($objarr as $key => $val)
        {
            return((string)$val);    
        }
    }


    public function executeProcedure(): void
    {
        $this->request-> execute();
        $this->fetchExecutionProcedure();
    }

    public function fetchExecutionProcedure(): void
    {
        if($this->request->rowCount() === 1)
        {
            $this->request->nextRowset();
        }
        $this->defRes = $this->request -> fetchAll(PDO::FETCH_ASSOC);
    
    }

    
}
?>