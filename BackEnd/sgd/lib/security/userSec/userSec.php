<?php
require_once ('C://xampp/htdocs/sgd/lib/common/reference/reference.php');
require_once ('C://xampp/htdocs/sgd/lib/common/client/client.php');
require_once ('C://xampp/htdocs/sgd/lib/security/rolUserSec/rolUserSec.php');
	class userSec {
		private $_db, $_log;
		private $userSecId, $_statusId, $userEmail, $userFirstName, $userLastName, $userApplication, $userPassword,$_clientId;

		function __construct($_db, $_log, $userSecId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($userSecId != 0){
				$this->setUserSec($userSecId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->userSecId);
			unset($this->_statusId);
			unset($this->userEmail);
			unset($this->userFirstName);
			unset($this->userLastName);
			unset($this->userApplication);
			unset($this->userPassword);
			unset($this->_clientId);
		}

		function setUserSec($userSecId){
			$response = FALSE;
			$dataUserSec = $this->findUserSec($userSecId);
			if($dataUserSec){
				$this->mapUserSec($dataUserSec);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("userSecId"=>$userSecId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findUserSec($userSecId){
			$response = false;
			$sql =  "SELECT * FROM security.user_sec WHERE user_sec_id = $userSecId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("userSecId"=>$userSecId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("userSecId"=>$userSecId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapUserSec($rs){
			$this->userSecId = $rs['user_sec_id'];
			$this->_statusId = new reference($this->_db, $this->_log, $rs['status_id']);
			$this->userEmail = $rs['user_email'];
			$this->userFirstName = $rs['user_first_name'];
			$this->userLastName = $rs['user_last_name'];
			$this->userApplication = $rs['user_application'];
			$this->userPassword = $rs['user_password'];
			$this->_clientId = new client($this->_db, $this->_log, $rs['client_id']);
		}

		public function checkUserSec($userEmail,$userPassword){
			$response = false;
			$sql =  "SELECT user_sec_id ".
			"FROM ".'security'.".user_sec ".
			"where user_email like '%$userEmail%' and user_password like '%$userPassword%' and user_application='true';";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setUserSec($rs[0]['user_sec_id']):true;
				$arrLog = array("input"=>array( "userEmail"=>$userEmail,
												"userPassword",$userPassword),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "userEmail"=>$userEmail,
												"userPassword",$userPassword),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function insertUserSec($_statusId, $userEmail, $userFirstName, $userLastName, $userApplication, $userPassword,$_clientId){
			$response = false;
			$sql =  "INSERT INTO security.user_sec ".
			"(status_id, user_email, user_first_name, user_last_name, user_application, user_password, client_id) ".
			"VALUES($_statusId, '$userEmail', '$userFirstName', '$userLastName', '$userApplication', '$userPassword', $_clientId) returning user_sec_id;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setUserSec($rs[0]['user_sec_id']):true;
				$arrLog = array("input"=>array( "_statusId"=>$_statusId,
												"userEmail"=>$userEmail,
												"userFirstName"=>$userFirstName,
												"userLastName"=>$userLastName,
												"userApplication"=>$userApplication,
												"userPassword"=>$userPassword,
												"_clientId"=>$_clientId
											),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "_statusId"=>$_statusId,
												"userEmail"=>$userEmail,
												"userFirstName"=>$userFirstName,
												"userLastName"=>$userLastName,
												"userApplication"=>$userApplication,
												"userPassword"=>$userPassword,
												"_clientId"=>$_clientId
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function updateUserSec($userSecId, $_statusId, $userEmail, $userFirstName, $userLastName, $userApplication, $userPassword,$_clientId){
			$response = false;
			$sql =  "UPDATE security.user_sec ".
			"SET status_id=$_statusId, user_email='$userEmail', user_first_name='$userFirstName', user_last_name='$userLastName', user_application='$userApplication', user_password='$userPassword', client_id=$_clientId ".
			"WHERE user_sec_id=$userSecId returning user_sec_id,status_id,user_email,user_first_name,user_last_name,user_application,user_password,client_id;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setUserSec($rs[0]['user_sec_id']):true;
				$arrLog = array("input"=>array( "userSecId"=>$userSecId,
												"_statusId"=>$_statusId,
												"userEmail"=>$userEmail,
												"userFirstName"=>$userFirstName,
												"userLastName"=>$userLastName,
												"userApplication"=>$userApplication,
												"userPassword"=>$userPassword,
												"_clientId"=>$_clientId
											),
								"output"=>$response,
								"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "userSecId"=>$userSecId,
												"_statusId"=>$_statusId,
												"userEmail"=>$userEmail,
												"userFirstName"=>$userFirstName,
												"userLastName"=>$userLastName,
												"userApplication"=>$userApplication,
												"userPassword"=>$userPassword,
												"_clientId"=>$_clientId
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function listUserSec($client_id){
			$response = false;
			if($client_id===''){
				$sql =  "SELECT us.user_sec_id,us.status_id,us.user_email,us.user_first_name,us.user_last_name,us.user_application,us.user_password,us.client_id,r.reference_name,c.client_name ".
				"FROM security.user_sec us, common.reference r, common.client c ".
				"WHERE us.status_id=r.reference_id and us.client_id=c.client_id order by 1 asc;";
			}else{
				$sql =  "SELECT us.user_sec_id,us.status_id,us.user_email,us.user_first_name,us.user_last_name,us.user_application,us.user_password,us.client_id,r.reference_name,c.client_name ".
				"FROM security.user_sec us, common.reference r, common.client c ".
				"WHERE us.status_id=r.reference_id and us.client_id=c.client_id and us.client_id=$client_id order by 1 asc;";
			}
			
					
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

		public function getUserSecId() { return $this->userSecId; }
		public function getStatus() { return $this->_statusId; }
		public function getUserEmail() { return $this->userEmail; }
		public function getUserFirstName() { return $this->userFirstName; }
		public function getUserLastName() { return $this->userLastName; }
		public function getUserApplication() { return $this->userApplication; }
		public function getUserPassword() { return $this->userPassword; }
		public function getClientId() { return $this->_clientId; }
	}