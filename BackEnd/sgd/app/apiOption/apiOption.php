<?php
    include('autoload.php'); 
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");
    $HTTP_RAW_POST_DATA = (json_decode($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : '';
    $HTTP_RAW_POST_DATA = (empty($HTTP_RAW_POST_DATA)) ? json_encode(array_merge($_REQUEST, $_FILES)) : $HTTP_RAW_POST_DATA;
    $server = new apiJson($HTTP_RAW_POST_DATA);
    $server->Register("getOptionList");
    $server->Register("test");
    $server->start();

    function getOptionList($arg){
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
        $userId =  "";
        $fatherId =  "";

        if(isset($arg->userId)){
            $userId =  $arg->userId;
        }
        else{
            array_push($errorlist,"Error: falta parametro userId");
        }
        if(isset($arg->fatherId)){
                $fatherId =  $arg->fatherId;
        }
        else{
            array_push($errorlist,"Error: falta parametro fatherId");
        }
        
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $userId =  $arg->userId;
        $fatherId =  $arg->fatherId;
        $arrayComponent = createMenu($_db, $_log, $userId , 0);
        $arrResp["error"] = false;
        $arrResp["data"] =  $arrayComponent;

        $response = array("codError" => 200, "data"=>$arrResp);

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function createMenu($_db, $_log, $userId, $fatherId){
        $_option = new option($_db, $_log);
        $data = $_option->getOptionList($userId, $fatherId);
    
        $arrayComponent = array();
        
        if($data){
            foreach($data as $row){
                $component = json_decode($row['option_component'], true);
                $component["name"] = $row['option_name'];
        
                switch ($row['option_type']) {
                    case 18:
                        $component["type"] = $row['option_type'];
                        $component['option'] = createMenu($_db, $_log, $userId, $row['options_id']);
                        break;
                    case 16:
                        $component["type"] = $row['option_type'];
                        break;
                }
                
                array_push($arrayComponent , $component);
            }
        }
        return $arrayComponent;
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
        return array("codError" => 200, "data" => "Hola estamos en linea en la apiOption");
    }
    