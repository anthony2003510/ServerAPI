<?php

/**
 *  CONSTRUCTOR PARAMS
 * ----------------------------------
 * $this->db = $pDB;
 * 
 * 
 * PRIVATE FUNCTION
 * ----------------------------------
 * bindParamsToProcedure(string $name, array $params): void;
 * putInterrogationMarks(string $execString, array $params): string;
 * 
 */

interface ControllerDataBaseInterface {

    public function prepareProcedure(string $name_procedure, array $params=[]): void;

    public function executeProcedure(): void;

    public function fetchExecutionProcedure(): void;
}

