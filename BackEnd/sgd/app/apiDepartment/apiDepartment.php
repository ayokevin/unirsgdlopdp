<?php
    include('autoload.php'); 
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");
    $HTTP_RAW_POST_DATA = (json_decode($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : '';
    $HTTP_RAW_POST_DATA = (empty($HTTP_RAW_POST_DATA)) ? json_encode(array_merge($_REQUEST, $_FILES)) : $HTTP_RAW_POST_DATA;
    $server = new apiJson($HTTP_RAW_POST_DATA);
    $server->Register("insertDepartment");
    $server->Register("updateDepartment");
    $server->Register("listDepartment");
    $server->Register("test");
    $server->start();

    function insertDepartment($arg){
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
        
        $idClient ="";
        $nameDepartment= "";
        $fatherId ="";
        $_statusId ="";

        if(isset($arg->idClient)){
            $idClient =  $arg->idClient;
        }
        else{
            array_push($errorlist,"Error: falta parametro idClient");
        }
        if(isset($arg->nameDepartment)){
                $nameDepartment =  $arg->nameDepartment;
        }
        else{
            array_push($errorlist,"Error: falta parametro nameDepartment");
        }
        if(isset($arg->fatherId)){
            $fatherId =  $arg->fatherId;
        }
        else{
            array_push($errorlist,"Error: falta parametro fatherId");
        }
        if(isset($arg->_statusId)){
        $_statusId =  $arg->_statusId;
        }
        else{
            array_push($errorlist,"Error: falta parametro departmentStatusId");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $idClient =  $arg->idClient;
        $nameDepartment =  $arg->nameDepartment;
        $fatherId =  $arg->fatherId;
        $_statusId =  $arg->_statusId;

        $_department = new department($_db,$_log);
        $responseInsert = $_department->insertDepartment($idClient,$nameDepartment,$fatherId,$_statusId);

        if ( $responseInsert) {
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción exitosa", "clientId" => $_department->getDepartmentId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function updateDepartment($arg){
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
        $idDepartment = "" ;
        $nameDepartment =  "";
        $fatherId =  "";
        $_statusId =  "";

        
        if(isset($arg->idDepartment)){
            $idDepartment =  $arg->idDepartment;
        }
        else{
            array_push($errorlist,"Error: falta parametro idDepartment");
        }
        if(isset($arg->nameDepartment)){
                $nameDepartment =  $arg->nameDepartment;
        }
        else{
            array_push($errorlist,"Error: falta parametro nameDepartment");
        }
        if(isset($arg->fatherId)){
            $fatherId =  $arg->fatherId;
        }
        else{
            array_push($errorlist,"Error: falta parametro fatherId");
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

        $idDepartment =  $arg->idDepartment ;
        $nameDepartment =   $arg->nameDepartment;
        $fatherId =   $arg->fatherId;
        $_statusId =   $arg->_statusId;

        $_department = new department($_db,$_log);
        $responseUpdate = $_department->updateDepartment($idDepartment,$nameDepartment,$fatherId,$_statusId);

        if ( $responseUpdate) {
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion exitosa", "idDepartment" => $_department->getDepartmentId(), "nameDepartment" => $_department->getDepartmentName(), "fatherId" => $_department->getFatherId(), "_statusId" => $_department->getDepartmentStatus()->getReferenceId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function listDepartment($arg){
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

        $_department = new department($_db,$_log);
        $response = $_department->listDepartment($idClient);

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
        return array("codError" => 200, "data" => "Hola estamos en linea en apiDepartment");
    }
    