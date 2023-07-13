<?php
    include('autoload.php'); 
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");
    $HTTP_RAW_POST_DATA = (json_decode($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : '';
    $HTTP_RAW_POST_DATA = (empty($HTTP_RAW_POST_DATA)) ? json_encode(array_merge($_REQUEST, $_FILES)) : $HTTP_RAW_POST_DATA;
    $server = new apiJson($HTTP_RAW_POST_DATA);
    $server->Register("listReference");
    $server->Register("test");
    $server->start();

    function listReference($arg){
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
        $referenceTableName = "" ;
        $referenceField =  "";

        if(isset($arg->referenceTableName)){
            $referenceTableName =  $arg->referenceTableName;
        }
        else{
            array_push($errorlist,"Error: falta parametro referenceTableName");
        }
        if(isset($arg->referenceField)){
            $referenceField =  $arg->referenceField;
        }
        else{
            array_push($errorlist,"Error: falta parametro referenceField");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $referenceTableName = $arg->referenceTableName;
        $referenceField =  $arg->referenceField;

        $_reference = new reference($_db,$_log);
        $response = $_reference->listReference($referenceTableName,$referenceField);

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
        return array("codError" => 200, "data" => "Hola estamos en linea en la apiReference");
    }
    