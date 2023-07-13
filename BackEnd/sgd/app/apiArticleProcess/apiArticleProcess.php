<?php
    include('autoload.php'); 
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");
    $HTTP_RAW_POST_DATA = (json_decode($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : '';
    $HTTP_RAW_POST_DATA = (empty($HTTP_RAW_POST_DATA)) ? json_encode(array_merge($_REQUEST, $_FILES)) : $HTTP_RAW_POST_DATA;
    $server = new apiJson($HTTP_RAW_POST_DATA);
    $server->Register("listArticleProcess");
    $server->Register("deleteArticleProcess");
    $server->Register("updateArticleProcess");
    $server->Register("insertArticleProcess");
    $server->Register("test");
    $server->start();

    function insertArticleProcess($arg){
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
        $_article =  "";

        if(isset($arg->_process)){
                $_process =  $arg->_process;
        }
        else{
            array_push($errorlist,"Error: falta parametro _process");
        }
        if(isset($arg->_article)){
            $_article =  $arg->_article;
        }
        else{
            array_push($errorlist,"Error: falta parametro _article");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $_process = $arg->_process ;
        $_article = $arg->_article ;

        $_articleProcess = new articleProcess($_db,$_log);
        $responseInsert = $_articleProcess->insertArticleProcess($_process,$_article);

        if ( $responseInsert) {
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción exitosa", "process_id" => $_articleProcess->getProcess()->getProcessId(),"article_id" => $_articleProcess->getArticle()->getArticleId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function updateArticleProcess($arg){
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
        $_articleOld =  "";
        $_processNew =  "";
        $_articleNew =  "";

        if(isset($arg->_processOld)){
            $_processOld =  $arg->_processOld;
        }
        else{
            array_push($errorlist,"Error: falta parametro _processOld");
        }
        if(isset($arg->_articleOld)){
                $_articleOld =  $arg->_articleOld;
        }
        else{
            array_push($errorlist,"Error: falta parametro _articleOld");
        }
        if(isset($arg->_processNew)){
            $_processNew =  $arg->_processNew;
        }
        else{
            array_push($errorlist,"Error: falta parametro _processNew");
        }
        if(isset($arg->_articleNew)){
            $_articleNew =  $arg->_articleNew;
        }
        else{
            array_push($errorlist,"Error: falta parametro _articleNew");
        }
        
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $_processOld = $arg->_processOld ;
        $_articleOld = $arg->_articleOld ;
        $_processNew = $arg->_processNew ;
        $_articleNew = $arg->_articleNew ;

        $_articleProcess = new articleProcess($_db,$_log);
        $responseUpdate = $_articleProcess->updateArticleProcess($_processOld,$_articleOld,$_processNew,$_articleNew);

        if ( $responseUpdate) {
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion exitosa", "process_id" => $_articleProcess->getProcess()->getProcessId(),"article_id" => $_articleProcess->getArticle()->getArticleId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function deleteArticleProcess($arg){
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
        $_article =  "";

        if(isset($arg->_process)){
                $_process =  $arg->_process;
        }
        else{
            array_push($errorlist,"Error: falta parametro _process");
        }
        if(isset($arg->_article)){
            $_article =  $arg->_article;
        }
        else{
            array_push($errorlist,"Error: falta parametro _article");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $_process = $arg->_process ;
        $_article = $arg->_article ;

        $_articleProcess = new articleProcess($_db,$_log);
        $responseInsert = $_articleProcess->deleteArticleProcess($_process,$_article);

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

    function listArticleProcess($arg){
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
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $idClient =  $arg->idClient;
        $idArticle =  $arg->idArticle;

        $_articleProcess = new articleProcess($_db,$_log);
        $response = $_articleProcess->listArticleProcess($idClient,$idArticle);

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
        return array("codError" => 200, "data" => "Hola estamos en linea en apiArticleProcess");
    }
    