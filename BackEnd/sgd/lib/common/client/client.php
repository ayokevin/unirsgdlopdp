<?php
require_once ('C://xampp/htdocs/sgd/lib/common/reference/reference.php');
	class client {
		private $_db, $_log;
		private $clientId, $clientRuc, $clientName, $_statusId;

		function __construct($_db, $_log, $clientId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($clientId != 0){
				$this->setClient($clientId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->clientId);
			unset($this->clientRuc);
			unset($this->clientName);
			unset($this->_statusId);
		}

		function setClient($clientId){
			$response = FALSE;
			$dataClient = $this->findClient($clientId);
			if($dataClient){
				$this->mapClient($dataClient);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("clientId"=>$clientId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findClient($clientId){
			$response = false;
			$sql =  "SELECT * FROM common.client WHERE client_id = $clientId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("clientId"=>$clientId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("clientId"=>$clientId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapClient($rs){
			$this->clientId = $rs['client_id'];
			$this->clientRuc = $rs['client_ruc'];
			$this->clientName = $rs['client_name'];
			$this->_statusId = new reference($this->_db, $this->_log, $rs['status_id']);
		}

		public function insertClient($clientName,$clientRuc,$_statusId){
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

		public function getClientId() { return $this->clientId; }
		public function getClientRuc() { return $this->clientRuc; }
		public function getClientName() { return $this->clientName; }
		public function getClientStatus() { return $this->_statusId; }
	}