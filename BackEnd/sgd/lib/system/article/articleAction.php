<?php
require_once ('C://xampp/htdocs/sgd/lib/system/article/article.php');
require_once ('C://xampp/htdocs/sgd/lib/system/action/action.php');
	class articleAction {
		private $_db, $_log;
		private $_action, $_article;

		function __construct($_db, $_log, $actionId=0, $articleId=0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($actionId != 0 && $articleId != 0){
				$this->setArticleAction($actionId, $articleId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->articleAction);
			unset($this->_action);
			unset($this->_article);
		}

		function setArticleAction($actionId, $articleId){
			$response = FALSE;
			$dataArticleAction = $this->findArticleAction($actionId, $articleId);
			if($dataArticleAction){
				$this->mapArticleAction($dataArticleAction);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("actionId"=>$actionId, "articleId"=>$articleId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findArticleAction($actionId, $articleId){
			$response = false;
			$sql =  "SELECT * FROM system.article_action WHERE action_id = $actionId AND article_id = $articleId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("actionId"=>$actionId, "articleId"=>$articleId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("actionId"=>$actionId, "articleId"=>$articleId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapArticleAction($rs){
			$this->_action = new action($this->_db, $this->_log, $rs['action_id']);
			$this->_article = new article($this->_db, $this->_log, $rs['article_id']);
		}

		public function insertArticleAction($_action,$_article){
			$response = false;
			$sql =  "INSERT INTO system.article_action (action_id, article_id) ".
					"SELECT $_action, $_article ".
					"WHERE NOT EXISTS ( ".
					"SELECT 1 FROM system.article_action ".
					"WHERE action_id = $_action AND article_id = $_article ".
					") returning action_id,article_id;"; 
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setArticleAction($rs[0]['action_id'],$rs[0]['article_id']):true;
				$arrLog = array("input"=>array( "action_id"=>$_action,
												"article_id"=>$_article
											),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "action_id"=>$_action,
												"article_id"=>$_article
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function updateArticleAction($_actionOld,$_articleOld, $_actionNew,$_articleNew){
			$response = false;
			$sql =  "UPDATE system.article_action ".
					"SET action_id = $_actionNew, article_id = $_articleNew ".
					"WHERE action_id = $_actionOld AND article_id = $_articleOld ".
					"AND NOT EXISTS ( ".
					"SELECT 1 FROM system.article_action ".
					"WHERE action_id = $_actionNew AND article_id = $_articleNew ) returning action_id, article_id"; 
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setArticleAction($rs[0]['action_id'],$rs[0]['article_id']):true;
				$arrLog = array("input"=>array( "_actionOld"=>$_actionOld,
												"_articleOld"=>$_articleOld,
												"_actionNew"=>$_actionNew,
												"_articleNew"=>$_articleNew
											),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "_actionOld"=>$_actionOld,
												"_articleOld"=>$_articleOld,
												"_actionNew"=>$_actionNew,
												"_articleNew"=>$_articleNew
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function deleteArticleAction($_action,$_article){
			$response = false;
			$sql =  "DELETE FROM system.article_action ".
					"WHERE action_id = $_action AND article_id = $_article;"; 
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setArticleAction($rs[0]['action_id'],$rs[0]['article_id']):true;
				$arrLog = array("input"=>array( "_action"=>$_action,
												"_article"=>$_article
											),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "_action"=>$_action,
												"_article"=>$_article
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function listArticleAction($clientId,$articleId){
			$response = false;
			$sql =  "SELECT aa.action_id, aa.article_id, a.article_name, ac.action_name ".
					"FROM system.article_action aa,system.article a,system.project p ,system.action ac ".
					"where aa.article_id = a.article_id and a.project_id = p.project_id and aa.action_id = ac.action_id ".
					"and p.client_id = $clientId and aa.article_id = $articleId;"; 
					
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

		public function getAction() { return $this->_action; }
		public function getArticle() { return $this->_article; }
	}