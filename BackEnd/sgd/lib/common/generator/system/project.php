<?php
	class project {
		private $_db, $_log;
		private $projectId, $_client, $_reference, $projectName;

		function __construct($_db, $_log, $projectId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($projectId != 0){
				$this->setProject($projectId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->projectId);
			unset($this->_client);
			unset($this->_reference);
			unset($this->projectName);
		}

		function setProject($projectId){
			$response = FALSE;
			$dataProject = $this->findProject($projectId);
			if($dataProject){
				$this->mapProject($dataProject);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("projectId"=>$projectId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findProject($projectId){
			$response = false;
			$sql =  "SELECT * FROM system.project WHERE project_id = $projectId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("projectId"=>$projectId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("projectId"=>$projectId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapProject($rs){
			$this->projectId = $rs['project_id'];
			$this->_client = new client($this->_db, $this->_log, $rs['client_id']);
			$this->_reference = new reference($this->_db, $this->_log, $rs['reference_id']);
			$this->projectName = $rs['project_name'];
		}

		public function getProjectId() { return $this->projectId; }
		public function getClient() { return $this->_client; }
		public function getReference() { return $this->_reference; }
		public function getProjectName() { return $this->projectName; }
	}