<?php
	class file {
		private $_db, $_log;
		private $fileId, $_article, $_process, $fileDate, $fileName, $fileNameDb, $_action, $_status, $fileNamed;

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
			unset($this->fileNameDb);
			unset($this->_action);
			unset($this->_status);
			unset($this->fileNamed);
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
			$this->fileNameDb = $rs['file_name_db'];
			$this->_action = new action($this->_db, $this->_log, $rs['action_id']);
			$this->_status = new status($this->_db, $this->_log, $rs['status_id']);
			$this->fileNamed = $rs['file_named'];
		}

		public function getFileId() { return $this->fileId; }
		public function getArticle() { return $this->_article; }
		public function getProcess() { return $this->_process; }
		public function getFileDate() { return $this->fileDate; }
		public function getFileName() { return $this->fileName; }
		public function getFileNameDb() { return $this->fileNameDb; }
		public function getAction() { return $this->_action; }
		public function getStatus() { return $this->_status; }
		public function getFileNamed() { return $this->fileNamed; }
	}