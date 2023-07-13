<?php
    include('autoload.php'); 
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");
    $HTTP_RAW_POST_DATA = (json_decode($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : '';
    $HTTP_RAW_POST_DATA = (empty($HTTP_RAW_POST_DATA)) ? json_encode(array_merge($_REQUEST, $_FILES)) : $HTTP_RAW_POST_DATA;
    $server = new apiJson($HTTP_RAW_POST_DATA);
    $server->Register("listAction");
    $server->Register("listActionReference");
    $server->Register("updateAction");
    $server->Register("insertAction");
    $server->Register("test");
    $server->start();


    function listAction($arg){
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

        $_action = new action($_db,$_log);
        $response = $_action->listAction($idClient);

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

    function listActionReference($arg){
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

        $_action = new action($_db,$_log);
        $response = $_action->listActionReference($idClient);

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

    function insertAction($arg){
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
        $_status =  "";
        $actionName =  "";
        $actionDescription =  "";
        $_department = "" ;

        if(isset($arg->_status)){
                $_status =  $arg->_status;
        }
        else{
            array_push($errorlist,"Error: falta parametro _status");
        }
        if(isset($arg->actionName)){
            $actionName =  $arg->actionName;
        }
        else{
            array_push($errorlist,"Error: falta parametro actionName");
        }
        if(isset($arg->actionDescription)){
            $actionDescription =  $arg->actionDescription;
        }
        else{
            array_push($errorlist,"Error: falta parametro actionDescription");
        }
        if(isset($arg->_department)){
            $_department =  $arg->_department;
        }
        else{
            array_push($errorlist,"Error: falta parametro _department");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $_status = $arg->_status ;
        $actionName = $arg->actionName ;
        $actionDescription = $arg->actionDescription ;
        $_department = $arg->_department ;

        $_action = new action($_db,$_log);
        $responseInsert = $_action->insertAction($_status,$actionName,$actionDescription,$_department);

        if ( $responseInsert) {
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción exitosa", "actionId" => $_action->getActionId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function updateAction($arg){
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
        $actionId ="";
        $_status =  "";
        $actionName =  "";
        $actionDescription =  "";
        $_department = "" ;

        if(isset($arg->actionId)){
            $actionId =  $arg->actionId;
        }
        else{
            array_push($errorlist,"Error: falta parametro actionId");
        }
        if(isset($arg->_status)){
                $_status =  $arg->_status;
        }
        else{
            array_push($errorlist,"Error: falta parametro _status");
        }
        if(isset($arg->actionName)){
            $actionName =  $arg->actionName;
        }
        else{
            array_push($errorlist,"Error: falta parametro actionName");
        }
        if(isset($arg->actionDescription)){
            $actionDescription =  $arg->actionDescription;
        }
        else{
            array_push($errorlist,"Error: falta parametro actionDescription");
        }
        if(isset($arg->_department)){
            $_department =  $arg->_department;
        }
        else{
            array_push($errorlist,"Error: falta parametro _department");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $actionId = $arg->actionId ;
        $_status = $arg->_status ;
        $actionName = $arg->actionName ;
        $actionDescription = $arg->actionDescription ;
        $_department = $arg->_department ;

        $_action = new action($_db,$_log);
        $responseUpdate = $_action->updateAction($actionId,$_status,$actionName,$actionDescription,$_department);

        if ( $responseUpdate) {
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion exitosa", "actionId" => $_action->getActionId(), "_status" => $_action->getStatus()->getReferenceId(), "actionName" => $_action->getActionName(), "actionDescription" => $_action->getActionDescription(),"_department" => $_action->getDepartment()->getDepartmentId()));
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
        return array("codError" => 200, "data" => "Hola estamos en linea en apiAction");
    }
    