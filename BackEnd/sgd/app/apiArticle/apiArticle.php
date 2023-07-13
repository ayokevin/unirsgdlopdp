<?php
    include('autoload.php'); 
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");
    $HTTP_RAW_POST_DATA = (json_decode($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : '';
    $HTTP_RAW_POST_DATA = (empty($HTTP_RAW_POST_DATA)) ? json_encode(array_merge($_REQUEST, $_FILES)) : $HTTP_RAW_POST_DATA;
    $server = new apiJson($HTTP_RAW_POST_DATA);
    $server->Register("listArticle");
    $server->Register("updateArticle");
    $server->Register("listArticleReference");
    $server->Register("test");
    $server->start();

    function listArticle($arg){
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
        $idArticle = "" ;
        $idProcess= "" ;

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
        $idArticle = $arg->idArticle;
        $idProcess = $arg->idProcess;

        $_article = new article($_db,$_log);
        $response = $_article->listArticle($idClient,$idArticle, $idProcess);

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

    function updateArticle($arg){
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
        $articleId = "" ;
        $_articleApply =  "";
        $articleObservation =  "";
        $_statusId =  "";
        $articleJustification = "" ;
        $_articleCompliance =  "";

        
        if(isset($arg->articleId)){
            $articleId =  $arg->articleId;
        }
        else{
            array_push($errorlist,"Error: falta parametro articleId");
        }
        if(isset($arg->_articleApply)){
                $_articleApply =  $arg->_articleApply;
        }
        else{
            array_push($errorlist,"Error: falta parametro _articleApply");
        }
        if(isset($arg->articleObservation)){
            $articleObservation =  $arg->articleObservation;
        }
        else{
            array_push($errorlist,"Error: falta parametro articleObservation");
        }
        if(isset($arg->_statusId)){
            $_statusId =  $arg->_statusId;
        }
        else{
            array_push($errorlist,"Error: falta parametro _statusId");
        }
        if(isset($arg->articleJustification)){
            $articleJustification =  $arg->articleJustification;
        }
        else{
            array_push($errorlist,"Error: falta parametro articleJustification");
        }
        if(isset($arg->_articleCompliance)){
            $_articleCompliance =  $arg->_articleCompliance;
        }
        else{
            array_push($errorlist,"Error: falta parametro _articleCompliance");
        }
        if(count($errorlist)!==0){
            return array("codError" => 200, "data" => array("desError"=>$errorlist));
        }

        $articleId = $arg->articleId ;
        $_articleApply =  $arg->_articleApply ;
        $articleObservation =  $arg->articleObservation ;
        $_statusId =  $arg->_statusId ;
        $articleJustification = $arg->articleJustification ;
        $_articleCompliance = $arg->_articleCompliance ;

        $_article = new article($_db,$_log);
        $responseUpdate = $_article->updateArticle($articleId,$_articleApply,$articleObservation,$_statusId,$articleJustification,$_articleCompliance);

        if ( $responseUpdate) {
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion exitosa", 
                                                                "articleId" => $_article->getArticleId(), 
                                                                "_articleApply" => $_article->getArticleApply()->getReferenceId(), 
                                                                "articleObservation" => $_article->getArticleObservation(), 
                                                                "_statusId" => $_article->getStatusId()->getReferenceId(),
                                                                "articleJustification" => $_article->getArticleJustification(),
                                                                "_articleCompliance" => $_article->getArticleCompliance()->getReferenceId()
                                                            ));
        }else{
            $response = array("codError" => 200, "data" => array("desError"=>"Actualizacion fallida"));
        }

        $timeProcess = microtime(true)-$startTime;
        $arrLog = array("time"=>$timeProcess, "input"=>json_encode($arg),"output"=>$response);
        $_log->notice(__FUNCTION__,$arrLog);
        return $response;
    }

    function listArticleReference($arg){
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

        $_article = new article($_db,$_log);
        $response = $_article->listArticleReference($idClient);

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
        return array("codError" => 200, "data" => "Hola estamos en linea en apiArticle");
    }
    