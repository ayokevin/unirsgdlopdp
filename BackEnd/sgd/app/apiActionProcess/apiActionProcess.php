<?php
    include('autoload.php'); 
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");
    $HTTP_RAW_POST_DATA = (json_decode($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : '';
    $HTTP_RAW_POST_DATA = (empty($HTTP_RAW_POST_DATA)) ? json_encode(array_merge($_REQUEST, $_FILES)) : $HTTP_RAW_POST_DATA;
    $server = new apiJson($HTTP_RAW_POST_DATA);
    $server->Register("listActionProcess");
    $server->Register("deleteActionProcess");
    $server->Register("updateActionProcess");
    $server->Register("insertActionProcess");
    $server->Register("test");
    $server->start();

    function insertActionProcess($arg){
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
        $_process =  "";
        $_action =  "";

        if(isset($arg->_process)){
                $_process =  $arg->_process;
        }
        else{
            array_push($errorlist,"Error: falta parametro _process");
        }
        if(isset($arg->_action)){
            $_action =  $arg->_action;
        }
        else{
            array_push($errorlist,"Error: falta parametro _action");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $_process = $arg->_process ;
        $_action = $arg->_action ;

        $_actionProcess = new actionProcess($_db,$_log);
        $responseInsert = $_actionProcess->insertActionProcess($_process,$_action);

        if ( $responseInsert) {
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción exitosa", "process_id" => $_actionProcess->getProcess()->getProcessId(),"action_id" => $_actionProcess->getAction()->getActionId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function updateActionProcess($arg){
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
        $_processOld =  "";
        $_actionOld =  "";
        $_processNew =  "";
        $_actionNew =  "";

        if(isset($arg->_processOld)){
            $_processOld =  $arg->_processOld;
        }
        else{
            array_push($errorlist,"Error: falta parametro _processOld");
        }
        if(isset($arg->_actionOld)){
                $_actionOld =  $arg->_actionOld;
        }
        else{
            array_push($errorlist,"Error: falta parametro _actionOld");
        }
        if(isset($arg->_processNew)){
            $_processNew =  $arg->_processNew;
        }
        else{
            array_push($errorlist,"Error: falta parametro _processNew");
        }
        if(isset($arg->_actionNew)){
            $_actionNew =  $arg->_actionNew;
        }
        else{
            array_push($errorlist,"Error: falta parametro _actionNew");
        }
        
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $_processOld = $arg->_processOld ;
        $_actionOld = $arg->_actionOld ;
        $_processNew = $arg->_processNew ;
        $_actionNew = $arg->_actionNew ;

        $_actionProcess = new actionProcess($_db,$_log);
        $responseUpdate = $_actionProcess->updateActionProcess($_processOld,$_actionOld,$_processNew,$_actionNew);

        if ( $responseUpdate) {
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion exitosa", "process_id" => $_actionProcess->getProcess()->getProcessId(),"action_id" => $_actionProcess->getAction()->getActionId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function deleteActionProcess($arg){
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
        $_process =  "";
        $_action =  "";

        if(isset($arg->_process)){
                $_process =  $arg->_process;
        }
        else{
            array_push($errorlist,"Error: falta parametro _process");
        }
        if(isset($arg->_action)){
            $_action =  $arg->_action;
        }
        else{
            array_push($errorlist,"Error: falta parametro _action");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $_process = $arg->_process ;
        $_action = $arg->_action ;

        $_actionProcess = new actionProcess($_db,$_log);
        $responseInsert = $_actionProcess->deleteActionProcess($_process,$_action);

        if ( $responseInsert) {
            $response = array("codError" => 200, "data" => array("desError"=>"Eliminacion exitosa"));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Eliminacion fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function listActionProcess($arg){
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
        $idProcess = "";

        if(isset($arg->idClient)){
            $idClient =  $arg->idClient;
        }
        else{
            array_push($errorlist,"Error: falta parametro idClient");
        }
        if(isset($arg->idProcess)){
            $idProcess =  $arg->idProcess;
        }
        else{
            array_push($errorlist,"Error: falta parametro idProcess");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $idClient =  $arg->idClient;
        $idProcess =  $arg->idProcess;

        $_actionProcess = new actionProcess($_db,$_log);
        $response = $_actionProcess->listActionProcess($idClient,$idProcess);

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
        return array("codError" => 200, "data" => "Hola estamos en linea en apiActionProcess");
    }
    