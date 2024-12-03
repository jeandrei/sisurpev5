<?php
  class Fusercursosuperiores extends Controller{
      
    public function __construct(){
      if((!isLoggedIn())){ 
        flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
        redirect('users/login');
        die();
      }
      $this->escolaModel = $this->model('Escola');         
      $this->fuserescolaModel = $this->model('Fuserescolaano');
      $this->fuserformacoesModel = $this->model('Fuserformacao');
      $this->fareacursoModel = $this->model('Fareacurso');
      $this->fnivelcursoModel = $this->model('Fnivelcurso');
      $this->fcursossupModel = $this->model('Fcursossuperior');
      $this->fusercursossupModel = $this->model('Fusercursosuperior');
      $this->fuserPosModel = $this->model('Fuserpo');
      $this->municipioModel = $this->model('Municipio');
      $this->regiaoModel = $this->model('Regiao');
      $this->estadoModel = $this->model('Estado');           
    }

    public function getUserCursosSup(){
      if($userCursosSup = $this->fusercursossupModel->getCursosUser($_SESSION[DB_NAME . '_user_id'])){
        foreach($userCursosSup as $row) {
          $userCursosSupArray[] = [
            'ucsId' => $row->ucsId,
            'areaId' => $row->areaId,
            'area' => $this->fareacursoModel->getAreaById($row->areaId)->area,
            'nivelId' => $row->nivelId,
            'nivel' => $this->fnivelcursoModel->getNivelById($row->nivelId)->nivel,
            'cursoId' => $row->cursoId,
            'curso' => $this->fcursossupModel->getCursoById($row->cursoId)->curso,
            'tipoInstituicao' => $row->tipoInstituicao,
            'instituicaoEnsino' => $row->instituicaoEnsino,
            'municipioInstituicao' => $this->municipioModel->getMunicipioById($row->municipioId)->nomeMunicipio,
            'file' => $row->file                
          ];
        };
        return $userCursosSupArray;
      } else {
        return false;
      }
    }

    public function index() {  
      //se o usuário ainda não adicionou nenhuma escola, faço essa verificação para evitar passar para próxima etapa pelo link sem ter adicionado uma escola
      if(!$this->fuserformacoesModel->getUserFormacoesById($_SESSION[DB_NAME . '_user_id'])){
        flash('message', 'Você deve adicionar sua formação para informar os dados de curso superior!', 'error'); 
        redirect('fuserformacoes/index');
        die();
      }       
      $data = [
        'areasCurso' => 
          ($this->fareacursoModel->getAreasCurso())
          ? $this->fareacursoModel->getAreasCurso()
          : '',
        'nivelCurso' => 
          ($this->fnivelcursoModel->getNivelCurso())
          ? $this->fnivelcursoModel->getNivelCurso()
          : '',
        'cursosSuperiores' => 
          ($this->fcursossupModel->getCursosSup())
          ? $this->fcursossupModel->getCursosSup()
          : '',
        'tiposInstituicoes' => 
          (getTipoInstituicoes())
          ? getTipoInstituicoes()
          : '',
        'userId' => $_SESSION[DB_NAME . '_user_id'],
        'titulo' => 'Curso superior',
        'regioes' => 
          ($this->regiaoModel->getRegioes())
          ? $this->regiaoModel->getRegioes()
          : '',
        'regiaoId' => 
          isset($_POST['regiaoId'])
          ? html($_POST['regiaoId'])
          : '',
        'estados' => 
          isset($_POST['regiaoId'])
          ? $this->estadoModel->getEstadosRegiaoById($_POST['regiaoId'])
          : '',
        'estadoId' => 
          isset($_POST['estadoId'])
          ? html($_POST['estadoId'])
          : '',
        'estado' => 
          isset($_POST['estadoId'])
          ? $this->estadoModel->getEstadoById($_POST['estadoId'])
          : '',
        'municipioId' => 
          isset($_POST['municipioId'])
          ? html($_POST['municipioId'])
          : '',
        'municipio' => 
          isset($_POST['municipioId'])
          ? $this->municipioModel->getMunicipioById($_POST['municipioId'])
          : '',
        'municipios' => 
          isset($_POST['estadoId'])
          ? $this->municipioModel->getMunicipiosEstadoById($_POST['estadoId'])
          : '',
        'userCursosSup' => 
          ($this->getUserCursosSup())
          ? $this->getUserCursosSup()
          : ''
      ];  
      $this->view('fusercursosuperiores/index',$data);  
    }

    public function add(){           
      if($_SERVER['REQUEST_METHOD'] == 'POST'){              
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);            
        unset($data);            
        $data = [
          'areaId' => 
            isset($_POST['areaId'])
            ? trim($_POST['areaId'])
            : '',
          'nivelId' => 
            isset($_POST['nivelId'])
            ? trim($_POST['nivelId'])
            : '',
          'cursoId' => 
            isset($_POST['cursoId'])
            ? trim($_POST['cursoId'])
            : '',
          'userId' => $_SESSION[DB_NAME . '_user_id'],
          'titulo' => 'Curso superior',
          'regioes' => 
            ($this->regiaoModel->getRegioes())
            ? $this->regiaoModel->getRegioes()
            : '',
          'regiaoId' => 
            isset($_POST['regiaoId'])
            ? html($_POST['regiaoId'])
            : '',
          'estados' => 
            isset($_POST['regiaoId'])
            ? $this->estadoModel->getEstadosRegiaoById($_POST['regiaoId'])
            : '',
          'estadoId' => 
            isset($_POST['estadoId'])
            ? html($_POST['estadoId'])
            : '',
          'estado' => 
            isset($_POST['estadoId'])
            ? $this->estadoModel->getEstadoById($_POST['estadoId'])
            : '',
          'municipioId' => 
            isset($_POST['municipioId'])
            ? html($_POST['municipioId'])
            : '',
          'municipio' => 
            isset($_POST['municipioId'])
            ? $this->municipioModel->getMunicipioById($_POST['municipioId'])
            : '',
          'municipios' => 
            isset($_POST['estadoId'])
            ? $this->municipioModel->getMunicipiosEstadoById($_POST['estadoId'])
            : '',
          'tipoInstituicao' => 
            isset($_POST['tipoInstituicao'])
            ? trim($_POST['tipoInstituicao'])
            : '',
          'anoConclusao' => 
            isset($_POST['anoConclusao'])
            ? html($_POST['anoConclusao'])
            : '',
          'instituicaoEnsino' => 
            isset($_POST['instituicaoEnsino'])
            ? trim($_POST['instituicaoEnsino'])
            : '',
          'areasCurso' => 
            ($this->fareacursoModel->getAreasCurso())
            ? $this->fareacursoModel->getAreasCurso()
            : '',
          'nivelCurso' => 
            ($this->fnivelcursoModel->getNivelCurso())
            ? $this->fnivelcursoModel->getNivelCurso()
            : '',
          'cursosSuperiores' => 
            ($this->fcursossupModel->getCursosSup())
            ? $this->fcursossupModel->getCursosSup()
            : '',
          'userCursosSup' => 
            ($this->getUserCursosSup())
            ? $this->getUserCursosSup()
            : '',
          'tiposInstituicoes' => 
            (getTipoInstituicoes())
            ? getTipoInstituicoes()
            : '',
          'file' => 
            (isset($_FILES['file_post']) && !empty($_FILES['file_post']))
            ? $_FILES['file_post']
            : '',
          'areaId_err' => '',
          'cursoId_err' => '',
          'nivelId_err' => '',
          'tipoInstituicao_err' => '',
          'anoConclusao_err' => '',
          'instituicaoEnsino_err' => '',
          'regiaoId_err' => '',
          'estadoId_err' => '',
          'municipioId_err' => '',
          'file_post_err' => ''         
        ];                             
          
        // Valida areaId
        if(empty($data['areaId']) || ($data['areaId'] == 'null')){
          $data['areaId_err'] = 'Por favor informe a área do curso.';
        }  

        // Valida nivelId
        if(empty($data['nivelId']) || ($data['nivelId'] == 'null')){
          $data['nivelId_err'] = 'Por favor informe o nível do curso.';
        } 

        // Valida cursoId
        if(empty($data['cursoId']) || ($data['cursoId'] == 'null')){
          $data['cursoId_err'] = 'Por favor informe o curso.';
        }  

        // Valida regiaoId
        if(empty($data['regiaoId']) || ($data['regiaoId'] == 'null')){
          $data['regiaoId_err'] = 'Por favor informe a região da instituição de ensino.';
        }  
        
        // Valida estadoId
        if(empty($data['estadoId']) || ($data['estadoId'] == 'null')){
          $data['estadoId_err'] = 'Por favor informe o estado da instituição de ensino.';
        } 

        // Valida municipioId
        if(empty($data['municipioId']) || ($data['municipioId'] == 'null')){
          $data['municipioId_err'] = 'Por favor informe o município da instituição de ensino.';
        } 

        // Valida tipoInstituicao
        if(empty($data['tipoInstituicao']) || ($data['tipoInstituicao'] == 'null')){
          $data['tipoInstituicao_err'] = 'Por favor informe tipo da instituição.';
        } 

        // Valida ano de conclusão
        if(empty($data['anoConclusao']) || ($data['anoConclusao'] == 'null')){
          $data['anoConclusao_err'] = 'Por favor informe o ano de conclusão.';
        }

        // Valida nstituicaoEnsino
        if(empty($data['instituicaoEnsino']) || ($data['instituicaoEnsino'] == '')){
          $data['instituicaoEnsino_err'] = 'Por favor informe a instituição de ensino.';
        }                      
        
        if(                    
            empty($data['areaId_err'])&&
            empty($data['nivelId_err'])&&
            empty($data['cursoId_err'])&&
            empty($data['tipoInstituicao_err'])&&
            empty($data['regiaoId_err'])&&
            empty($data['estadoId_err'])&&
            empty($data['municipioId_err'])&&
            empty($data['instituicaoEnsino_err']) && 
            empty($data['anoConclusao_err']) &&
            empty($data['file_post_err'])
        ){
          // Register maiorEscolaridade
          try { 
                         
            //verifico se foi passado um arquivo
            if(!empty($_FILES['file_post']['name'])){              
                /**
              * Faz o upload do arquivo do input id=file_post 
              * Utilizando a função upload que está no arquivo helpers/functions
              * Se tiver erro vai retornar o erro em $data['errors'];
              */ 
              $lastId = $this->fusercursossupModel->getLastId();                              
              $newId = ($lastId + 1);                              
              $file = upload($arr = [
                'file' => 'file_post',
                'path' => 'uploads/diplomas/',
                'extensions' => ["jpeg","jpg","png"],
                'maxsize' => 2097152,
                'name' => $_SESSION[DB_NAME . '_user_name'] . "_" . $newId
              ]);        
              //ser retornou sucesso é que fez o upload do arquivo e o mesmo retorna o caminho do arquivo
              if($file['sucess']){
                $data['file'] = $file['file'];
              } else {
                if($file['errors']){
                  foreach($file['errors'] as $row){
                  $erro .= $row . ".";
                  }
                } else {
                  $erro = 'Ops! Algo deu errado ao tentar fazer o upload do arquivo.';
                }                                
                throw new Exception($erro);
              }
            } else {
              $data['file'] = '';
            }            
            if($lastId = $this->fusercursossupModel->register($data)) {              
              flash('message', 'Curso superior registrado com sucesso!','success');                        
              redirect('fusercursosuperiores/index');
              die();
            } else {                               
              throw new Exception('Ops! Algo deu errado ao tentar gravar os dados! Tente novamente.');
            } 
          } catch (Exception $e) { 
            if(isset($file['file'])){
              removeFile($file['file']);
            }                            
            $erro = 'Erro: '.  $e->getMessage(); 
            flash('message', $erro,'error');              
            redirect('fusercursosuperiores/add');        
          }  
        } else {
          $this->view('fusercursosuperiores/add', $data);
        }            
      } else {        
        if(!$this->fuserformacoesModel->getUserFormacoesById($_SESSION[DB_NAME . '_user_id'])){
          flash('message', 'Você deve adicionar sua formação para informar os dados de curso superior!', 'error'); 
          redirect('fuserformacoes/index');
          die();
        } 
        $data = [
          'areaId' => '',
          'nivelId' => '',
          'tipoInstituicao' => '',
          'regiaoId' => '',          
          'areasCurso' => $this->fareacursoModel->getAreasCurso(),
          'nivelCurso' => $this->fnivelcursoModel->getNivelCurso(),
          'tiposInstituicoes' => getTipoInstituicoes(),
          'userId' => $_SESSION[DB_NAME . '_user_id'],
          'titulo' => 'Curso superior',
          'regioes' => $this->regiaoModel->getRegioes(),          
          'instituicaoEnsino' => '',
          'areaId_err' => '',
          'cursoId_err' => '',
          'nivelId_err' => '',
          'tipoInstituicao_err' => '',
          'anoConclusao_err' => '',
          'instituicaoEnsino_err' => '',
          'regiaoId_err' => '',
          'estadoId_err' => '',
          'municipioId_err' => '',
          'file_post_err' => ''          
        ];       
        $this->view('fusercursosuperiores/add',$data);
      }
    }


    public function delete($_ucsId){          
      try {
        $file = $this->fusercursossupModel->getFile($_ucsId);
        $userId = $this->fusercursossupModel->getUserId($_ucsId);
        //$qtdCursosSup = count($this->fusercursossupModel->getCursosUser($userId));        
        if($this->fusercursossupModel->delete($_ucsId)){
          //se o usuário removeu todos os cursos superiores tenho que remover a especialização e os cursos de pós
          if(!$this->fusercursossupModel->getCursosUser($userId)){
            $this->fuserPosModel->deleteAllUserPosCurso($userId);
            $this->fuserPosModel->deleteAllPosUser($userId);            
          }          
          if(isset($file->file)){
            if(!removeFile($file->file)){                
              throw new Exception('Ops! Algo deu errado tentar excluir o arquivo!');
            }
          }             
          flash('message', 'Curso removido com sucesso!','success');                     
          redirect('fusercursosuperiores/index');
        } else {                        
          throw new Exception('Ops! Algo deu errado ao tentar excluir o curso!');
        }
      } catch (Exception $e) {                   
        $erro = 'Erro: '.  $e->getMessage();                      
        flash('message', $erro,'error');
        redirect('fusercursosuperiores/index');
      }
    }

    public function download($_ucsId){
      if(!$data = $this->fusercursossupModel->getFile($_ucsId)){
        $html = "<p>Erro ao tentar recuperar o anexo.</p>";
        return $html;
      } else {  
        $msg = download($data->file);          
        if($msg['sucess'] == false) {
          $html = "<p>Erro ao tentar recuperar o anexo.</p>";
          return $html;
        }
      }
    }    
  }   
?>