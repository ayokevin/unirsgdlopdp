<?php
	class rolOption {
		private $_db, $_log;
		private $rolOptionId, $_options, $_rol, $_status;

		function __construct($_db, $_log, $rolOptionId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($rolOptionId != 0){
				$this->setRolOption($rolOptionId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->rolOptionId);
			unset($this->_options);
			unset($this->_rol);
			unset($this->_status);
		}

		function setRolOption($rolOptionId){
			$response = FALSE;
			$dataRolOption = $this->findRolOption($rolOptionId);
			if($dataRolOption){
				$this->mapRolOption($dataRolOption);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("rolOptionId"=>$rolOptionId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findRolOption($rolOptionId){
			$response = false;
			$sql =  "SELECT * FROM security.rol_option WHERE rol_option_id = $rolOptionId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("rolOptionId"=>$rolOptionId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("rolOptionId"=>$rolOptionId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapRolOption($rs){
			$this->rolOptionId = $rs['rol_option_id'];
			$this->_options = new options($this->_db, $this->_log, $rs['options_id']);
			$this->_rol = new rol($this->_db, $this->_log, $rs['rol_id']);
			$this->_status = new status($this->_db, $this->_log, $rs['status_id']);
		}

		public function getRolOptionId() { return $this->rolOptionId; }
		public function getOptions() { return $this->_options; }
		public function getRol() { return $this->_rol; }
		public function getStatus() { return $this->_status; }
	}