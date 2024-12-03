<?php 
	class Temas extends Controller{

		public function __construct(){
			if((!isLoggedIn())){ 
				flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
				redirect('pages/index');
				die();
			}                
			$this->inscricaoModel = $this->model('Inscricoe');
			$this->temaModel = $this->model('Tema');
		}

		public function index($id=null){	
			$html = "
				<thead class='thead-dark'>
					<tr class='text-center'>
						<th scope='col'>Ações</th>
						<th scope='col'>Tema</th>
						<th scope='col'>Formador</th>
						<th scope='col'>Carga Horária</th>
					</tr>
				</thead>
				<tbody>
			";	
			if($temas = $this->temaModel->getTemasInscricoesById($id)){					
				$i = 0;
				foreach($temas as $tema){						
					$aberto = $this->inscricaoModel->inscricaoAberta($tema->inscricoes_id);					
					$i++;                
					$html .= "
						<tr class='text-center'>
							<th scope='row'>
					"; 
					if($aberto){
						$html .="<button type='button' class='btn btn-danger' onClick=remover($tema->id)>Remover</button>";
					}					
					$html .= "
						</th>
							<td>$tema->tema</td>
							<td>$tema->formador</td>
							<td>$tema->carga_horaria</td>
						</tr>  
					";
				}	
			} else {
				$html .= "
					<tr class='text-center'>
						<td colspan='4'>
							Nenhum tema cadastrado
						</td> 
					</tr> 
				";
			}
			$html .= '</tbody>';	
			echo $html; 
		}		
		
		public function add($inscricoes_id){	
			$data=[
				'inscricoes_id' => $inscricoes_id,
				'tema'=>$_POST['tema'],
				'carga_horaria'=>$_POST['carga_horaria'],
				'formador'=>$_POST['formador']                
			];
			$error=[];

			//valida tema
			if(empty($data['tema'])){
				$error['tema_err'] = 'Por favor informe o tema!';
			}		

			if(
					empty($error['tema_err']) 
				)
			{                
				try{
					if($this->temaModel->register($data)){                        
						$json_ret = array(                                            
							'error'=>false
						); 
						echo json_encode($json_ret); 
					}     
				} catch (Exception $e) {
					$json_ret = array(
						'classe'=>'alert alert-danger', 
						'message'=>'Erro ao gravar os dados',
						'error'=>$data
					);                     
					echo json_encode($json_ret); 
				}						
			} else {
				$json_ret = array(
					'classe'=>'alert alert-danger', 
					'message'=>'Erro ao tentar gravar os dados',
					'error'=>$error
				);
				echo json_encode($json_ret);
			}                               
		}

		public function delete($id){	
			try{
				if($this->temaModel->deleteTema($id)){                        
					$json_ret = array(                                            
						'error'=>false
					);  
					echo json_encode($json_ret); 
				}     
			} catch (Exception $e) {
				$json_ret = array(
					'classe'=>'alert alert-danger', 
					'message'=>'Erro ao tentar excluir os dados',
					'error'=>$data
				);                     
				echo json_encode($json_ret); 
			}
		}
	}
?>