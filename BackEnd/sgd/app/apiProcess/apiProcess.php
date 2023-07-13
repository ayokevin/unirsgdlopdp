<?php
    include('autoload.php'); 
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");
    $HTTP_RAW_POST_DATA = (json_decode($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : '';
    $HTTP_RAW_POST_DATA = (empty($HTTP_RAW_POST_DATA)) ? json_encode(array_merge($_REQUEST, $_FILES)) : $HTTP_RAW_POST_DATA;
    $server = new apiJson($HTTP_RAW_POST_DATA);
    $server->Register("listProcess");
    $server->Register("listProcessReference");
    $server->Register("updateProcess");
    $server->Register("insertProcess");
    $server->Register("test");
    $server->start();


    function listProcess($arg){
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

        if(isset($arg->idClient)){
            $idClient =  $arg->idClient;
        }
        else{
            array_push($errorlist,"Error: falta parametro idClient");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $idClient =  $arg->idClient;

        $_process = new process($_db,$_log);
        $response = $_process->listProcess($idClient);

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

    function listProcessReference($arg){
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

        if(isset($arg->idClient)){
            $idClient =  $arg->idClient;
        }
        else{
            array_push($errorlist,"Error: falta parametro idClient");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $idClient =  $arg->idClient;

        $_process = new process($_db,$_log);
        $response = $_process->listProcessReference($idClient);

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

    function insertProcess($arg){
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
        $userSecId =  "";
        $departmentId =  "";
        $processName =  "";
        $processOrder = "" ;
        $processDescription =  "";
        $_statusId =  "";

        if(isset($arg->userSecId)){
                $userSecId =  $arg->userSecId;
        }
        else{
            array_push($errorlist,"Error: falta parametro userSecId");
        }
        if(isset($arg->departmentId)){
            $departmentId =  $arg->departmentId;
        }
        else{
            array_push($errorlist,"Error: falta parametro departmentId");
        }
        if(isset($arg->processName)){
            $processName =  $arg->processName;
        }
        else{
            array_push($errorlist,"Error: falta parametro processName");
        }
        if(isset($arg->processOrder)){
            $processOrder =  $arg->processOrder;
        }
        else{
            array_push($errorlist,"Error: falta parametro processOrder");
        }
        if(isset($arg->processDescription)){
            $processDescription =  $arg->processDescription;
        }
        else{
            array_push($errorlist,"Error: falta parametro processDescription");
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

        $userSecId = $arg->userSecId ;
        $departmentId = $arg->departmentId ;
        $processName = $arg->processName ;
        $processOrder = $arg->processOrder ;
        $processDescription = $arg->processDescription ;
        $_statusId = $arg->_statusId ;

        $_process = new process($_db,$_log);
        $responseInsert = $_process->insertProcess($userSecId,$departmentId,$processName,$processOrder,$processDescription,$_statusId);

        if ( $responseInsert) {
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción exitosa", "processId" => $_process->getProcessId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function updateProcess($arg){
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
        $processId ="";
        $userSecId =  "";
        $departmentId =  "";
        $processName =  "";
        $processOrder = "" ;
        $processDescription =  "";
        $_statusId =  "";

        if(isset($arg->processId)){
            $processId =  $arg->processId;
        }
        else{
            array_push($errorlist,"Error: falta parametro processId");
        }
        if(isset($arg->userSecId)){
                $userSecId =  $arg->userSecId;
        }
        else{
            array_push($errorlist,"Error: falta parametro userSecId");
        }
        if(isset($arg->departmentId)){
            $departmentId =  $arg->departmentId;
        }
        else{
            array_push($errorlist,"Error: falta parametro departmentId");
        }
        if(isset($arg->processName)){
            $processName =  $arg->processName;
        }
        else{
            array_push($errorlist,"Error: falta parametro processName");
        }
        if(isset($arg->processOrder)){
            $processOrder =  $arg->processOrder;
        }
        else{
            array_push($errorlist,"Error: falta parametro processOrder");
        }
        if(isset($arg->processDescription)){
            $processDescription =  $arg->processDescription;
        }
        else{
            array_push($errorlist,"Error: falta parametro processDescription");
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

        $processId = $arg->processId ;
        $userSecId = $arg->userSecId ;
        $departmentId = $arg->departmentId ;
        $processName = $arg->processName ;
        $processOrder = $arg->processOrder ;
        $processDescription = $arg->processDescription ;
        $_statusId = $arg->_statusId ;

        $_process = new process($_db,$_log);
        $responseUpdate = $_process->updateProcess($processId,$userSecId,$departmentId,$processName,$processOrder,$processDescription,$_statusId);

        if ( $responseUpdate) {
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion exitosa", "processId" => $_process->getProcessId(), "userSecId" => $_process->getUserSec()->getUserSecId(), "departmentId" => $_process->getDepartment()->getDepartmentId(), "processName" => $_process->getProcessName(),"processOrder" => $_process->getProcessOrder(),"processDescription" => $_process->getProcessDescription(),"_statusId" => $_process->getStatus()->getReferenceName()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion fallida"));
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
        return array("codError" => 200, "data" => "Hola estamos en linea en apiProcess");
    }
    