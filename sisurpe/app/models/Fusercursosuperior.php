<?php
	class Fusercursosuperior {
		private $db;

		public function __construct(){				
			$this->db = new Database;
		}

		public function getLastId(){
			$this->db->query("
				SELECT 
					MAX(ucsid) as id 
				FROM 
					f_user_curso_superior
			");           
			$row = $this->db->single();
			if($this->db->rowCount() > 0){
				return $row->id;
			} else {
				return false;
			}
		}

		public function getCursosUser($_userId){  			
			$this->db->query("
				SELECT 
					fucs.ucsId,
					fucs.userId as userId,
					fucs.areaId as areaId,
					fucs.nivelId as nivelId, 
					fucs.cursoId as cursoId, 
					fucs.tipoInstituicao as tipoInstituicao, 
					fucs.instituicaoEnsino as instituicaoEnsino, 
					fucs.municipioId as municipioId, 
					fucs.file as file, 
					fucs.anoConclusao as anoConclusao 
				FROM 
					f_user_curso_superior fucs 
				WHERE 
					fucs.userId = :userId 
				ORDER BY 
					fucs.instituicaoEnsino ASC
			");
			$this->db->bind(':userId',$_userId);
			$result = $this->db->resultSet();			
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}

		// Registra um curso na tabela f_user_curso_superior
		public function register($data){
			
			//AQUI
			//debug($data);
			
			if($data['file'] == ''){
				$sql = "
					INSERT INTO f_user_curso_superior (userId, areaId,nivelId, cursoId,tipoInstituicao,instituicaoEnsino,municipioId,anoConclusao) VALUES (:userId, :areaId,:nivelId,:cursoId,:tipoInstituicao,:instituicaoEnsino,:municipioId,:anoConclusao)
				";
			} else {   
				$sql = "
					INSERT INTO f_user_curso_superior (userId, areaId,nivelId, cursoId,tipoInstituicao,instituicaoEnsino,municipioId,file,anoConclusao) VALUES (:userId, :areaId,:nivelId,:cursoId,:tipoInstituicao,:instituicaoEnsino,:municipioId,:file,:anoConclusao)
				";
			}										
			$this->db->query($sql); 
			$this->db->bind(':userId',$data['userId']);
			$this->db->bind(':areaId',$data['areaId']);
			$this->db->bind(':nivelId',$data['nivelId']);
			$this->db->bind(':cursoId',$data['cursoId']);
			$this->db->bind(':tipoInstituicao',$data['tipoInstituicao']);
			$this->db->bind(':anoConclusao',$data['anoConclusao']);
			$this->db->bind(':instituicaoEnsino',$data['instituicaoEnsino']);
			$this->db->bind(':municipioId',$data['municipioId']);
			if($data['file']){
				$this->db->bind(':file',$data['file']);                
			}		
			if($this->db->execute()){
				return $this->db->lastId;
			} else {
				return false;
			}
		}

		public function getUserFormacoesById($_userId){            
			$this->db->query("
				SELECT 
					fuf.userId as userId, 
					fuf.maiorEscolaridade as maiorEscolaridade, 
					fuf.tipoEnsinoMedio as tipoEnsinoMedio 
				FROM 
					f_user_formacao fuf 
				WHERE 
					fuf.userId = :userId
			");
			$this->db->bind(':userId',$_userId);
			$row = $this->db->single();
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			}
		}

		public function delete($_ucsId){
			$this->db->query("
				DELETE FROM 
					f_user_curso_superior 
				WHERE 
					ucsId = :ucsId
				");
			$this->db->bind(':ucsId', $_ucsId);					
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}        
		}
			
		public function uploadBlob($file){  
			try {                    
				if (
					!isset($_FILES[$file]['error']) ||
					is_array($_FILES[$file]['error'])
				) {
					throw new RuntimeException('Parâmetros inválidos.');
				}				
				// Check $_FILES['upfile']['error'] value.
				switch ($_FILES[$file]['error']) {
					case UPLOAD_ERR_OK:
						break;
					case UPLOAD_ERR_NO_FILE:
						throw new RuntimeException('Nenhum arquivo enviado.');
					case UPLOAD_ERR_INI_SIZE:
					case UPLOAD_ERR_FORM_SIZE:
						throw new RuntimeException('Arquivo excede o tamenho permitido.');
					default:
						throw new RuntimeException('Erro desconhecido.');
				}				
				// You should also check filesize here. 
				//o tamanho é em bytes então tem que fazer 20*1024*1024 para
				//para 20mb
				if ($_FILES[$file]['size'] > 20971520) {
					throw new RuntimeException('Arquivo excede o tamenho permitido.');
				}				
				// DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
				// Check MIME Type by yourself.
				$finfo = new finfo(FILEINFO_MIME_TYPE);
				if (false === $ext = array_search(
					$finfo->file($_FILES[$file]['tmp_name']),
					array(
						'jpg' => 'image/jpeg',
						'png' => 'image/png',                        
						'pdf' => 'application/pdf',
					),
					true
				)) {
					throw new RuntimeException('Formato inválido.');
				}               
				if (!$file = $this->db->uploadFile($file)) {
					throw new RuntimeException('Falha ao tentar fazer o upload do arquivo.');
				}	
			} catch (RuntimeException $e) {
				$file = [
					'erro' => true,
					'message' => $e->getMessage()
				];            
			}					
			return $file;
		}

		public function getFile($_ucsId){            
			$this->db->query("
				SELECT 
					file 
				FROM 
					f_user_curso_superior 
				WHERE  
					ucsId = :ucsId
			");
			$this->db->bind(':ucsId',$_ucsId); 
			$row = $this->db->single(); 
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			}   
		}	

		public function getUserId($_ucsId){            
			$this->db->query("
				SELECT 
					userId 
				FROM 
					f_user_curso_superior 
				WHERE  
					ucsId = :ucsId
			");
			$this->db->bind(':ucsId',$_ucsId); 
			$row = $this->db->single(); 
			if($this->db->rowCount() > 0){
				return $row->userId;
			} else {
				return false;
			}   
		}
		
		public function getUserCursosSup($_userId){
			$this->db->query("
				SELECT 
					* 
				FROM 
					f_user_curso_superior 
				WHERE  
					userId = :userId
			");
			$this->db->bind(':userId',$_userId); 
			$result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}   
		}

		public function removeAllCursosSupUser($_userId){
			$cursosSup = $this->getUserCursosSup($_userId);					
			foreach($cursosSup as $curso){
				if(isset($curso->file) && !empty($curso->file)){					
					removeFile($curso->file);
				}				
				if(!$this->delete($curso->ucsId)){
					return false;
				}
			}	
			return true;								
		}
	}
?>