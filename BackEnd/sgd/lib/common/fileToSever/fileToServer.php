<?php
/*****************
 * API: Procesamiento de archivos para la subida de archivos al servidor
 * AUTOR: KEVIN SANTILLAN
 * FECHA: 2023-06-27
 * ***************/ 

 class fileToServer {
    private $_db, $_log;
    private $file, $newName, $tmpName, $uploadName, $extFile, $descrip_r;

    function __construct($db, $log, $newName='', $file=''){
        $this->_db = $db;
        $this->_log = $log;
        if($file != '' && $newName != ''){
            $this->descrip_r = $this->setFileToDataBase($file, $newName);
        } else {
            if($newName != ''){
                $this->newName = $newName;
            }
        }
    }

    function __destruct(){
        unset($this->_db);
        unset($this->_log);
    }

    public function setFileToDataBase($file, $newName){
        $this->file = $file;
        $this->newName = $newName;
        $name = $file->name;
        $pathinfo = pathinfo($name);
        $this->extFile = $pathinfo['extension'];
        $this->tmpName = $this->file->tmp_name;
        $this->uploadName = UPLOADPATH.$this->newName.".".$this->extFile;
        
        $response = array("descrip_r" => "ok");
        
        // Cargar el archivo al servidor
        if (move_uploaded_file($this->tmpName, $this->uploadName)) {
            $response["descrip_r"] = "Archivo cargado exitosamente.";
            // También puedes realizar otras acciones después de cargar el archivo, si es necesario
        } else {
            $response["descrip_r"] = "Error al cargar el archivo.";
        }
        
        $arrLog = array(
            "input" => array("file" => $this->file, "newName" => $newName, "tmpName" => $this->tmpName),
            "output" => $response
        );
        $this->_log->warning(__METHOD__, $arrLog);
        
        return $response;
    }

    public function deleteFileFromServer($fileName){
        $response=file_exists(UPLOADPATH.$fileName);
			
        if ( $response) {
            unlink(UPLOADPATH.$fileName);
            $arrLog = array("input"=>array(	"fileName"=> UPLOADPATH.$fileName,),
                            "output"=>$response);
            $this->_log->notice(__FUNCTION__,$arrLog);
        }else{
            $arrLog = array("input"=>array(	"fileName"=> UPLOADPATH.$fileName,),
                            "output"=>$response);
            $this->_log->warning(__FUNCTION__,$arrLog);
        }
    }

    public function generateDownloadLink($fileName)
    {
        $filePath = UPLOADPATH . $fileName;
        
        if (file_exists($filePath)) {
            // Generar el enlace de descarga directa al archivo C:\xampp\htdocs\sgd\log\system\apiFile
            $downloadLink = 'http://' . $_SERVER['HTTP_HOST'] . '/log/system/apiFile/' . $fileName;
            
            return $downloadLink;
        } else {
            // El archivo no existe en el servidor
            return null;
        }
    }

    public function getFile(){
        return $this->file;
    }

    public function getNewName(){
        return $this->newName;
    }
    
    public function getUploadName(){
        return $this->uploadName;
    }

    public function setUploadName($uploadName){
        $this->uploadName = $uploadName;
    }
}
