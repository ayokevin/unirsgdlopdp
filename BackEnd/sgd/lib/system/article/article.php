<?php
require_once ('C://xampp/htdocs/sgd/lib/common/reference/reference.php');
require_once ('C://xampp/htdocs/sgd/lib/system/project/project.php');
	class article {
		private $_db, $_log;
		private $articleId, $_project, $articleName, $articleDescription, $articleFatherId, $_articleApply, $articleObservation, $_statusId, $articleActualState, $articleJustification, $_articleCompliance, $articleUpdateAt;

		function __construct($_db, $_log, $articleId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($articleId != 0){
				$this->setArticle($articleId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->articleId);
			unset($this->_project);
			unset($this->articleName);
			unset($this->articleDescription);
			unset($this->articleFatherId);
			unset($this->_articleApply);
			unset($this->articleObservation);
			unset($this->_statusId);
			unset($this->articleActualState);
			unset($this->articleJustification);
			unset($this->_articleCompliance);
			unset($this->articleUpdateAt);
		}

		function setArticle($articleId){
			$response = FALSE;
			$dataArticle = $this->findArticle($articleId);
			if($dataArticle){
				$this->mapArticle($dataArticle);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("articleId"=>$articleId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findArticle($articleId){
			$response = false;
			$sql =  "SELECT * FROM system.article WHERE article_id = $articleId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("articleId"=>$articleId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("articleId"=>$articleId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapArticle($rs){
			$this->articleId = $rs['article_id'];
			$this->_project = new project($this->_db, $this->_log, $rs['project_id']);
			$this->articleName = $rs['article_name'];
			$this->articleDescription = $rs['article_description'];
			$this->articleFatherId = $rs['article_father_id'];
			$this->_articleApply = new reference($this->_db, $this->_log, $rs['article_apply']);
			$this->articleObservation = $rs['article_observation'];
			$this->_statusId = new reference($this->_db, $this->_log, $rs['status_id']);
			$this->articleActualState = $rs['article_actual_state'];
			$this->articleJustification = $rs['article_justification'];
			$this->_articleCompliance = new reference($this->_db, $this->_log, $rs['article_compliance']);
			$this->articleUpdateAt = $rs['article_update_at'];
		}

		public function listArticle($client_id,$article_id,$process_id){
			$response = false;
			if($client_id===''){
				$sql =  "SELECT ".
				"a.article_name, ".
				"a.article_id, ".
				"a.article_description, ".
				"a.article_apply, ".
				"a.article_justification, ".
				"a.status_id, ".
				"r_ar.reference_name AS article_status_name, ".
				"a.article_compliance, ".
				"r_ac.reference_name AS article_compliance_name, ".
				"r_aa.reference_name AS article_apply_name, ".
				"a.article_observation, ".
				"a.article_update_at, ".
				"COALESCE(articulo.documentoArticulo, '{}') AS documentoArticulo, ".
				"COALESCE(proceso.documentoProcesos, '{}') AS documentoProcesos, ".
				"ARRAY_REMOVE(ARRAY_AGG(DISTINCT ac.action_name), NULL) AS actions, ".
				"ARRAY_REMOVE(ARRAY_AGG(DISTINCT f.file_named), NULL) AS documentoAcciones, ".
				"ARRAY_REMOVE(ARRAY_AGG(DISTINCT f.file_id), NULL) AS documentoAccionesIds, ".
				"p.project_id, ".
				"p.project_name, ".
				"r_ar.reference_name AS article_status_name, ".
				"ARRAY_AGG(DISTINCT pr.process_name) AS process_names, ".
				"ARRAY_AGG(DISTINCT f2.file_id) AS documentoArticuloIds, ".
				"ARRAY_AGG(DISTINCT f3.file_id) AS documentoProcesosIds ".
			  "FROM ".
				"system.article AS a ".
				"LEFT JOIN ( ".
				  "SELECT ".
					"af.article_id, ".
					"ARRAY_REMOVE(ARRAY_AGG(DISTINCT af.file_named), NULL) AS documentoArticulo ".
				  "FROM ".
					"system.file AS af ".
				  "WHERE ".
					"af.article_id IS NOT NULL ".
				  "GROUP BY ".
					"af.article_id ".
				") articulo ON a.article_id = articulo.article_id ".
				"LEFT JOIN ( ".
				  "SELECT ".
					"ap.article_id, ".
					"ARRAY_REMOVE(ARRAY_AGG(DISTINCT fp.file_named), NULL) AS documentoProcesos ".
				  "FROM ".
					"system.file AS fp ".
					"JOIN system.article_process AS ap ON fp.process_id = ap.process_id ".
				  "WHERE ".
					"fp.process_id IS NOT NULL ".
				  "GROUP BY ".
					"ap.article_id ".
				") proceso ON a.article_id = proceso.article_id ".
				"LEFT JOIN system.article_action AS aa ON a.article_id = aa.article_id ".
				"LEFT JOIN system.action AS ac ON aa.action_id = ac.action_id ".
				"LEFT JOIN system.file AS f ON ac.action_id = f.action_id ".
				"LEFT JOIN system.project AS p ON a.project_id = p.project_id ".
				"LEFT JOIN common.client AS c ON p.client_id = c.client_id ".
				"LEFT JOIN common.reference AS r_a ON ac.status_id = r_a.reference_id ".
				"LEFT JOIN common.reference AS r_ar ON a.status_id = r_ar.reference_id ".
				"LEFT JOIN common.reference AS r_ac ON a.article_compliance = r_ac.reference_id ".
				"LEFT JOIN common.reference AS r_aa ON a.article_apply = r_aa.reference_id ".
				"LEFT JOIN system.article_process AS ap ON a.article_id = ap.article_id ".
				"LEFT JOIN system.process AS pr ON ap.process_id = pr.process_id ".
				"LEFT JOIN system.file AS f2 ON f2.file_named = ANY(articulo.documentoArticulo) ".
				"LEFT JOIN system.file AS f3 ON f3.file_named = ANY(proceso.documentoProcesos) ".
			  "WHERE ".
				"aa.action_id IS NOT NULL ".
			  "GROUP BY ".
				"a.article_id, a.article_name, a.article_description, a.article_apply, a.article_justification, ".
				"a.status_id, r_ar.reference_name, a.article_compliance, r_ac.reference_name, a.article_observation, ".
				"a.article_update_at, articulo.documentoArticulo, proceso.documentoProcesos, p.project_id, ".
				"p.project_name, r_ar.reference_name, r_aa.reference_name ".
			  "HAVING ".
				"array_agg(ac.status_id) @> ARRAY[36]::int[];";
			}else if($client_id!==''){
				$sql =  "SELECT ".
				"a.article_name, ".
				"a.article_id, ".
				"a.article_description, ".
				"a.article_apply, ".
				"a.article_justification, ".
				"a.status_id, ".
				"r_ar.reference_name AS article_status_name, ".
				"a.article_compliance, ".
				"r_ac.reference_name AS article_compliance_name, ".
				"r_aa.reference_name AS article_apply_name, ".
				"a.article_observation, ".
				"a.article_update_at, ".
				"COALESCE(articulo.documentoArticulo, '{}') AS documentoArticulo, ".
				"COALESCE(proceso.documentoProcesos, '{}') AS documentoProcesos, ".
				"ARRAY_REMOVE(ARRAY_AGG(DISTINCT ac.action_name), NULL) AS actions, ".
				"ARRAY_REMOVE(ARRAY_AGG(DISTINCT f.file_named), NULL) AS documentoAcciones, ".
				"ARRAY_REMOVE(ARRAY_AGG(DISTINCT f.file_id), NULL) AS documentoAccionesIds, ".
				"p.project_id, ".
				"p.project_name, ".
				"r_ar.reference_name AS article_status_name, ".
				"ARRAY_AGG(DISTINCT pr.process_name) AS process_names, ".
				"ARRAY_AGG(DISTINCT f2.file_id) AS documentoArticuloIds, ".
				"ARRAY_AGG(DISTINCT f3.file_id) AS documentoProcesosIds ".
			  "FROM ".
				"system.article AS a ".
				"LEFT JOIN ( ".
				  "SELECT ".
					"af.article_id, ".
					"ARRAY_REMOVE(ARRAY_AGG(DISTINCT af.file_named), NULL) AS documentoArticulo ".
				  "FROM ".
					"system.file AS af ".
				  "WHERE ".
					"af.article_id IS NOT NULL ".
				  "GROUP BY ".
					"af.article_id ".
				") articulo ON a.article_id = articulo.article_id ".
				"LEFT JOIN ( ".
				  "SELECT ".
					"ap.article_id, ".
					"ARRAY_REMOVE(ARRAY_AGG(DISTINCT fp.file_named), NULL) AS documentoProcesos ".
				  "FROM ".
					"system.file AS fp ".
					"JOIN system.article_process AS ap ON fp.process_id = ap.process_id ".
				  "WHERE ".
					"fp.process_id IS NOT NULL ".
				  "GROUP BY ".
					"ap.article_id ".
				") proceso ON a.article_id = proceso.article_id ".
				"LEFT JOIN system.article_action AS aa ON a.article_id = aa.article_id ".
				"LEFT JOIN system.action AS ac ON aa.action_id = ac.action_id ".
				"LEFT JOIN system.file AS f ON ac.action_id = f.action_id ".
				"LEFT JOIN system.project AS p ON a.project_id = p.project_id ".
				"LEFT JOIN common.client AS c ON p.client_id = c.client_id ".
				"LEFT JOIN common.reference AS r_a ON ac.status_id = r_a.reference_id ".
				"LEFT JOIN common.reference AS r_ar ON a.status_id = r_ar.reference_id ".
				"LEFT JOIN common.reference AS r_ac ON a.article_compliance = r_ac.reference_id ".
				"LEFT JOIN common.reference AS r_aa ON a.article_apply = r_aa.reference_id ".
				"LEFT JOIN system.article_process AS ap ON a.article_id = ap.article_id ".
				"LEFT JOIN system.process AS pr ON ap.process_id = pr.process_id ".
				"LEFT JOIN system.file AS f2 ON f2.file_named = ANY(articulo.documentoArticulo) ".
				"LEFT JOIN system.file AS f3 ON f3.file_named = ANY(proceso.documentoProcesos) ".
			  "WHERE ".
				"aa.action_id IS NOT NULL ".
				"AND c.client_id = $client_id ".
			  "GROUP BY ".
				"a.article_id, a.article_name, a.article_description, a.article_apply, a.article_justification, ".
				"a.status_id, r_ar.reference_name, a.article_compliance, r_ac.reference_name, a.article_observation, ".
				"a.article_update_at, articulo.documentoArticulo, proceso.documentoProcesos, p.project_id, ".
				"p.project_name, r_ar.reference_name, r_aa.reference_name ".
			  "HAVING ".
				"array_agg(ac.status_id) @> ARRAY[36]::int[];";
			}else if($client_id!=='' && $article_id !==''){
				$sql =  "SELECT ".
				"a.article_name, ".
				"a.article_id, ".
				"a.article_description, ".
				"a.article_apply, ".
				"a.article_justification, ".
				"a.status_id, ".
				"r_ar.reference_name AS article_status_name, ".
				"a.article_compliance, ".
				"r_ac.reference_name AS article_compliance_name, ".
				"r_aa.reference_name AS article_apply_name, ".
				"a.article_observation, ".
				"a.article_update_at, ".
				"COALESCE(articulo.documentoArticulo, '{}') AS documentoArticulo, ".
				"COALESCE(proceso.documentoProcesos, '{}') AS documentoProcesos, ".
				"ARRAY_REMOVE(ARRAY_AGG(DISTINCT ac.action_name), NULL) AS actions, ".
				"ARRAY_REMOVE(ARRAY_AGG(DISTINCT f.file_named), NULL) AS documentoAcciones, ".
				"ARRAY_REMOVE(ARRAY_AGG(DISTINCT f.file_id), NULL) AS documentoAccionesIds, ".
				"p.project_id, ".
				"p.project_name, ".
				"r_ar.reference_name AS article_status_name, ".
				"ARRAY_AGG(DISTINCT pr.process_name) AS process_names, ".
				"ARRAY_AGG(DISTINCT f2.file_id) AS documentoArticuloIds, ".
				"ARRAY_AGG(DISTINCT f3.file_id) AS documentoProcesosIds ".
			  "FROM ".
				"system.article AS a ".
				"LEFT JOIN ( ".
				  "SELECT ".
					"af.article_id, ".
					"ARRAY_REMOVE(ARRAY_AGG(DISTINCT af.file_named), NULL) AS documentoArticulo ".
				  "FROM ".
					"system.file AS af ".
				  "WHERE ".
					"af.article_id IS NOT NULL ".
				  "GROUP BY ".
					"af.article_id ".
				") articulo ON a.article_id = articulo.article_id ".
				"LEFT JOIN ( ".
				  "SELECT ".
					"ap.article_id, ".
					"ARRAY_REMOVE(ARRAY_AGG(DISTINCT fp.file_named), NULL) AS documentoProcesos ".
				  "FROM ".
					"system.file AS fp ".
					"JOIN system.article_process AS ap ON fp.process_id = ap.process_id ".
				  "WHERE ".
					"fp.process_id IS NOT NULL ".
				  "GROUP BY ".
					"ap.article_id ".
				") proceso ON a.article_id = proceso.article_id ".
				"LEFT JOIN system.article_action AS aa ON a.article_id = aa.article_id ".
				"LEFT JOIN system.action AS ac ON aa.action_id = ac.action_id ".
				"LEFT JOIN system.file AS f ON ac.action_id = f.action_id ".
				"LEFT JOIN system.project AS p ON a.project_id = p.project_id ".
				"LEFT JOIN common.client AS c ON p.client_id = c.client_id ".
				"LEFT JOIN common.reference AS r_a ON ac.status_id = r_a.reference_id ".
				"LEFT JOIN common.reference AS r_ar ON a.status_id = r_ar.reference_id ".
				"LEFT JOIN common.reference AS r_ac ON a.article_compliance = r_ac.reference_id ".
				"LEFT JOIN common.reference AS r_aa ON a.article_apply = r_aa.reference_id ".
				"LEFT JOIN system.article_process AS ap ON a.article_id = ap.article_id ".
				"LEFT JOIN system.process AS pr ON ap.process_id = pr.process_id ".
				"LEFT JOIN system.file AS f2 ON f2.file_named = ANY(articulo.documentoArticulo) ".
				"LEFT JOIN system.file AS f3 ON f3.file_named = ANY(proceso.documentoProcesos) ".
			  "WHERE ".
				"aa.action_id IS NOT NULL ".
				"AND c.client_id = $client_id ".
				"AND a.article_id = $article_id ".
			  "GROUP BY ".
				"a.article_id, a.article_name, a.article_description, a.article_apply, a.article_justification, ".
				"a.status_id, r_ar.reference_name, a.article_compliance, r_ac.reference_name, a.article_observation, ".
				"a.article_update_at, articulo.documentoArticulo, proceso.documentoProcesos, p.project_id, ".
				"p.project_name, r_ar.reference_name, r_aa.reference_name ".
			  "HAVING ".
				"array_agg(ac.status_id) @> ARRAY[36]::int[];";
			}else if($client_id!== '' && $article_id !== '' && $process_id !== ''){
				$sql = $sql =  "SELECT ".
				"a.article_name, ".
				"a.article_id, ".
				"a.article_description, ".
				"a.article_apply, ".
				"a.article_justification, ".
				"a.status_id, ".
				"r_ar.reference_name AS article_status_name, ".
				"a.article_compliance, ".
				"r_ac.reference_name AS article_compliance_name, ".
				"r_aa.reference_name AS article_apply_name, ".
				"a.article_observation, ".
				"a.article_update_at, ".
				"COALESCE(articulo.documentoArticulo, '{}') AS documentoArticulo, ".
				"COALESCE(proceso.documentoProcesos, '{}') AS documentoProcesos, ".
				"ARRAY_REMOVE(ARRAY_AGG(DISTINCT ac.action_name), NULL) AS actions, ".
				"ARRAY_REMOVE(ARRAY_AGG(DISTINCT f.file_named), NULL) AS documentoAcciones, ".
				"ARRAY_REMOVE(ARRAY_AGG(DISTINCT f.file_id), NULL) AS documentoAccionesIds, ".
				"p.project_id, ".
				"p.project_name, ".
				"r_ar.reference_name AS article_status_name, ".
				"ARRAY_AGG(DISTINCT pr.process_name) AS process_names, ".
				"ARRAY_AGG(DISTINCT f2.file_id) AS documentoArticuloIds, ".
				"ARRAY_AGG(DISTINCT f3.file_id) AS documentoProcesosIds ".
			  "FROM ".
				"system.article AS a ".
				"LEFT JOIN ( ".
				  "SELECT ".
					"af.article_id, ".
					"ARRAY_REMOVE(ARRAY_AGG(DISTINCT af.file_named), NULL) AS documentoArticulo ".
				  "FROM ".
					"system.file AS af ".
				  "WHERE ".
					"af.article_id IS NOT NULL ".
				  "GROUP BY ".
					"af.article_id ".
				") articulo ON a.article_id = articulo.article_id ".
				"LEFT JOIN ( ".
				  "SELECT ".
					"ap.article_id, ".
					"ARRAY_REMOVE(ARRAY_AGG(DISTINCT fp.file_named), NULL) AS documentoProcesos ".
				  "FROM ".
					"system.file AS fp ".
					"JOIN system.article_process AS ap ON fp.process_id = ap.process_id ".
				  "WHERE ".
					"fp.process_id IS NOT NULL ".
				  "GROUP BY ".
					"ap.article_id ".
				") proceso ON a.article_id = proceso.article_id ".
				"LEFT JOIN system.article_action AS aa ON a.article_id = aa.article_id ".
				"LEFT JOIN system.action AS ac ON aa.action_id = ac.action_id ".
				"LEFT JOIN system.file AS f ON ac.action_id = f.action_id ".
				"LEFT JOIN system.project AS p ON a.project_id = p.project_id ".
				"LEFT JOIN common.client AS c ON p.client_id = c.client_id ".
				"LEFT JOIN common.reference AS r_a ON ac.status_id = r_a.reference_id ".
				"LEFT JOIN common.reference AS r_ar ON a.status_id = r_ar.reference_id ".
				"LEFT JOIN common.reference AS r_ac ON a.article_compliance = r_ac.reference_id ".
				"LEFT JOIN common.reference AS r_aa ON a.article_apply = r_aa.reference_id ".
				"LEFT JOIN system.article_process AS ap ON a.article_id = ap.article_id ".
				"LEFT JOIN system.process AS pr ON ap.process_id = pr.process_id ".
				"LEFT JOIN system.file AS f2 ON f2.file_named = ANY(articulo.documentoArticulo) ".
				"LEFT JOIN system.file AS f3 ON f3.file_named = ANY(proceso.documentoProcesos) ".
			  "WHERE ".
				"aa.action_id IS NOT NULL ".
				"AND c.client_id = $client_id ".
				"AND (a.article_id = $article_id OR pr.process_id = $process_id) ".
			  "GROUP BY ".
				"a.article_id, a.article_name, a.article_description, a.article_apply, a.article_justification, ".
				"a.status_id, r_ar.reference_name, a.article_compliance, r_ac.reference_name, a.article_observation, ".
				"a.article_update_at, articulo.documentoArticulo, proceso.documentoProcesos, p.project_id, ".
				"p.project_name, r_ar.reference_name, r_aa.reference_name ".
			  "HAVING ".
				"array_agg(ac.status_id) @> ARRAY[36]::int[];";
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

		public function updateArticle($articleId,$_articleApply, $articleObservation, $_statusId, $articleJustification, $_articleCompliance){
			$response = false;
			$sql =  "UPDATE system.article ".
			"SET  article_apply=$_articleApply, article_observation='$articleObservation', status_id=$_statusId, article_justification='$articleJustification', article_compliance=$_articleCompliance, article_update_at=CURRENT_TIMESTAMP ".
			"WHERE article_id=$articleId returning article_id ;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setArticle($rs[0]['article_id']):true;
				$arrLog = array("input"=>array( "articleId"=>$articleId,
												"_articleApply"=>$_articleApply,
												"articleObservation"=>$articleObservation,
												"_statusId"=>$_statusId,
												"articleJustification"=>$articleJustification,
												"_articleCompliance"=>$_articleCompliance
											),
								"output"=>$response,
								"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "articleId"=>$articleId,
												"_articleApply"=>$_articleApply,
												"articleObservation"=>$articleObservation,
												"_statusId"=>$_statusId,
												"articleJustification"=>$articleJustification,
												"_articleCompliance"=>$_articleCompliance
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function listArticleReference($client_id){
			$response = false;
			$sql =  "SELECT a.article_id ,a.article_name,a.article_description ".
					"FROM system.article a, system.project p ".
					"where a.project_id = p.project_id and p.client_id =client_id;";
			
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



		public function getArticleId() { return $this->articleId; }
		public function getProject() { return $this->_project; }
		public function getArticleName() { return $this->articleName; }
		public function getArticleDescription() { return $this->articleDescription; }
		public function getArticleFatherId() { return $this->articleFatherId; }
		public function getArticleApply() { return $this->_articleApply; }
		public function getArticleObservation() { return $this->articleObservation; }
		public function getStatusId() { return $this->_statusId; }
		public function getArticleActualState() { return $this->articleActualState; }
		public function getArticleJustification() { return $this->articleJustification; }
		public function getArticleCompliance() { return $this->_articleCompliance; }
		public function getArticleUpdateAt() { return $this->articleUpdateAt; }
	}