<?php
    include('autoload.php'); 
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");
    $HTTP_RAW_POST_DATA = (json_decode($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : '';
    $HTTP_RAW_POST_DATA = (empty($HTTP_RAW_POST_DATA)) ? json_encode(array_merge($_REQUEST, $_FILES)) : $HTTP_RAW_POST_DATA;
    $server = new apiJson($HTTP_RAW_POST_DATA);
    $server->Register("insertClient");
    $server->Register("updateClient");
    $server->Register("listClient");
    $server->Register("test");
    $server->start();

    function insertClient($arg){
        $startTime = microtime(true);
        $_db=new db(CONNECTION);
        $_log = new log(LOGLEVEL,LOGPATH);
        $respValidate = validateArg($arg);  
        if($respValidate){
            $arrLog = array("input"=>$arg,"output"=>$arg);
            $_log->notice(__FUNCTION__,$arrLog);
            return $respValidate;
        }

        $errorlist=array();
        $nameClient =  "";
        $rucClient =  "";
        $_statusId =  "";

        if(isset($arg->nameClient)){
            $nameClient =  $arg->nameClient;
        }
        else{
            array_push($errorlist,"Error: falta parametro nameClient");
        }
        if(isset($arg->rucClient)){
                $rucClient =  $arg->rucClient;
        }
        else{
            array_push($errorlist,"Error: falta parametro rucClient");
        }
        if(isset($arg->_statusId)){
            $_statusId =  $arg->_statusId;
        }
        else{
            array_push($errorlist,"Error: falta parametro _statusId");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $nameClient =  $arg->nameClient;
        $rucClient =  $arg->rucClient;
        $_statusId =  $arg->_statusId; 

        $_client = new client($_db,$_log);
        $responseInsert = $_client->insertClient($nameClient,$rucClient,$_statusId);

        if ( $responseInsert) {
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción exitosa", "clientId" => $_client->getClientId(), "status_name" => $_client->getClientStatus()->getReferenceName()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function updateClient($arg){
        $startTime = microtime(true);
        $_db=new db(CONNECTION);
        $_log = new log(LOGLEVEL,LOGPATH);
        $respValidate = validateArg($arg);  
        if($respValidate){
            $arrLog = array("input"=>$arg,"output"=>$arg);
            $_log->notice(__FUNCTION__,$arrLog);
            return $respValidate;
        }

        $errorlist=array();
        $idClient = "" ;
        $nameClient =  "";
        $rucClient =  "";
        $_statusId =  "";

        
        if(isset($arg->nameClient)){
            $nameClient =  $arg->nameClient;
        }
        else{
            array_push($errorlist,"Error: falta parametro nameClient");
        }
        if(isset($arg->rucClient)){
                $rucClient =  $arg->rucClient;
        }
        else{
            array_push($errorlist,"Error: falta parametro rucClient");
        }
        if(isset($arg->_statusId)){
            $_statusId =  $arg->_statusId;
        }
        else{
            array_push($errorlist,"Error: falta parametro _statusId");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $idClient = $arg->idClient;
        $nameClient =  $arg->nameClient;
        $rucClient =  $arg->rucClient;
        $_statusId = $arg->_statusId;

        $_client = new client($_db,$_log);
        $responseUpdate = $_client->updateClient($idClient,$nameClient,$rucClient,$_statusId);

        if ( $responseUpdate) {
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion exitosa", "clientId" => $_client->getClientId(), "clientName" => $_client->getClientName(), "clientRuc" => $_client->getClientRuc(), "status_id" => $_client->getClientStatus()->getReferenceId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function listClient($arg){
        $startTime = microtime(true);
        $_db=new db(CONNECTION);
        $_log = new log(LOGLEVEL,LOGPATH);
        $respValidate = validateArg($arg);  
        if($respValidate){
            $arrLog = array("input"=>$arg,"output"=>$arg);
            $_log->notice(__FUNCTION__,$arrLog);
            return $respValidate;
        }

        $_client = new client($_db,$_log);
        $response = $_client->listClient();

        if ( $response) {
            $response = array("codError" => 200, "data" => array("desError"=>"Consulta exitosa", "data" => $response));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Consulta fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }
    
    function validateArg($arg){
        $resp = false;
        if (!is_object($arg)) {
            $resp = array("codError" => 400, "data" =>array("desError"=>"Error en parametros") );
        }
        if (is_null($arg)) {
            $resp = array("codError" => 400, "data" =>array("desError"=>"Error parametros vacios"));
        }
        return $resp;
    }
    
    function test($arg) {    
        return array("codError" => 200, "data" => "Hola estamos en linea");
    }
    