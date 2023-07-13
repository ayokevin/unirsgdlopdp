<?php
require_once ('C://xampp/htdocs/sgd/lib/system/action/action.php');
require_once ('C://xampp/htdocs/sgd/lib/system/article/article.php');
require_once ('C://xampp/htdocs/sgd/lib/system/process/process.php');
require_once ('C://xampp/htdocs/sgd/lib/common/fileToSever/fileToServer.php');
require_once ('C://xampp/htdocs/sgd/lib/common/reference/reference.php');
	class file {
		private $_db, $_log;
		private $fileId, $_article, $_process, $fileDate, $fileName,$fileNamed , $fileNameDb, $_action, $_status;

		function __construct($_db, $_log, $fileId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($fileId != 0){
				$this->setFile($fileId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->fileId);
			unset($this->_article);
			unset($this->_process);
			unset($this->fileDate);
			unset($this->fileName);
			unset($this->fileNamed);
			unset($this->fileNameDb);
			unset($this->_action);
			unset($this->_status);
		}

		function setFile($fileId){
			$response = FALSE;
			$dataFile = $this->findFile($fileId);
			if($dataFile){
				$this->mapFile($dataFile);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("fileId"=>$fileId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findFile($fileId){
			$response = false;
			$sql =  "SELECT * FROM system.file WHERE file_id = $fileId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("fileId"=>$fileId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("fileId"=>$fileId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapFile($rs){
			$this->fileId = $rs['file_id'];
			$this->_article = new article($this->_db, $this->_log, $rs['article_id']);
			$this->_process = new process($this->_db, $this->_log, $rs['process_id']);
			$this->fileDate = $rs['file_date'];
			$this->fileName = $rs['file_name'];
			$this->fileName = $rs['file_named'];
			$this->fileNameDb = $rs['file_name_db'];
			$this->_action = new action($this->_db, $this->_log, $rs['action_id']);
			$this->_status = new reference($this->_db, $this->_log, $rs['status_id']);
		}

		public function generateUniqueFileName($articleId, $processId, $actionId){
			$uniqueName = $articleId . '.' . $processId . '.' . $actionId . '.' . time();
			return $uniqueName;
		}

		public function insertFile($_article,$_process,$file,$fileName,$_action,$_status){
			$response = false;
			$fileUploader = new fileToServer($this->_db, $this->_log);
			$ext = pathinfo($file->name, PATHINFO_EXTENSION);
			$fileNameDb = $this->generateUniqueFileName($_article, $_process, $_action);
			$fileUploader->setFileToDataBase($file, $fileNameDb);
			$uploadedFileName = $fileUploader->getUploadName();

			if($_article !== ''){
				$sql =  "INSERT INTO system.file ".
						"(article_id, file_date, file_name, file_named, file_name_db,status_id) ".
						"VALUES($_article, CURRENT_TIMESTAMP, '{$file->name}', '$fileName' ,'{$fileNameDb}.{$ext}',$_status) ".
						"returning file_id;";
			}else if($_process !== ''){
				$sql =  "INSERT INTO system.file ".
						"(process_id, file_date, file_name, file_named, file_name_db,status_id) ".
						"VALUES($_process, CURRENT_TIMESTAMP, '{$file->name}', '$fileName' ,'{$fileNameDb}.{$ext}',$_status) ".
						"returning file_id;";
			}else if($_action !== ''){
				$sql =  "INSERT INTO system.file ".
						"(file_date, file_name, file_named, file_name_db, action_id,status_id) ".
						"VALUES(CURRENT_TIMESTAMP, '{$file->name}', '$fileName' ,'{$fileNameDb}.{$ext}',  $_action,$_status) ".
						"returning file_id;";
			}
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setFile($rs[0]['file_id']):true;
				$arrLog = array("input"=>array( "file_id"=> $rs[0]['file_id'],
												"_article"=>$_article,
												"_process"=>$_process,
												"file_name"=>$file->name,
												"file_name_db"=>$fileNameDb,
												
											),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "_article"=>$_article,
												"_process"=>$_process,
												"file_name"=>$file->name,
												"file_name_db"=>$fileNameDb,
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function listFileArticle($clientId,$articleId){
			$response = false;
				$sql =  "SELECT f.file_id, f.article_id, a.article_name, f.file_date, f.file_named, f.file_name, f.file_name_db, ".
						"f.status_id, r.reference_name ".
						"FROM system.file f,system.article a ,common.reference r ,system.project p ".
						"where f.article_id =a.article_id ".
						"and a.project_id =p.project_id ".
						"and f.status_id =r.reference_id ".
						"and p.client_id =$clientId ".
						"and a.article_id = $articleId ".
						"order by 1 asc;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = true;
				$arrLog = array("input"=>array( "data"=> $rs),
								"output"=>$response,
								"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array(	"data"=> 'No se encuentra la tabla'),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $rs;
		}

		public function listFileProcess($clientId,$processId){
			$response = false;
				$sql =  "SELECT f.file_id, p.process_id ,p.process_name , f.file_date, f.file_named, f.file_name, f.file_name_db, ". 
						"f.status_id, r.reference_name ".
						"FROM system.file f,system.process p ,common.reference r ,common.department d ".
						"where f.process_id = p.process_id ".
						"and f.status_id =r.reference_id ".
						"and p.department_id =d.department_id ".
						"and d.client_id =$clientId ".
						"and p.process_id = $processId ".
						"order by 1 asc;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = true;
				$arrLog = array("input"=>array( "data"=> $rs),
								"output"=>$response,
								"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array(	"data"=> 'No se encuentra la tabla'),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $rs;
		}

		public function listFileActions($clientId,$actionId){
			$response = false;
				$sql =  "SELECT f.file_id, a.action_id,a.action_name , f.file_date, f.file_name, f.file_named , f.file_name_db, ".
						"f.status_id, r.reference_name ".
						"FROM system.file f,system.action a ,common.reference r ,common.department d ".
						"where f.action_id = a.action_id ".
						"and f.status_id =r.reference_id ".
						"and a.department_id = d.department_id ".
						"and d.client_id = $clientId ".
						"and a.action_id = $actionId ".
						"order by 1 asc;"; 
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = true;
				$arrLog = array("input"=>array( "data"=> $rs),
								"output"=>$response,
								"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array(	"data"=> 'No se encuentra la tabla'),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $rs;
		}

		public function updateFile($fileId,$_article,$_process,$file,$fileName,$_action,$_status){
			$response = false;

			if(!empty($file)){
				$ext = pathinfo($file->name, PATHINFO_EXTENSION);
				$fileNameDb = $this->generateUniqueFileName($_article, $_process, $_action);
				$fileUploader = new fileToServer($this->_db, $this->_log,$fileNameDb,$file);
				$fileUploader->setFileToDataBase($file, $fileNameDb);

				$this->setFile($fileId);
				$oldFileNameDb = $this->getFileNameDb();
				// Eliminar el archivo antiguo
				
				
				if($_article !==''){
					$sql =  "UPDATE system.file ".
							"SET article_id=$_article , file_date=CURRENT_TIMESTAMP, file_name='{$file->name}', ".
							"file_name_db='{$fileNameDb}.{$ext}',file_named='$fileName' , status_id=$_status ".
							"WHERE file_id=$fileId returning file_id;";
				}else if($_process!==''){
					$sql =  "UPDATE system.file ".
							"SET process_id=$_process , file_date=CURRENT_TIMESTAMP, file_name='{$file->name}', ".
							"file_name_db='{$fileNameDb}.{$ext}',file_named='$fileName' , status_id=$_status ".
							"WHERE file_id=$fileId returning file_id;";
				}else if($_action!==''){
					$sql =  "UPDATE system.file ".
							"SET action_id=$_action , file_date=CURRENT_TIMESTAMP, file_name='{$file->name}', ".
							"file_name_db='{$fileNameDb}.{$ext}',file_named='$fileName' , status_id=$_status ".
							"WHERE file_id=$fileId returning file_id;";
				}
				if ($oldFileNameDb) {
					$fileUploader->deleteFileFromServer($oldFileNameDb);
				}
			}else{
					$sql =  "UPDATE system.file ".
								"SET status_id=$_status,file_named='$fileName',file_date=CURRENT_TIMESTAMP ".
								"WHERE file_id=$fileId returning file_id;";
			}
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setFile($rs[0]['file_id']):true;
				$arrLog = array("input"=>array(	"file_id"=> $rs[0]['file_id'],
												"_article"=>$_article,
												"_process"=>$_process,
												"file_name"=>$file->name,
												"file_name_db"=>$fileNameDb
											),
									"output"=>$response,
									"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array(	"file_id"=> $rs[0]['file_id'],
												"_article"=>$_article,
												"_process"=>$_process,
												"file_name"=>$file->name,
												"file_name_db"=>$fileNameDb
											),
									"sql"=>$sql,
									"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function downloadFile($fileId){
			$response = false;
				$sql =  "SELECT EXISTS ( ".
					"SELECT 1 ".
					"FROM system.file ".
					"WHERE file_id = '$fileId' ".
					") AS registro_existe, ".
					"file_name_db, ".
					"file_name ".
					"FROM system.file ".
					"WHERE file_id = '$fileId';"; 
					
			$rs = $this->_db->query($sql);
			if($rs) {
				
				$fileUploader = new fileToServer($this->_db, $this->_log);
				
				$response = $fileUploader->generateDownloadLink($rs[0]['file_name_db']);
				
				$arrLog = array("input"=>array( "data"=> $rs),
								"output"=>$response,
								"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array(	"data"=> 'No se encuentra el registro'),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
				
			}

			return $response;
		}

		public function getFileId() { return $this->fileId; }
		public function getArticle() { return $this->_article; }
		public function getProcess() { return $this->_process; }
		public function getFileDate() { return $this->fileDate; }
		public function getFileName() { return $this->fileName; }
		public function getFileNamed() { return $this->fileNamed; }
		public function getFileNameDb() { return $this->fileNameDb; }
		public function getAction() { return $this->_action; }
		public function getStatus() { return $this->_status; }
	}