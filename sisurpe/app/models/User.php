<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	class User extends Pagination{
		private $db;

		public function __construct(){				
			$this->db = new Database;
		}

		// Register User
		public function register($data){
			$this->db->query("
				INSERT INTO users (name, cpf, email, password) VALUES (:name, :cpf, :email, :password)
			");			
			$this->db->bind(':name',$data['name']);
			$this->db->bind(':cpf',$data['cpf']);
			$this->db->bind(':email',$data['email']);
			$this->db->bind(':password',$data['password']);			
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
		}

		public function removeUserEscolaColeta($userId){
			$this->db->query("
				DELETE FROM 
					user_escola_coleta 
				WHERE 
					userId = :userId
			");				
			$this->db->bind(':userId',$userId); 
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
		}

		// Update User
		public function update($data){   
			if($data['usertype'] != 'coleta'){
				if(!$this->removeUserEscolaColeta($data['user_id'])){
					return false;
				}
			}
			if((!empty($data['password'])) && (!is_null($data['password'])) && ($data['password'] != ""))  {           
				$this->updatepassword($data);
			}				
			$this->db->query("
				UPDATE 
					users 
				SET 
					users.name=:name, 
					users.type=:type
				WHERE 
					id=:id
			");			
			$this->db->bind(':id',$data['user_id']); 
			$this->db->bind(':name',$data['name']);  
			$this->db->bind(':type',$data['usertype']);  
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
		}

		// Update Password
		public function updatepassword($data){
			$this->db->query("
				UPDATE 
					users 
				SET 
					password = :password 
				WHERE 
					email=:email
			");			        
			$this->db->bind(':email',$data['email']);
			$this->db->bind(':password',$data['password']);			
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
		}

		// 2 Login User                
		public function login($email, $password){
			$this->db->query("
				SELECT 
					* 
				FROM 
					users 
				WHERE 
					email = :email
			");
			$this->db->bind(':email', $email);
			$row = $this->db->single();
			$hashed_password = $row->password;
			// password_verify — Verifica se um password corresponde com um hash criptografado
			// Logo para verificar não precisa descriptografar 
			// aqui $password vem do formulário ou seja digitado pelo usuário  
			// e $hashed_password vem da consulta do banco e está criptografado
			if(password_verify($password, $hashed_password)){
				return $row;
			} else {
				return false;
			}
		}

		// Find user by email
		public function findUserByEmail($email){
			$this->db->query("
				SELECT 
					* 
				FROM 
					users 
				WHERE 
					email = :email
			");			
			$this->db->bind(':email', $email);
			$row = $this->db->single();			
			if($this->db->rowCount() > 0){
				return true;
			} else {
				return false;
			}
		}

		public function getEmailById($id){
			$this->db->query("
				SELECT 
					email 
				FROM 
					users 
				WHERE 
					id = :id
			");			
			$this->db->bind(':id', $id);
			$row = $this->db->single();		
			if($this->db->rowCount() > 0){
				return true;
			} else {
				return false;
			} 
		}

		public function getUserById($id){
			$this->db->query("
				SELECT 
					id,
					name,
					email,
					cpf
				FROM 
					users 
				WHERE 
					id = :id
			");				
			$this->db->bind(':id', $id);
			$row = $this->db->single();			
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			} 
		}

		public function sendemail($email, $senha){   
			/* Exception class. */
			require APPROOT . '/inc/PHPMailer-master/src/Exception.php';
			/* The main PHPMailer class. */
			require APPROOT . '/inc/PHPMailer-master/src/PHPMailer.php';
			/* SMTP class, needed if you want to use SMTP. */
			require APPROOT . '/inc/PHPMailer-master/src/SMTP.php';
			// Instantiation and passing `true` enables exceptions
			$mail = new PHPMailer(true);
			try {
				//Server settings
				//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
				$mail->isSMTP();                                            // Send using SMTP
				$mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
				$mail->Username   = 'sisurpe@educapenha.com.br';                     // SMTP username
				$mail->Password   = '@sisurpe23@';                               // SMTP password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
				$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
				//Recipients
				$mail->CharSet = 'UTF-8';
				$mail->setLanguage('pt_br', APPROOT . '/inc/PHPMailer-master/language');
				$mail->setFrom('sisurpe@educapenha.com.br', 'SISURPE');
				$mail->addAddress($email, 'SISURPE');     // Add a recipient
				//$mail->addAddress('ellen@example.com');               // Name is optional
				//$mail->addReplyTo('info@example.com', 'Information');
				//$mail->addCC('cc@example.com');
				//$mail->addBCC('bcc@example.com');
				// Attachments
				//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
				$texto = 'Sua nova senha é :' . $senha;
				// Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = 'Você solicitou uma nova senha de acesso ao SISURPE';
				$mail->Body    = $texto;
				//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
				$mail->send();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function getUserIdByCpf($cpf){
			$this->db->query("
				SELECT 
					users.id as id, 
					users.name as name 
				FROM 
					users 
				WHERE 
					cpf = :cpf
			");			
			$this->db->bind(':cpf', $cpf);
			$row = $this->db->single();			
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			} 
		}

		public function cpfCadastrado($cpf){
			$this->db->query("
				SELECT 
					users.id as id, 
					users.name as name 
				FROM 
					users 
				WHERE 
					cpf = :cpf
			");			
			$this->db->bind(':cpf', $cpf);
			$row = $this->db->single();			
			if($this->db->rowCount() > 0){
				return true;
			} else {
				return false;
			} 
		}

    /*Deleta todos os grupos do usuário  */
    public function delGruposUsuario($userId){  
      if(!$this->gruposDoUsuario($userId)){        
        return true;
      }
      $this->db->query('DELETE FROM usersGrupos WHERE userId = :userId');				
			$this->db->bind(':userId', $userId);
			$this->db->execute();				
			if($this->db->rowCount() > 0){
				return true;
			} else {
				return false;
			}
    }

    //Adiciona um usuário em um grupo
    public function addGrupo($userId, $grupoId){
      $this->db->query('INSERT INTO usersGrupos (userid, grupoId) VALUES (:userid, :grupoId)');      
      $this->db->bind(':userid',$userId);
      $this->db->bind(':grupoId',$grupoId);        
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    //Retorna true se um usuário já pertence a um grupo
    public function userPertenceGrupo($userId, $grupoId){
      $this->db->query('SELECT * FROM usersGrupos WHERE userId = :userId AND grupoId = :grupoId'); 
			$this->db->bind(':userId', $userId);
      $this->db->bind(':grupoId', $grupoId);
			$this->db->execute();
			if($this->db->rowCount() > 0){
				return true;
			} else {
				return false;
			}
    }

    public function deleteGrupoUsuario($userId, $grupoId){    
      //echo "userId = " . $userId . " grupoId = " . $grupoId;
      //die();
      $this->db->query('DELETE FROM usersGrupos WHERE userId = :userId AND grupoId = :grupoId');				
			$this->db->bind(':userId', $userId);
      $this->db->bind(':grupoId', $grupoId);							
      if($this->db->execute()){
				return true;
			} else {        
				return false;
			}
    }


		//FUNÇÃO QUE EXECUTA A SQL PAGINATE PARA FUNCIONAR O BIND TEM QUE COLOCAR O  PARÂMETRO using_bound_params' => true lá no controller
		public function getUsers($page, $options){ 			
			$bind = [];
			$sql = ("
				SELECT 
						users.id,
						users.name,
						users.email,						
						users.created_at 
				FROM 
						users 
				WHERE 1"
			);
			if(!empty($options['named_params'][':cpf'])){
				$sql .= " AND users.cpf LIKE CONCAT(:cpf, '%')";
				$bind += [':cpf' => $options['named_params'][':cpf']];           
			}
			if(!empty($options['named_params'][':name'])){
				$sql .= " AND users.name LIKE CONCAT(:name, '%')";
				$bind += [':name' => $options['named_params'][':name']]; 
			}
			
			$sql .= " ORDER BY name ASC";
			
			try{
				$this->pag = new Pagination($page,$sql,$options);  
			} catch(paginationException $e){
				echo $e;
				exit();
			}
			//bind
			foreach($bind as $key => $value){             
				$this->pag->bindParam($key, $value, PDO::PARAM_STR, 12);            
			}  
			//EXECUTA A PAGINAÇÃO
			$this->pag->execute();
			//RETORNA A PAGINAÇÃO
			return $this->pag;
		}	
    
    public function getUserPermitions($userId){
      $this->db->query("
        SELECT 
          *
        FROM 
          grupoAcaoTabela gat 
        WHERE 
        EXISTS (
          SELECT 
          *
          FROM 
          usersGrupos ug 
          WHERE 
          ug.grupoId = gat.grupoId 
          AND 
          ug.userId = :userId
        )
        AND 
        (
        gat.ler = 's'
        OR 
        gat.editar = 's'
        OR 
        gat.criar = 's'
        OR 
        gat.apagar = 's')
      ");      
      $this->db->bind(':userId', $userId);
      $result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
    }

	}//class
?>