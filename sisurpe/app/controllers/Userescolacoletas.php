<?php 
	class Userescolacoletas extends Controller{

		public function __construct(){  
			if((!isLoggedIn())){ 
				flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
				redirect('users/login');
				die();
			}
			$this->userEscolaColetaModel = $this->model('Userescolacoleta');
		}
		
		public function add(){  
			if($this->userEscolaColetaModel->getUserEscolaById($_POST['userId'],$_POST['escolaId'])){
				$json_ret = array(
					'class'=>'error', 
					'message'=>'Escola já vinculada a este usuário!',
					'error'=>$data
				);                     
				echo json_encode($json_ret); 
				die() ;
			}			
			$data=[
				'userId' => post('userId'),
				'escolaId' => post('escolaId')
			];

			$error=[];	
			//valida userId
			if(empty($data['userId'])){
				$error['userId_err'] = 'Erro ao tentar recuperar os dados do usuário!';
			}

			//valida escolaId
			if(empty($data['escolaId'])){
				$error['escolaId_err'] = 'Por favor informe a escola!';
			}     

			if(
					empty($error['userId_err']) &&
					empty($error['escolaId_err'])
				)
			{                
				try{
					//primeiro atualizo o tipo do usuário para coleta
					if(!$this->userEscolaColetaModel->updateTipo($data['userId'])){
							throw new Exception('Erro ao atualizar o tipo do usuários!'); 
					}
					//depois gravo no banco
					if($this->userEscolaColetaModel->register($data)){                   
						$json_ret = array(                                            
							'class'=>'success', 
							'message'=>'Usuário vinculado com sucesso!',
							'error'=>false
						);  
						echo json_encode($json_ret); 
						die();
					} else {
						throw new Exception('Erro ao gravar os dados!');  
					}     
				} catch (Exception $e) {                     
					$json_ret = array(
						'class'=>'error', 
						'message'=>$e->getMessage(),
						'error'=>$data
					);                     
					echo json_encode($json_ret); 
					die();
				}					
			} else {                
				$json_ret = array(
					'class'=>'error', 
					'message'=>'Erro ao tentar gravar os dados',
					'error'=>$error
				);
				echo json_encode($json_ret);
				die();
			}                   
		}
   
		public function getUserEscolaColeta(){      
			$userId = $_POST['userId'];      
			$html = "
				<thead class='thead-dark'>
					<tr class='text-center'>
						<th scope='col'>Escolas Coleta</th>                    
						<th scope='col'></th>                   
					</tr>
				</thead>
				<tbody>
			";		
			if($userEscolas = $this->userEscolaColetaModel->getEscolaColetaUserById($userId)){					
				$i = 0;
				foreach($userEscolas as $row){																						
					$i++;                
					$html .= "
						<tr class='text-center align-middle'>                        
							<td>$row->nome</td>
							<td><button type='button' class='btn btn-sm btn-danger' onClick=removeEscola($row->uecId)>Remover</button></td>
						</tr>  
					";                   
				}	
			} else {
				$html .= "
					<tr class='text-center'>
						<td colspan='4'>
								Nenhuma escola adicionada
						</td> 
					</tr> 
				";
			}
			$html .= '</tbody>';
			echo $html;
		}

		public function delete($id){             
			try{    
				if($this->userEscolaColetaModel->delete($id)){                        
					$json_ret = array(                                            
						'class'=>'success', 
						'message'=>'Escola removida com sucesso!',
						'error'=>false
					);   
					echo json_encode($json_ret); 
				}  else {
					throw new Exception('Erro ao tentar remover a escola!');  
				}     
			} catch (Exception $e) {
				$json_ret = array(
					'classe'=>'error', 
					'message'=>$e,
					'error'=>$data
				);                     
				echo json_encode($json_ret); 
			}
		}					
	}
?>