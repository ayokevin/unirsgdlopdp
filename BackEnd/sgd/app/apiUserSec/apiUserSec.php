<?php
    include('autoload.php'); 
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");
    $HTTP_RAW_POST_DATA = (json_decode($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : '';
    $HTTP_RAW_POST_DATA = (empty($HTTP_RAW_POST_DATA)) ? json_encode(array_merge($_REQUEST, $_FILES)) : $HTTP_RAW_POST_DATA;
    $server = new apiJson($HTTP_RAW_POST_DATA);
    $server->Register("checkUserSec");
    $server->Register("listUserSec");
    $server->Register("updateUserSec");
    $server->Register("insertUserSec");
    $server->Register("test");
    $server->start();


    function checkUserSec($arg){
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
        $userEmail = "" ;
        $userPassword = "" ;

        if(isset($arg->userEmail)){
            $userEmail =  $arg->userEmail;
        }
        else{
            array_push($errorlist,"Error: falta parametro userEmail");
        }
        if(isset($arg->userPassword)){
            $userPassword =  $arg->userPassword;
        }
        else{
            array_push($errorlist,"Error: falta parametro userPassword");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $userEmail = $arg->userEmail ;
        $userPassword = $arg->userPassword ;
        if($userEmail!=='' && $userPassword!==''){
            $_userSec = new userSec($_db,$_log);
            $responseInsert = $_userSec->checkUserSec($userEmail,$userPassword);
            if ( $responseInsert) {
                $response = array("codError" => 200, "data" => array(   "desError"=>"El usuario existe", 
                                                                        "userSecId" => $_userSec->getUserSecId(),
                                                                        "userFirstName" => $_userSec->getUserFirstName(),
                                                                        "userLastName" => $_userSec->getUserLastName(),
                                                                        "userApplication" => $_userSec->getUserApplication(),
                                                                        "statusName" => $_userSec->getStatus()->getReferenceName(),
                                                                        "clientId" => $_userSec->getClientId()->getClientId(),
                                                                    ));
            }
            else{
                $response = array("codError" => 200, "data" => array("desError"=>"El usuario NO existe"));
            }
        }
        else{
            $response = array("codError" => 200, "data" => array("desError"=>"El usuario NO existe"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function listUserSec($arg){
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

        $_userSec = new userSec($_db,$_log);
        $response = $_userSec->listUserSec($idClient);

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

    function insertUserSec($arg){
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
        $_statusId =  "";
        $userEmail =  "";
        $userFirstName =  "";
        $userLastName = "" ;
        $userApplication =  "";
        $userPassword =  "";
        $_clientId =  "";

        if(isset($arg->_statusId)){
                $_statusId =  $arg->_statusId;
        }
        else{
            array_push($errorlist,"Error: falta parametro _statusId");
        }
        if(isset($arg->userEmail)){
            $userEmail =  $arg->userEmail;
        }
        else{
            array_push($errorlist,"Error: falta parametro userEmail");
        }
        if(isset($arg->userFirstName)){
            $userFirstName =  $arg->userFirstName;
        }
        else{
            array_push($errorlist,"Error: falta parametro userFirstName");
        }
        if(isset($arg->userLastName)){
            $userLastName =  $arg->userLastName;
        }
        else{
            array_push($errorlist,"Error: falta parametro userLastName");
        }
        if(isset($arg->userApplication)){
            $userApplication =  $arg->userApplication;
        }
        else{
            array_push($errorlist,"Error: falta parametro userApplication");
        }
        if(isset($arg->userPassword)){
            $userPassword =  $arg->userPassword;
        }
        else{
            array_push($errorlist,"Error: falta parametro userPassword");
        }
        if(isset($arg->_clientId)){
            $_clientId =  $arg->_clientId;
        }
        else{
            array_push($errorlist,"Error: falta parametro _clientId");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $_statusId =  $arg->_statusId ;
        $userEmail =  $arg->userEmail ;
        $userFirstName =  $arg->userFirstName ;
        $userLastName = $arg->userLastName ;
        $userApplication = $arg->userApplication ;
        $userPassword =  $arg->userPassword ;
        $_clientId =  $arg->_clientId ;

        $_userSec = new userSec($_db,$_log);
        $responseInsert = $_userSec->insertUserSec($_statusId,$userEmail,$userFirstName,$userLastName,$userApplication,$userPassword,$_clientId);

        if ( $responseInsert) {
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción exitosa", "userSecId" => $_userSec->getUserSecId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function updateUserSec($arg){
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
        $userSecId = "" ;
        $_statusId =  "";
        $userEmail =  "";
        $userFirstName =  "";
        $userLastName = "" ;
        $userApplication =  "";
        $userPassword =  "";
        $_clientId =  "";

        
        if(isset($arg->userSecId)){
            $userSecId =  $arg->userSecId;
        }
        else{
            array_push($errorlist,"Error: falta parametro userSecId");
        }
        if(isset($arg->_statusId)){
                $_statusId =  $arg->_statusId;
        }
        else{
            array_push($errorlist,"Error: falta parametro _statusId");
        }
        if(isset($arg->userEmail)){
            $userEmail =  $arg->userEmail;
        }
        else{
            array_push($errorlist,"Error: falta parametro userEmail");
        }
        if(isset($arg->userFirstName)){
            $userFirstName =  $arg->userFirstName;
        }
        else{
            array_push($errorlist,"Error: falta parametro userFirstName");
        }
        if(isset($arg->userLastName)){
            $userLastName =  $arg->userLastName;
        }
        else{
            array_push($errorlist,"Error: falta parametro userLastName");
        }
        if(isset($arg->userApplication)){
            $userApplication =  $arg->userApplication;
        }
        else{
            array_push($errorlist,"Error: falta parametro userApplication");
        }
        if(isset($arg->userPassword)){
            $userPassword =  $arg->userPassword;
        }
        else{
            array_push($errorlist,"Error: falta parametro userPassword");
        }
        if(isset($arg->_clientId)){
            $_clientId =  $arg->_clientId;
        }
        else{
            array_push($errorlist,"Error: falta parametro _clientId");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $userSecId = $arg->userSecId ;
        $_statusId =  $arg->_statusId ;
        $userEmail =  $arg->userEmail ;
        $userFirstName =  $arg->userFirstName ;
        $userLastName = $arg->userLastName ;
        $userApplication = $arg->userApplication ;
        $userPassword =  $arg->userPassword ;
        $_clientId =  $arg->_clientId ;

        $_userSec = new userSec($_db,$_log);
        $responseUpdate = $_userSec->updateUserSec($userSecId,$_statusId,$userEmail,$userFirstName,$userLastName,$userApplication,$userPassword,$_clientId);

        if ( $responseUpdate) {
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion exitosa", "userSecId" => $_userSec->getUserSecId(), "_statusId" => $_userSec->getStatus()->getReferenceId(), "userEmail" => $_userSec->getUserEmail(), "userFirstName" => $_userSec->getUserFirstName(),"userLastName" => $_userSec->getUserLastName(),"userApplication" => $_userSec->getUserApplication(),"userPassword" => $_userSec->getUserPassword(),"_clientId" => $_userSec->getClientId()->getClientId()));
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
        return array("codError" => 200, "data" => "Hola estamos en linea en apiUSerSec");
    }
    