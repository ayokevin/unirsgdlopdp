<?php
require_once ('C://xampp/htdocs/sgd/lib/common/reference/reference.php');
	class rolUserSec {
		private $_db, $_log;
		private $rolUserSecId, $_userSec, $_rol, $_status;

		function __construct($_db, $_log, $rolUserSecId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($rolUserSecId != 0){
				$this->setRolUserSec($rolUserSecId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->rolUserSecId);
			unset($this->_userSec);
			unset($this->_rol);
			unset($this->_status);
		}

		function setRolUserSec($rolUserSecId, $userSecId, $rolId, $statusId){
			$response = FALSE;
			$dataRolUserSec = $this->findRolUserSec($rolUserSecId);
			if($dataRolUserSec){
				$this->mapRolUserSec($dataRolUserSec);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("rolUserSecId"=>$rolUserSecId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findRolUserSec($rolUserSecId){
			$response = false;
			$sql =  "SELECT * FROM security.rol_user_sec WHERE rol_user_sec_id = $rolUserSecId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("rolUserSecId"=>$rolUserSecId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("rolUserSecId"=>$rolUserSecId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapRolUserSec($rs){
			$this->rolUserSecId = $rs['rol_user_sec_id'];
			$this->_userSec = new userSec($this->_db, $this->_log, $rs['user_sec_id']);
			$this->_rol = new rol($this->_db, $this->_log, $rs['rol_id']);
			$this->_status = new reference($this->_db, $this->_log, $rs['status_id']);
		}

		public function checkRolUserSec($userSecId){
			$response = false;
			$sql =  "SELECT * ".
					"FROM ".'security'.".rol_user_sec ".
					"where user_sec_id  = $userSecId;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setRolUserSec($rs[0]['rol_user_sec_id']):true;
				$arrLog = array("input"=>array( "user_sec_id"=>$userSecId),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "user_sec_id"=>$userSecId),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function insertUserSec($clientName,$clientRuc,$_statusId){
			$response = false;
			$sql =  "INSERT INTO common.client(client_name, client_ruc, status_id ) ".
					"VALUES ('$clientName','$clientRuc',$_statusId) returning client_id,status_id";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setClient($rs[0]['client_id']):true;
				$arrLog = array("input"=>array( "clientName"=>$clientName,
												"clientRuc",$clientRuc,
												"statusId",$_statusId),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "clientName"=>$clientName,
												"clientRuc",$clientRuc,
												"statusId",$_statusId),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function updateClient($clientId,$clientName,$clientRuc,$_statusId){
			$response = false;
			$sql =  "UPDATE common.client ".
			"SET client_ruc='$clientRuc', client_name='$clientName', status_id=$_statusId ".
			"WHERE client_id=$clientId returning client_id,client_name,client_ruc,status_id ;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setClient($rs[0]['client_id']):true;
				$arrLog = array("input"=>array( "clientId"=>$clientId,
												"clientName"=>$clientName,
												"clientRuc",$clientRuc,
												"statusId",$_statusId),
								"output"=>$response,
								"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array(	"clientId"=>$clientId,
												"clientName"=>$clientName,
												"clientRuc",$clientRuc,
												"statusId",$_statusId),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function listClient(){
			$response = false;
			$sql =  "SELECT c.client_id, c.client_ruc, c.client_name, c.status_id, r.reference_name ".
					"FROM common.client c, common.reference r ".
					"WHERE c.status_id = r.reference_id order by 1 asc;";
					
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

		

		public function getRolUserSecId() { return $this->rolUserSecId; }
		public function getUserSec() { return $this->_userSec; }
		public function getRol() { return $this->_rol; }
		public function getStatus() { return $this->_status; }
	}