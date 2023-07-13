<?php

//by: Kevin Santillan
//Esta clase es para la conexion a la base de datos
//Creacion: 13/05/2023

class db{

    private $dbname, $dbhost, $dbuser, $dbpasswd,$dbport;
    private $connectionName, $connection_status,$connection;
    private $numRows, $numCols, $affectedRows, $lastError, $lastOid, $colsNames;
    private $pathlog;

    function __construct($connectionName)
    {
        $this->pathLog = 'C:\xampp\htdocs\sgd\log\db\logDB';
        $this->connectionName = $connectionName;
        if (isset($connectionName)) {
            $this->setParametersBD($connectionName);
        }
    }

    function __desctruct()
    {
        unset($this->dbuser);
        unset($this->dbname);
        unset($this->dbhost);
        unset($this->dbpasswd);
        unset($this->dbport);
        unset($this->connectionName);
        unset($this->connectionStatus);
        $this->close();
    }

    private function printLog($message){
        $message = date("Y-m-d H:i:s")." ".$message; 
        $php = $_SERVER["PHP_SELF"];
        error_log("[$php] ".$message."\n", 3, $this->pathLog);
        return true;    
    }

    
    public function setParametersBD($connectionName)
    {
        $pathDataFile = __DIR__."/config/config.ini";
        $data = parse_ini_file($pathDataFile, true);
        if(array_key_exists($connectionName, $data)) {
            $this->dbuser = $data[$connectionName]["user"];
            $this->dbname = $data[$connectionName]["dbName"];
            $this->dbhost = $data[$connectionName]["host"];
            $this->dbpasswd = $data[$connectionName]["password"];
            $this->dbport=$data[$connectionName]["port"];
            $mensaje ="Datos asignados: user=[$this->dbuser], dbname=[$this->dbname],host=[$this->dbhost],password=[$this->dbpasswd],port=[$this->dbport]";
            $this->printLog("dataBaseLog ". $mensaje." Function: ".__FUNCTION__, "info");
        } else {
            $mensaje ="Conexion invalida [$connectionName]";
            $this->printLog("dataBaseLog ". $mensaje." Function: ".__FUNCTION__, "warning");
        }
        $this->connect();
    }

    private function field_name($result)
    {
        $arr = array();
        for ($i = 0; $i < $this->getNumCols(); $i++) {
            $arr[] = pg_field_name($result, $i);
        }
        return $arr;
    }

    private function setQueryParameters($sql)
    {
        $result = false;
        
        if ($this->getStatus() == TRUE) {
           $result = pg_query($this->connection, $sql);
           if ($result == true) {
                $this->numRows = pg_num_rows($result);
                $this->numCols = pg_num_fields($result);
                $this->affectedRows = pg_affected_rows($result);
                $this->lastOid = pg_last_oid($result);
                $this->lastError = null;                
            } else {
                $this->lastError = pg_last_error($this->connection);
                $this->numRows = null;
                $this->numCols = null;
                $this->affectedRows = null;
                $this->lastOid = null;                    
                $this->printLog("[$sql] [" . $this->lastError ."]");
            }
        } else {
            $this->printLog("[$sql] [Error en conexion]");
        }
        return $result;
    }

    public function query($sql)
    {
        $result = false;
        $res = $this->setQueryParameters($sql);
        
        if (!is_bool($res)) {
            if ($this->lastError === NULL) {
                if($this->numRows == 0 && $this->affectedRows > 0 ){
                    $result = true;
                }
                else{
                    $table = array();
                    while ($row = pg_fetch_assoc($res)) {
                        $table[] = $row; // Agrega cada fila a $result
                    }
                    $this->_nameCols = $this->field_name($res);
                    $result = $table;
                }
            }
            pg_free_result($res); // Libera los recursos de la consulta
        } else {
            $result = $res; // Si $res es un booleano (indicando un error), simplemente lo devuelve
        }
        #var_dump($result);
        return $result;
    }

    public function connect()
    {
        $conn_string = "host=" . $this->dbhost . " port=" . $this->dbport . " dbname=" . $this->dbname . " user=" . $this->dbuser . " password=" . $this->dbpasswd;
        $this->connection = pg_connect($conn_string);
        if ($this->connection) {
            $this->connectionStatus = pg_connection_status($this->connection);
            $mensaje = $this->getStatus();
           
            $this->printLog("dataBaseLog: pg_connection_status: ".$mensaje." - Function: ".__FUNCTION__, "info");
            return true;
        }
        $mensaje = $this->getStatus();
        $this->printLog("dataBaseLog: pg_connection_status: ".$mensaje." - Function: ".__FUNCTION__, "warning");
        return false;

    }

    private function close()
    {
        if (is_resource($this->connection)) {
            pg_close($this->connection);
            unset($this->connection);
        }
    }

    public function getStatus(){
        $response = (pg_connection_status($this->getConexion()) === PGSQL_CONNECTION_OK) ? TRUE : FALSE ;
        return $response;
    }

    public function getConexion()
    {
        return $this->connection;
    }

     public function getNumRows()
    {
        return $this->numRows;
    }

    public function getNumCols()
    {
        return $this->numCols;
    }

    public function getAffectedRows()
    {
        return $this->affectedRows;
    }

    public function getLastError()
    {
        return $this->lastError;
    }

    public function getLastOID()
    {
        return $this->lastOid;
    }

    public function getcolsNames()
    {
        return $this->colsNames;
    }

}