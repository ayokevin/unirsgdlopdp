<?php
    include('autoload.php'); 
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");
    $HTTP_RAW_POST_DATA = (json_decode($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : '';
    $HTTP_RAW_POST_DATA = (empty($HTTP_RAW_POST_DATA)) ? json_encode(array_merge($_REQUEST, $_FILES)) : $HTTP_RAW_POST_DATA;
    $server = new apiJson($HTTP_RAW_POST_DATA);
    $server->Register("listArticleAction");
    $server->Register("deleteArticleAction");
    $server->Register("updateArticleAction");
    $server->Register("insertArticleAction");
    $server->Register("test");
    $server->start();

    function insertArticleAction($arg){
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
        $_action =  "";
        $_article =  "";

        if(isset($arg->_action)){
                $_action =  $arg->_action;
        }
        else{
            array_push($errorlist,"Error: falta parametro _action");
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

        $_action = $arg->_action ;
        $_article = $arg->_article ;

        $_articleAction = new articleAction($_db,$_log);
        $responseInsert = $_articleAction->insertArticleAction($_action,$_article);

        if ( $responseInsert) {
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción exitosa", "action_id" => $_articleAction->getAction()->getActionId(),"article_id" => $_articleAction->getArticle()->getArticleId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Inserción fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function updateArticleAction($arg){
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
        $_actionOld =  "";
        $_articleOld =  "";
        $_actionNew =  "";
        $_articleNew =  "";

        if(isset($arg->_actionOld)){
            $_actionOld =  $arg->_actionOld;
        }
        else{
            array_push($errorlist,"Error: falta parametro _actionOld");
        }
        if(isset($arg->_articleOld)){
                $_articleOld =  $arg->_articleOld;
        }
        else{
            array_push($errorlist,"Error: falta parametro _articleOld");
        }
        if(isset($arg->_actionNew)){
            $_actionNew =  $arg->_actionNew;
        }
        else{
            array_push($errorlist,"Error: falta parametro _actionNew");
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

        $_actionOld = $arg->_actionOld ;
        $_articleOld = $arg->_articleOld ;
        $_actionNew = $arg->_actionNew ;
        $_articleNew = $arg->_articleNew ;

        $_articleAction = new articleAction($_db,$_log);
        $responseUpdate = $_articleAction->updateArticleAction($_actionOld,$_articleOld,$_actionNew,$_articleNew);

        if ( $responseUpdate) {
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion exitosa", "action_id" => $_articleAction->getAction()->getActionId(),"article_id" => $_articleAction->getArticle()->getArticleId()));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function deleteArticleAction($arg){
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
        $_action =  "";
        $_article =  "";

        if(isset($arg->_action)){
                $_action =  $arg->_action;
        }
        else{
            array_push($errorlist,"Error: falta parametro _action");
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

        $_action = $arg->_action ;
        $_article = $arg->_article ;

        $_articleAction = new articleAction($_db,$_log);
        $responseInsert = $_articleAction->deleteArticleAction($_action,$_article);

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

    function listArticleAction($arg){
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

        $_articleAction = new articleAction($_db,$_log);
        $response = $_articleAction->listArticleAction($idClient,$idArticle);

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
        return array("codError" => 200, "data" => "Hola estamos en linea en apiArticleAction");
    }
    