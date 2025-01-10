<?php 
  class Presencas extends Controller{
    public function __construct(){ 
      if((!isLoggedIn())){ 
        flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
        redirect('pages/index');
        die();
      }         
      $this->abrePresencaModel = $this->model('Abrepresenca');
      $this->grupoModel = $this->model('Grupo');
      $this->inscricaoModel = $this->model('Inscricoe');
      $this->inscritoModel = $this->model('Inscrito');
      $this->temaModel = $this->model('Tema');
      $this->userModel = $this->model('User');    
      $this->presencaModel = $this->model('Presenca');  
    }

    //Valida o id para excluir ou editar
    public function validaId($id){      
			if(!is_numeric($id)){
				flash('message', 'ID inválido.', 'error'); 
        redirect('inscricoes/index');
        die();			
			} else if(!$data = $this->abrePresencaModel->getAbrePresencasById($id)) {
        flash('message', 'Abre Presença inexistente.', 'error'); 
        redirect('inscricoes/index');
        die();	
      }
      return $data;
    }    
      
    public function index($abre_presenca_id){          

      $this->getPermicao('editar',$_SESSION[SE.'_user_id']);

      $abrePresenca = $this->validaId($abre_presenca_id);

      $inscricoes_id = $this->abrePresencaModel->getInscricaoId($abre_presenca_id)->inscricoes_id; 
      $data = [
        'abre_presenca_id' => $abre_presenca_id, 
        'inscricoes_id' => $inscricoes_id,        
        'titulo' => 'Registro de Presenca',
        'description'=> 'Registre aqui sua presença',
        'curso' => $this->inscricaoModel->getInscricaoById($inscricoes_id),
        'presenca_em_andamento' => $this->abrePresencaModel->temPresencaEmAndamento($inscricoes_id),
        'cpf_err' => '',
        'qrCode' => 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . rawurlencode(URLROOT . '/presencas/qr&presenca_em_andamento=' . $abre_presenca_id . '&userId=' . $_SESSION[SE . '_user_id']),
      ];  
      $this->view('presencas/index', $data);
    }  

    public function qr(){ 
           
      if((!isLoggedIn())){ 
        $json_ret = array
        (
          'class'=>'error', 
          'message'=>'Sua sessão expirou, você deve fazer o login primeiro!',
          'error'=>$data
        );                     
        echo json_encode($json_ret); 
        die();
      }    

      $data = [
        'abre_presenca_id' => get('presenca_em_andamento'),
        'user_id' => get('userId')
      ];   
      
      
      $inscricaoId = $this->abrePresencaModel->getInscricaoId($data['abre_presenca_id'])->inscricoes_id;
      
      if(!$this->inscritoModel->estaInscrito($inscricaoId,$data['user_id'])){
        $json_ret = array
        (
          'class'=>'error', 
          'message'=>'Você não está inscrito neste curso!',
          'error'=>$data
        );                     
        echo json_encode($json_ret); 
        die();
      }

      try {        
        if($this->presencaModel->jaRegistrado($data)){
          throw new Exception('Ops! Você já tem presença neste curso!');
        } else {
          if($this->presencaModel->register($data)){          
            $json_ret = array(                                            
              'class'=>'success', 
              'message'=>'Presença registrada com sucesso!!',
              'error'=>false
            );    
            echo json_encode($json_ret); 
            die();
          } else {                                
            throw new Exception('Ops! Algo deu errado ao tentar registrar a presença! Tente novamente.');
          } 
        }  
      } catch (Exception $e) {
        $erro = 'Erro: '.  $e->getMessage();                                  
        $json_ret = array
        (
          'class'=>'error', 
          'message'=>$erro,
          'error'=>true
        );                     
        echo json_encode($json_ret);  
        die();
      }   
    }


    public function qrBkp(){      
      if((!isLoggedIn())){ 
        flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
        redirect('pages/index');
        die();
      }    
      $data = [
        'abre_presenca_id' => get('presenca_em_andamento'),
        'user_id' => get('userId')
      ]; 
     
      
      try {
        if($this->presencaModel->jaRegistrado($data)){
          throw new Exception('Ops! Você já tem presença neste curso!');
        }
        if($this->presencaModel->register($data)){
          $confirmacaoArr = [
            'abre_presenca' => $this->abrePresencaModel->getInscricaoId($data['abre_presenca_id']),
            'user' => $this->userModel->getUserById($data['user_id']),
            'inscricao' => $this->abrePresencaModel->getInscricaoById($data['abre_presenca_id']),
            'classe' => 'bg-success',
            'mensagem' => 'Presença registrada com sucesso!'
          ];
          $this->view('presencas/confirmacaoqr',$confirmacaoArr);
        } else {                                
          throw new Exception('Ops! Algo deu errado ao tentar registrar a presença! Tente novamente.');
        } 

      } catch (Exception $e) {
        $erro = 'Erro: '.  $e->getMessage(); 
        $confirmacaoArr = [
          'abre_presenca' => $this->abrePresencaModel->getInscricaoId($data['abre_presenca_id']),
          'user' => $this->userModel->getUserById($data['user_id']),
          'inscricao' => $this->abrePresencaModel->getInscricaoById($data['abre_presenca_id']),
          'classe' => 'bg-danger',
          'mensagem' => $erro
        ];                               
        $this->view('presencas/confirmacaoqr',$confirmacaoArr);      
      }
     
     // $this->presencaModel->register($data);
      
    }

    public function fechar($abre_presenca_id){ 
      $this->abrePresencaModel->fecharPresenca($abre_presenca_id);
      $inscricoes_id = $this->abrePresencaModel->getInscricaoId($abre_presenca_id);        
      redirect('abrepresencas/index/' . $inscricoes_id->inscricoes_id);
    } 

    public function add(){  
      $data = [
        'abre_presenca_id' => post('abre_presenca_id'),
        'user_id' => post('user_id')
      ];

      $error=[];
      if(empty($data['abre_presenca_id'])){
          $error['abre_presenca_id_err'] = 'Erro ao tentar recuperar o id da presença!';
      }

      if(empty($data['user_id'])){
        $error['user_id_err'] = 'Erro ao tentar recuperar o id usuário!';
      }

      //Se o usuário já tiver presença nesse curso eu dou a mensagem de erro
      if($this->presencaModel->jaRegistrado($data)){
        $json_ret = array(
          'classe'=>'alert alert-danger', 
          'message'=>'Usuário já registrado para esta presença!',
          'error'=>$data
        );                     
        echo json_encode($json_ret); 
        return;
      }   
      if(
          empty($error['abre_presenca_id_err']) && 
          empty($error['user_id_err']) 
        )
      {                
        try{
          if($this->presencaModel->register($data)){                        
            $json_ret = array(                                            
              'error'=>false,
              'classe'=>'alert alert-success',
              'message'=>'Presença Confirmada',
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
    }//add

    public function update(){  
      $data=[
        'abre_presenca_id' => post('abre_presenca_id'),
        'user_id'=> post('user_id')               
      ];  

      $error=[];    
      if(empty($data['abre_presenca_id'])){
          $error['abre_presenca_id_err'] = 'Erro ao tentar recuperar o id da presença!';
      }

      if(empty($data['user_id'])){
        $error['user_id_err'] = 'Erro ao tentar recuperar o id usuário!';
      }  

      if(
          empty($error['abre_presenca_id_err']) && 
          empty($error['user_id_err']) 
        )
      {                
        try{
          //removo a presença do usuário se ele tiver nesse curso
          if($this->presencaModel->removePresenca($data['abre_presenca_id'],$data['user_id'])){
            //se removeu certinho verifico se o usuário marcou ou desmarcou o checkbox
            if($_POST['presenca'] == 'true'){
              //se ele marcou eu marco a presença
              if($this->presencaModel->register($data)){                        
                $json_ret = array(                                            
                  'class'=>'success', 
                  'message'=>'Presença confirmada!',
                  'error'=>false
                );       
                echo json_encode($json_ret); 
              }     
            } else {
              if($this->presencaModel->removePresenca($data['abre_presenca_id'],$data['user_id'])){
                $json_ret = array(                                            
                  'class'=>'success', 
                  'message'=>'Presença removida!',
                  'error'=>false
                );    
                echo json_encode($json_ret);
              }
            }
          } 
        } catch (Exception $e) {
          $json_ret = array
            (
              'class'=>'error', 
              'message'=>'Erro ao gravar os dados!',
              'error'=>$data
            );                     
          echo json_encode($json_ret); 
        }          
      }   else {
          $json_ret = array(
            'class'=>'error', 
            'message'=>'Erro ao tentar gravar os dados!',
            'error'=>$error
          );
          echo json_encode($json_ret);
      }                             
    }//update 
    
    public function checkAll(){
      $data=[
        'abre_presenca_id' => (isset($_POST['abre_presenca_id'])) ? $_POST['abre_presenca_id'] : '',
        'usersIds' => (isset($_POST['usersIds'])) ? $_POST['usersIds'] : ''
      ];  

      $error=[];    
      if(empty($data['abre_presenca_id'])){
          $error['abre_presenca_id_err'] = 'Erro ao tentar recuperar o id da presença!';
      }

      if(empty($data['usersIds'])){
        $error['usersIds_err'] = 'Erro ao tentar recuperar o id usuário!';
      }

      if(
        empty($error['abre_presenca_id_err']) && 
        empty($error['user_id_err']) 
      )
      {                
        try{          
          //removo a presença do usuário se ele tiver nesse curso
          if($this->presencaModel->removeTodasPresencas($data['abre_presenca_id'])){   
            //marco todas as presenças
            if(!$this->presencaModel->checkAll($data)){                        
              throw new Exception('Erro ao tentar atualizar a presença.');
            } else {
              $json_ret = array(                                            
                'class'=>'success', 
                'message'=>'Presença atualizada!',
                'error'=>false
              );       
              echo json_encode($json_ret);
            }              
          } 
        } catch (Exception $e) {
          $json_ret = array
            (
              'class'=>'error', 
              'message'=>'Erro ao gravar os dados!',
              'error'=>$data
            );                     
          echo json_encode($json_ret); 
        }          
      }   else {
          $json_ret = array(
            'class'=>'error', 
            'message'=>'Erro ao tentar gravar os dados!',
            'error'=>$error
          );
          echo json_encode($json_ret);
      }     

      //var_dump($data['usersIds']);
      //var_dump($data['abre_presenca_id']);
    }

    // Função que valida se o usuário pode ou não apagar um grupo
    public function getPermicao($acao,$userId){      
      if(!$this->grupoModel->getPermicao($acao,$userId,'abre_presenca')){
        flash('message', 'Você não tem permissão para '. $acao.' na tabela abre presença.', 'error'); 
        if($acao === 'ler'){
          redirect('index');
        } else {
          redirect('inscricoes/index');
        }        
        die();
      }    	  		
    }




  }
  