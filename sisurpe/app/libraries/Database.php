<?php
	/**
	 * PDO Database Class
	 * Connect to database
	 * Create prepared statemants
	 * Bind values
	 * Retun rows and results
	 */

	class Database { 
		protected $host = DB_HOST;
		protected $user = DB_USER;
		protected $pass = DB_PASS;
		protected $dbname = DB_NAME;
		protected $options;
		public    $lastId;
		//toda vez que preparamos um a sql vamos usar o dbh
		protected $dbh;
		protected $stmt;
		protected $error; 
		protected $dsn;  

		public function __construct() {				
			// Set DSN DATABASE SERVER NAME       
			$this->dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;             
			$options = array(
				// persistent connections increase performance checking the connection to the database
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			);		
			// Ceate PDO instance
			try{
				$this->dbh = new PDO($this->dsn, $this->user, $this->pass, $options);
				$this->dbh->exec('SET NAMES "utf8"'); 
			} catch(PDOException $e){
				$this->error = $e->getMessage();
				echo $this->error;
			}
		}

		// Prepare statement with query
		public function query($sql) {
			$this->stmt = $this->dbh->prepare($sql);
		}

		//Bind values
		public function bind($param, $value, $type = null) {
			if(is_null($type)){
				switch(true){
					case is_int($value):
						$type = PDO::PARAM_INT;
						break;
					case is_bool($value):
						$type = PDO::PARAM_BOOL;
						break;
					case is_null($value):
						$type = PDO::PARAM_NULL;
						break;
					default:
						$type = PDO::PARAM_STR;                                 
				}
			}
			$this->stmt->bindValue($param, $value, $type);
		}

		//Execute the prepared statemant and store the id in lastId proprerty
		public function execute(){
			if($this->stmt->execute()){
				$this->lastId = $this->dbh->lastInsertId();            
				return true;
			} else {
				return false;
			}			
		}

		//Get result set as array of objects $dados->nome
		public function resultSet(){
			$this->execute();
			return $this->stmt->fetchAll(PDO::FETCH_OBJ);
		}
		
		public function resultSetArray(){
			$this->execute();
			return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		//Get a single record as object $dados->nome
		public function single(){
			$this->execute();
			return $this->stmt->fetch(PDO::FETCH_OBJ);
		}

		// Get row count
		public function rowCount(){
			return $this->stmt->rowCount();
		}

		//upload to database blob type
		//fileExtensions - Extenções permitidas
		//allowedSize - Tamanho em bytes permitidos
		//newname - Se quiser dar um novo nome para o arquivo ao armazenar no banco de dados
		public function uploadFile($file,$newname=null){
			if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
				$file_uploaded['error'] = "Erro ao tentar fazer o upload do arquivo" . $_FILES[$file]['error'];
				return $file_uploaded['error'];
			}
			//se não for passado um novo nome utilizo o nome original do arquivo
			$fileName = is_null($newname) ? $_FILES[$file]['name'] : $newname; 		
			//Tipo do arquivo se jpeg,jpg,png etc
			$fileType = $_FILES[$file]['type'];
			//Pega o tamanho do arquivo para podermos impedir arquivos maiores que um determinado tamanho
			$fileSize = $_FILES[$file]['size'];   
			//Temp name o php vai gerar um nome temporário para o arquivo algo tipo /tmp/phpINyjnR
			$file = $_FILES[$file]['tmp_name'];  
			if (empty($file_uploaded['error'])){
				$file_uploaded = [
					'nome' => is_null($newname) ? $fileName : $newname,
					'extensao' => $fileExtension,
					'tipo' => $fileType,
					'data' => file_get_contents($file)
				];
			}		
			return $file_uploaded; 
		}				
	}
?>
