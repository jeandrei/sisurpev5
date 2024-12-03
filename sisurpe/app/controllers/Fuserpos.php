<?php
  class Fuserpos extends Controller{

    public function __construct(){
      if((!isLoggedIn())){ 
        flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
        redirect('users/login');
        die();
      }
      //vai procurar na pasta model um arquivo chamado User.php e incluir
      $this->escolaModel = $this->model('Escola');
      $this->bairroModel = $this->model('Bairro');
      $this->fuserescolaModel = $this->model('Fuserescolaano');
      $this->fuserformacoesModel = $this->model('Fuserformacao');
      $this->fusercursossupModel = $this->model('Fusercursosuperior');
      $this->fposModel = $this->model('Fpo');
      $this->fuserposModel = $this->model('Fuserpo');
    }

    public function index() {  
      //se o usuário ainda não adicionou nenhuma escola, faço essa verificação para evitar passar para próxima etapa pelo link sem ter adicionado uma escola
      if(!$this->fusercursossupModel->getUserFormacoesById($_SESSION[DB_NAME . '_user_id'])){
        flash('message', 'Você deve adicionar um curso superior primeiro primeiro!', 'error'); 
        redirect('fusercursosuperiores/index');
        die();
      }
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                                
        if(empty($_POST['pos'])){
          $data['pos_err'] = 'Por favor informe ao menos uma opção de pos graduação.';
        }  

        if(                    
          empty($data['pos_err'])
        ){
          try {
            if($this->fuserposModel->register($_POST['pos'],$_SESSION[DB_NAME . '_user_id'])){
              flash('message', 'Pós registrada com sucesso!','success');                        
              redirect('fuserpos/index');
            } else {                                
              throw new Exception('Ops! Algo deu errado ao tentar gravar os dados! Tente novamente.');
            } 

          } catch (Exception $e) {
            $erro = 'Erro: '.  $e->getMessage(); 
            flash('message', $erro,'error');                       
            redirect('fuserpos/index');        
          }  
        } else {   
          $data['pos'] = $this->fposModel->getPos();                                   
          $this->view('fuserpos/index', $data);
        } 
        
      } else {
        if($userPos = $this->fuserposModel->getUserPos($_SESSION[DB_NAME . '_user_id'])){
          foreach($userPos as $row){
            $userPosIdArray[] = $row->posId;
          } 
        } else {
          $userPosIdArray = 'null';
        }        
        $data = [
          'pos' => $this->fposModel->getPos(),
          'userPos' => $userPos,
          'userPosId' => $userPosIdArray,
          'pos_err' => '',
          'avancarLink' => ($userPosIdArray[0] == 1) ? URLROOT .'/fuseroutroscursos/index' : URLROOT .'/fusercursoespecializacoes/index'
        ];                   
        $this->view('fuserpos/index',$data);
      } 
    } 
  }   
?>