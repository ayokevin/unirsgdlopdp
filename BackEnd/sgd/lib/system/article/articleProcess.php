<?php
require_once ('C://xampp/htdocs/sgd/lib/system/process/process.php');
require_once ('C://xampp/htdocs/sgd/lib/system/article/article.php');
	class articleProcess {
		private $_db, $_log;
		private $_process, $_article;

		function __construct($_db, $_log, $processId=0, $articleId=0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($processId != 0 && $articleId != 0){
				$this->setArticleProcess($processId, $articleId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->articleProcess);
			unset($this->_process);
			unset($this->_article);
		}

		function setArticleProcess($processId, $articleId){
			$response = FALSE;
			$dataArticleProcess = $this->findArticleProcess($processId, $articleId);
			if($dataArticleProcess){
				$this->mapArticleProcess($dataArticleProcess);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("processId"=>$processId, "articleId"=>$articleId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findArticleProcess($processId, $articleId){
			$response = false;
			$sql =  "SELECT * FROM system.article_process WHERE process_id = $processId AND article_id = $articleId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("processId"=>$processId, "articleId"=>$articleId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("processId"=>$processId, "articleId"=>$articleId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapArticleProcess($rs){
			$this->_process = new process($this->_db, $this->_log, $rs['process_id']);
			$this->_article = new article($this->_db, $this->_log, $rs['article_id']);
		}

		public function insertArticleProcess($_process,$_article){
			$response = false;
			$sql =  "INSERT INTO system.article_process (process_id, article_id) ".
					"SELECT $_process, $_article ".
					"WHERE NOT EXISTS ( ".
					"SELECT 1 FROM system.article_process ".
					"WHERE process_id = $_process AND article_id = $_article ".
					") returning process_id,article_id;"; 
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setArticleProcess($rs[0]['process_id'],$rs[0]['article_id']):true;
				$arrLog = array("input"=>array( "process_id"=>$_process,
												"article_id"=>$_article
											),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "process_id"=>$_process,
												"article_id"=>$_article
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function updateArticleProcess($_processOld,$_articleOld, $_processNew,$_articleNew){
			$response = false;
			$sql =  "UPDATE system.article_process ".
					"SET process_id = $_processNew, article_id = $_articleNew ".
					"WHERE process_id = $_processOld AND article_id = $_articleOld ".
					"AND NOT EXISTS ( ".
					"SELECT 1 FROM system.article_process ".
					"WHERE process_id = $_processNew AND article_id = $_articleNew ) returning process_id, article_id"; 
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setArticleProcess($rs[0]['process_id'],$rs[0]['article_id']):true;
				$arrLog = array("input"=>array( "_processOld"=>$_processOld,
												"_articleOld"=>$_articleOld,
												"_processNew"=>$_processNew,
												"_articleNew"=>$_articleNew
											),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "_processOld"=>$_processOld,
												"_articleOld"=>$_articleOld,
												"_processNew"=>$_processNew,
												"_articleNew"=>$_articleNew
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function deleteArticleProcess($_process,$_article){
			$response = false;
			$sql =  "DELETE FROM system.article_process ".
					"WHERE process_id = $_process AND article_id = $_article;"; 
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setArticleProcess($rs[0]['process_id'],$rs[0]['article_id']):true;
				$arrLog = array("input"=>array( "_process"=>$_process,
												"_article"=>$_article
											),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "_process"=>$_process,
												"_article"=>$_article
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function listArticleProcess($clientId,$articleId){
			$response = false;
			$sql =  "SELECT ap.process_id, ap.article_id, a.article_name, ps.process_name ".
					"FROM system.article_process ap,system.article a,system.project p ,system.process ps ".
					"where ap.article_id = a.article_id and a.project_id = p.project_id and ap.process_id = ps.process_id ".
					"and p.client_id = $clientId and ap.article_id = $articleId;"; 
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = true;
				$arrLog = array("input"=>array( "data"=> $rs),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "data"=> 'No se encuentra la tabla'),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $rs;
		}

		public function getProcess() { return $this->_process; }
		public function getArticle() { return $this->_article; }
	}