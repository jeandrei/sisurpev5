<?php
  class Validar extends Controller {
    public function __construct(){   
      $this->inscricaoModel = $this->model('Inscricoe');
      $this->userModel = $this->model('User');


      $this->inscritoModel = $this->model('Inscrito');
      $this->temaModel = $this->model('Tema');
      $this->userModel = $this->model('User');
      $this->abrePresencaModel = $this->model('Abrepresenca');
      $this->presencaModel = $this->model('Presenca');
      $this->certificadoModel = $this->model('Certificado');
    }

    public function index(){ 
      $userId = get('userId');
      $inscricaoId = get('cursoId');     
      $inscricao = $this->inscricaoModel->getInscricaoById($inscricaoId);
      $user = $this->userModel->getUserById($userId); 
      $data = [
        'user' => $user,
        'inscricao'=> $inscricao              
      ];   
      $this->view('userscertificados/validar', $data);
    }     
    
  }//class Inscricoes
?>