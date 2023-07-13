<?php
    include('autoload.php'); 
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");
    $HTTP_RAW_POST_DATA = (json_decode($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : '';
    $HTTP_RAW_POST_DATA = (empty($HTTP_RAW_POST_DATA)) ? json_encode(array_merge($_REQUEST, $_FILES)) : $HTTP_RAW_POST_DATA;
    $server = new apiJson($HTTP_RAW_POST_DATA);
    $server->Register("insertFile");
    $server->Register("listFileArticle");
    $server->Register("listFileProcess");
    $server->Register("listFileActions");
    $server->Register("updateFile");
    $server->Register("downloadFile");
    $server->Register("test");
    $server->start();

    function insertFile($arg){
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
        $_article =  "";
        $_process =  "";
        $file =  "";
        $fileName =  "";
        $_action = "" ;
        $_status = "" ;

        if(isset($arg->_article)){
                $_article =  $arg->_article;
        }
        else{
            array_push($errorlist,"Error: falta parametro _article");
        }
        if(isset($arg->_process)){
            $_process =  $arg->_process;
        }
        else{
            array_push($errorlist,"Error: falta parametro _process");
        }
        if(isset($arg->file)){
            $file =  $arg->file;
        }
        else{
            array_push($errorlist,"Error: falta parametro file");
        }
        if(isset($arg->fileName)){
            $fileName =  $arg->fileName;
        }
        else{
            array_push($errorlist,"Error: falta parametro fileName");
        }
        if(isset($arg->_action)){
            $_action =  $arg->_action;
        }
        else{
            array_push($errorlist,"Error: falta parametro _action");
        }
        if(isset($arg->_status)){
            $_status =  $arg->_status;
        }
        else{
            array_push($errorlist,"Error: falta parametro _status");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $_article = $arg->_article ;
        $_process = $arg->_process ;
        $file = $arg->file ;
        $fileName =   $arg->fileName ;
        $_action = $arg->_action ;
        $_status = $arg->_status ;

        $_file = new file($_db,$_log);
        $responseInsert = $_file->insertFile($_article,$_process,$file,$fileName,$_action,$_status);

        if ( $responseInsert) {
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción exitosa", "fileId" => $_file->getFileId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }
    
    function listFileArticle($arg){
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
        $idArticle = "";

        if(isset($arg->idClient)){
            $idClient =  $arg->idClient;
        }
        else{
            array_push($errorlist,"Error: falta parametro idClient");
        }
        if(isset($arg->idArticle)){
            $idArticle =  $arg->idArticle;
        }
        else{
            array_push($errorlist,"Error: falta parametro idArticle");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $idClient =  $arg->idClient;
        $idArticle =  $arg->idArticle;

        $_file = new file($_db,$_log);
        $response = $_file->listFileArticle($idClient,$idArticle);

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

    function listFileProcess($arg){
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

        $_file = new file($_db,$_log);
        $response = $_file->listFileProcess($idClient,$idProcess);

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

    function listFileActions($arg){
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
        $idAction = "" ;

        if(isset($arg->idClient)){
            $idClient =  $arg->idClient;
        }
        else{
            array_push($errorlist,"Error: falta parametro idClient");
        }
        if(isset($arg->idAction)){
            $idAction =  $arg->idAction;
        }
        else{
            array_push($errorlist,"Error: falta parametro idAction");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $idClient =  $arg->idClient;
        $idAction =  $arg->idAction;

        $_file = new file($_db,$_log);
        $response = $_file->listFileActions($idClient,$idAction);

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

    function updateFile($arg){
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
        $idFile="";
        $_article =  "";
        $_process =  "";
        $file =  "";
        $fileName =  "";
        $_action = "" ;
        $_status = "" ;

        if(isset($arg->idFile)){
            $idFile =  $arg->idFile;
        }
        else{
            array_push($errorlist,"Error: falta parametro idFile");
        }
        if(isset($arg->_article)){
                $_article =  $arg->_article;
        }
        else{
            array_push($errorlist,"Error: falta parametro _article");
        }
        if(isset($arg->_process)){
            $_process =  $arg->_process;
        }
        else{
            array_push($errorlist,"Error: falta parametro _process");
        }
        if(isset($arg->file)){
            $file =  $arg->file;
        }
        else{
            array_push($errorlist,"Error: falta parametro file");
        }
        if(isset($arg->fileName)){
            $fileName =  $arg->fileName;
        }
        else{
            array_push($errorlist,"Error: falta parametro fileName");
        }
        if(isset($arg->_action)){
            $_action =  $arg->_action;
        }
        else{
            array_push($errorlist,"Error: falta parametro _action");
        }
        if(isset($arg->_status)){
            $_status =  $arg->_status;
        }
        else{
            array_push($errorlist,"Error: falta parametro _status");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $$idFile = $arg->idFile ;
        $_article = $arg->_article ;
        $_process = $arg->_process ;
        $file = $arg->file ;
        $fileName = $arg->fileName ;
        $_action = $arg->_action ;
        $_status = $arg->_status ;

        $_file = new file($_db,$_log);
        $responseUpdate = $_file->updateFile($idFile,$_article,$_process,$file,$fileName,$_action,$_status);

        if ( $responseUpdate) {
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion exitosa", "fileId" => $_file->getFileId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"v fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function downloadFile($arg){
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
        $idFile = "" ;

        if(isset($arg->idFile)){
            $idFile =  $arg->idFile;
        }
        else{
            array_push($errorlist,"Error: falta parametro idFile");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $idFile =  $arg->idFile;

        $_file = new file($_db,$_log);
        $response = $_file->downloadFile($idFile);

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
        return array("codError" => 200, "data" => "Hola estamos en linea en apiFile");
    }
    