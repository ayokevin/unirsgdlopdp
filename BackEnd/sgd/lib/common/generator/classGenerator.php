<?php

    /*****************
     * API: Programita para generar clases desde la base de datos de Postgres
     * AUTOR: DSANTILLAN
     * FECHA: 2023-05-01
     * ***************/

    class classGenerator {
        private $_db, $_log;
        private $schemaName, $tableNames, $outputDir;

        function __construct($_db, $_log, $schemaName = '', $tableNames  = array(), $outputDir = 'C:\xampp\htdocs\sgd\lib\common\generator') {
            $this->_db = $_db;
            $this->_log = $_log;
            $this->outputDir = $outputDir;
            if($schemaName != '' && is_array($tableNames)){
                $this->schemaName = $schemaName;
                $this->tableNames = $tableNames; //Si el array esta vacio buscara todas las tablas del esquema
            }
        }

        function __destruct(){
            unset($this->_db);
            unset($this->_log);
            unset($this->schemaName);
            unset($this->tableName);
        }

        public function generateClasses() {
            // Obtener las tablas en el esquema
            $tables = $this->verifyTables();
            var_dump($tables);
            foreach ($tables as $table) {
                $tableName = $table['table_name'];
                $satitizateTableName = str_replace('_', '', ucwords(strtolower($tableName), '_'));
                $className = lcfirst($satitizateTableName);

                // Definir la clase
                $classCode = "<?php\n\tclass $className {\n";
                $classCode .= "\t\tprivate \$_db, \$_log;\n";
                $gettersCode = "";
                $constructCode = "\t\tfunction __construct(\$_db, \$_log";
                $destructCode = "\t\tfunction __destruct(){\n".
                                "\t\t\tunset(\$this->_db);\n".
                                "\t\t\tunset(\$this->_log);\n";
                $constructIfCode = "if(";
                $construct_set_code = "\$this->set".$satitizateTableName."(";
                $setCode = "\t\tfunction set$satitizateTableName(";
                $find_code = "\t\tfunction find$satitizateTableName(";
                $mapCode = "\t\tfunction map$satitizateTableName(\$rs){\n";

                $columns = $this->verifyColumns($tableName);
                $intermediateData = $this->verifyIntermediateTable($columns, $tableName);

                if(is_array($intermediateData)){
                    $inputVariables = "";
                    $input_array_code = "";
                    $sql_where_code = "";
                    foreach($intermediateData as $key => $foreignKey){
                        $inputVariable = lcfirst(str_replace('_', '', ucwords(strtolower($foreignKey), '_')))."Id";
                        $constructCode .= ", \$$inputVariable";
                        $constructIfCode .= ($key == 0)?"\$".$inputVariable." != 0":" && \$".$inputVariable." != 0";
                        $inputVariables .= ($key == 0)?"\$".$inputVariable:", \$".$inputVariable;
                        $input_array_code .= ($key == 0)?"\"$inputVariable\"=>\$$inputVariable":", \"$inputVariable\"=>\$$inputVariable";
                        $sql_where_code .= ($key == 0)?"$foreignKey"."_id"." = \$$inputVariable":" AND $foreignKey"."_id"." = \$$inputVariable"; ;
                    }
                    $constructCode .= "){\n";
                    $constructIfCode .= "){\n";
                    $construct_set_code .= $inputVariables.");\n";                
                    $setCode .= "$inputVariables){\n";
                    $setCode .=   "\t\t\t\$response = FALSE;\n".
                                    "\t\t\t\$data$satitizateTableName = \$this->find$satitizateTableName($inputVariables);\n".
                                    "\t\t\tif(\$data$satitizateTableName){\n".
                                        "\t\t\t\t\$this->map$satitizateTableName(\$data$satitizateTableName);\n".
                                        "\t\t\t\t\$response = TRUE;\n".
                                    "\t\t\t}\n".
                                    "\t\t\t\$arrLog = array(\"input\"=>array($input_array_code), \"output\"=>\$response);\n".
                                    "\t\t\t\$this->_log->warning(__METHOD__,\$arrLog);\n".
                                    "\t\t\treturn \$response;\n\t\t}\n\n";
                    $find_code .= "$inputVariables){\n";
                    $find_code .=   "\t\t\t\$response = false;\n".
                                    "\t\t\t\$sql =  \"SELECT * FROM {$this->schemaName}.{$tableName} WHERE $sql_where_code\";\n\n".
                                            
                                    "\t\t\t\$rs = \$this->_db->query(\$sql);\n".
                                    "\t\t\tif(\$rs){\n".
                                        "\t\t\t\t\$response = \$rs[0];\n".
                                    "\t\t\t}\n".
                                    "\t\t\telse{\n".
                                        "\t\t\t\t\$arrLog = array(\"input\"=>array($input_array_code), \"sql\"=>\$sql, \"error\"=>\$this->_db->getLastError());\n".
                                        "\t\t\t\t\$this->_log->error(__METHOD__,\$arrLog);\n". 
                                    "\t\t\t}\n".
                                    "\t\t\t\$arrLog = array(\"input\"=>array($input_array_code), \"output\"=>\$response, \"sql\"=>\$sql);\n".
                                    "\t\t\t\$this->_log->debug(__METHOD__,\$arrLog);\n".
                                    "\t\t\treturn \$response;\n\t\t}\n\n";
                }
                else{
                    $inputVariable = lcfirst($satitizateTableName)."Id";
                    $constructCode .= ", \$$inputVariable = 0"."){\n";
                    $constructIfCode .= "\$".$inputVariable." != 0){\n";
                    $construct_set_code .= "\$$inputVariable);\n";
                    $setCode .= "\$$inputVariable){\n";
                    $setCode .=    "\t\t\t\$response = FALSE;\n".
                                    "\t\t\t\$data$satitizateTableName = \$this->find$satitizateTableName(\$$inputVariable);\n".
                                    "\t\t\tif(\$data$satitizateTableName){\n".
                                        "\t\t\t\t\$this->map$satitizateTableName(\$data$satitizateTableName);\n".
                                        "\t\t\t\t\$response = TRUE;\n".
                                    "\t\t\t}\n".
                                    "\t\t\t\$arrLog = array(\"input\"=>array(\"$inputVariable\"=>\$$inputVariable), \"output\"=>\$response);\n".
                                    "\t\t\t\$this->_log->warning(__METHOD__,\$arrLog);\n".
                                    "\t\t\treturn \$response;\n\t\t}\n\n";
                    $find_code .= "\$$inputVariable){\n";
                    $find_code .=   "\t\t\t\$response = false;\n".
                                "\t\t\t\$sql =  \"SELECT * FROM {$this->schemaName}.{$tableName} WHERE $tableName"."_id"." = \$$inputVariable\";\n\n".
                                        
                                "\t\t\t\$rs = \$this->_db->query(\$sql);\n".
                                "\t\t\tif(\$rs){\n".
                                    "\t\t\t\t\$response = \$rs[0];\n".
                                "\t\t\t}\n".
                                "\t\t\telse{\n".
                                    "\t\t\t\t\$arrLog = array(\"input\"=>array(\"$inputVariable\"=>\$$inputVariable), \"sql\"=>\$sql, \"error\"=>\$this->_db->getLastError());\n".
                                    "\t\t\t\t\$this->_log->error(__METHOD__,\$arrLog);\n". 
                                "\t\t\t}\n".
                                "\t\t\t\$arrLog = array(\"input\"=>array(\"$inputVariable\"=>\$$inputVariable), \"output\"=>\$response, \"sql\"=>\$sql);\n".
                                "\t\t\t\$this->_log->debug(__METHOD__,\$arrLog);\n".
                                "\t\t\treturn \$response;\n\t\t}\n\n";
                }
                $constructCode .= "\t\t\t\$this->_db = \$_db;\n";
                $constructCode .= "\t\t\t\$this->_log = \$_log;\n";
                $constructCode .= "\t\t\t$constructIfCode";
                $constructCode .= "\t\t\t\t$construct_set_code";
                $constructCode .= "\t\t\t}\n\t\t}\n\n";

                foreach ($columns as $key => $column) {

                    //var_dump($column);
                    $columnName = lcfirst(str_replace('_', '', ucwords(strtolower($column['column_name']), '_')));
                    $varColumnName = "";

                    if($column['referenced_table'] != NULL){
                        $varColumnName = "_".substr($columnName, 0, -2);
                        $gettersCode .= "\t\tpublic function get".ucwords(substr($columnName, 0, -2))."() { return \$this->$varColumnName; }\n";
                        $mapCode .= "\t\t\t\$this->$varColumnName = new ".substr($columnName, 0, -2)."(\$this->_db, \$this->_log, \$rs['{$column['column_name']}']);\n";
                    }
                    else{
                        $varColumnName = $columnName;
                        $gettersCode .= "\t\tpublic function get".ucwords($columnName)."() { return \$this->$varColumnName; }\n";
                        $mapCode .= "\t\t\t\$this->$varColumnName = \$rs['{$column['column_name']}'];\n";
                    }

                    $classCode .= ($key == 0)?"\t\tprivate \$$varColumnName" :  ", \$$varColumnName";
                    $destructCode .= "\t\t\tunset(\$this->$varColumnName);\n";
            
                }
                
                $mapCode .= "\t\t}\n\n";
                $classCode .= ";\n\n";
                $destructCode .= "\t\t}\n\n";

                $classCode .= $constructCode.$destructCode.$setCode.$find_code.$mapCode.$gettersCode."\t}";
                
                $filename = $this->outputDir . "/{$className}.php";
                file_put_contents($filename, $classCode);
            }  
        }

        private function verifyTables(){
            if(count($this->tableNames) == 0){
                $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema='{$this->schemaName}' order by 1 asc";
                $result = $this->_db->query($sql);
            }
            else{
                $sql = "SELECT table_name FROM information_schema.tables WHERE  table_schema='{$this->schemaName}' and table_name in('".implode("','", $this->tableNames)."') order by 1 asc";
                $result = $this->_db->query($sql);
            }

            if($this->_db->getLastError()) {
                $arrLog = array("sql"=>$sql,
                                "error"=>$this->_db->getLastError());
                $this->_log->error(__METHOD__,$arrLog);  
            } else {
                $arrLog = array("output"=>$result,
                                "sql"=>$sql);
                $this->_log->debug(__METHOD__,$arrLog); 
            }
            
            return $result;
        }

        private function verifyColumns($tableName){

            $sql = "select distinct on(a.attnum) ".
                        "a.attname AS column_name, ".
                        "t.typname AS data_type, ".
                        "e.contype as constraint_type, ".
                        "a.attnotnull AS is_null, ".
                        "f.relname AS referenced_table ".
                    "FROM ".
                        "pg_attribute AS a ".
                        "JOIN pg_type AS t ON a.atttypid = t.oid ".
                        "LEFT JOIN pg_attrdef AS d ON a.attrelid = d.adrelid AND a.attnum = d.adnum ".
                        "LEFT JOIN pg_constraint AS e ON a.attrelid = e.conrelid AND a.attnum = ANY (e.conkey) ".
                        "LEFT JOIN pg_class AS f ON e.confrelid = f.oid ".
                    "WHERE ".
                        "a.attnum > 0 AND NOT a.attisdropped ".
                        "AND a.attrelid::regclass = '{$this->schemaName}.$tableName'::regclass ".
                    "ORDER BY a.attnum, f.relname;";
                    
            $result = $this->_db->query($sql);

            if($this->_db->getLastError()) {
                $arrLog = array("input"=>$tableName,
                                "sql"=>$sql,
                                "error"=>$this->_db->getLastError());
                $this->_log->error(__METHOD__,$arrLog);  
            } else {
                $arrLog = array("input"=>$tableName,
                                "output"=>$result,
                                "sql"=>$sql);
                $this->_log->debug(__METHOD__,$arrLog); 
            }
            
            return $result;
        }

        private function verifyIntermediateTable($columns, $tableName){
            $response = false;

            foreach ($columns as $row) {
                if(substr($row['column_name'], -3) == '_id'){
                    $keyNames[] = substr($row['column_name'], 0, -3);
                }
            }

        $combinations = array();

            // Iterar sobre cada par de strings y crear las combinaciones posibles
            foreach ($keyNames as $i => $string1) {
                foreach ($keyNames as $j => $string2) {
                    if ($i !== $j) { // Evitar duplicados
                        $combinations[] = implode("_", array($string1, $string2));
                    }
                }
            }

            return in_array($tableName, $combinations)? $keyNames : false;
        }
    }

    define("DBCONECTION","CONPG");
    define("LOGPATH",'C:\xampp\htdocs\sgd\lib\common\generator\classGenerator.log');
    define("LOGLEVEL","100");

    include_once('C:\xampp\htdocs\sgd\lib\common\log.php');
    include_once('C:\xampp\htdocs\sgd\lib\db\db.php');

    $_db = new db(DBCONECTION);
    $_log = new log(LOGLEVEL,LOGPATH);

    // $tableNames = array('data_client');

    // $generator = new ClassGenerator($_db, $_log, 'gw_sms', $tableNames);
    $generator = new ClassGenerator($_db, $_log, 'system');
    $generator->generateClasses();