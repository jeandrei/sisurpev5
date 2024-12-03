<?php
    class Certificado{
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getPosts(){
            $this->db->query("SELECT * FROM posts");

            return $this->db->resultSet();
        }

        public function getCertificados(){
					$fileDirectory = 'uploads/modeloCertificados/';
					$url = URLROOT .'/'. $fileDirectory;
					$files = scandir($fileDirectory);			
					foreach ($files as $file) {
						$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
						if (in_array($extension, ['jpg', 'jpeg', 'pdf'])) {	
							$modelosCertificados[] = [
								'url' => $url.$file,
								'arquivo' => $fileDirectory.$file,
								'file' => $file
							]; 
						}
					}					
					if($modelosCertificados){
						return $modelosCertificados;
					} else {
						return false;
					}
        }

    }