<?php
  class Inscricoes extends Controller {
    public function __construct(){           
      if((!isLoggedIn())){ 
        flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
        redirect('pages/index');
        die();
      }   
      $this->inscricaoModel = $this->model('Inscricoe');
      $this->inscritoModel = $this->model('Inscrito');
      $this->temaModel = $this->model('Tema');
      $this->userModel = $this->model('User');
      $this->abrePresencaModel = $this->model('Abrepresenca');
      $this->presencaModel = $this->model('Presenca');
      $this->certificadoModel = $this->model('Certificado');
      $this->grupoModel = $this->model('Grupo');
    }

    public function index(){ 
      unset($data);
      $inscricoesArr = $this->inscricaoModel->getInscricoes();
      if($inscricoesArr){
        foreach($inscricoesArr as $row){
          $inscricoes[] = [
            'id' => $row->id,
            'nome_curso' => $row->nome_curso,
            'descricao' => $row->descricao,
            'data_inicio' => $row->data_inicio,
            'data_termino' => $row->data_termino,
            'numero_certificado' => $row->numero_certificado,
            'localEvento' => $row->localEvento,
            'periodo' => $row->periodo,
            'fase' => $row->fase,
            'horario' => $row->horario,
            'usuarioInscrito' => $this->inscritoModel->estaInscrito($row->id,$_SESSION[SE . '_user_id']),
            'usuarioTemPresenca' => $this->inscricaoModel->getPresencasUsuarioById($_SESSION[SE . '_user_id'],$row->id),
            'existeInscritos' => $this->inscritoModel->existeInscritos($row->id),
            'existePresenca' => $this->presencaModel->getPresencas($row->id),
            'existePresencaEmAndamento' => $this->abrePresencaModel->temPresencaEmAndamento($row->id),
            'cargaHoraria' => $this->temaModel->getTotalCargaHoraria($row->id)
          ];
        }
      } else {
        $inscricoes = false;
      }
            
      $data = [
        'titulo' => 'Inscrições',
        'description'=> 'Inscrições',
        'inscricoes' => $inscricoes        
      ];   
      $this->view('inscricoes/index', $data);
    }  

    public function arquivadas(){ 
      if(isset($_GET['page'])) {  
        $page = $_GET['page'];  
      } else {  
        $page = 1;  
      }     
      if(!isset($_GET['nomeInscricao'])){$_GET['nomeInscricao'] = '';}
      $options = array(
        'results_per_page' => 10,
        'url' => URLROOT . '/inscricoes/arquivadas?page=*VAR*&nomeInscricao=' . $_GET['nomeInscricao'],
        'using_bound_params' => true,
        'named_params' => array(                                        
                                    ':nomeInscricao' => $_GET['nomeInscricao']
                                )     
      );  
      $paginate = $this->inscricaoModel->getArquivadasPag($page, $options);   
      if($paginate->success == true){ 
        $data['paginate'] = $paginate;          
        $results = $paginate->resultset->fetchAll(); 
      }    
      $data = [
        'titulo' => 'Inscrições Arquivadas',
        'paginate' => $paginate,
        'results'=> $results             
      ];  
      $this->view('inscricoes/arquivadas', $data);
    }  

    public function reabrir($inscricoes_id){
      $id = $this->inscricaoModel->reabreInscricao($inscricoes_id);
      redirect('inscricoes/index/');
    }

    /* quando a inscrição é feita pelo usuário */
    public function inscrever($inscricoes_id){ 
      $error=[];
      if(empty($inscricoes_id)){
        $error['inscricoes_id_err'] = 'Id obrigatório';
      }
      if($this->inscritoModel->verificaJaInscrito($inscricoes_id,$_SESSION[SE . '_user_id'])){
        $error['inscricoes_id_err'] = 'Ops! Usuário já está inscrito no curso!';  
      }        
      if(empty($error['inscricoes_id_err'])){
        if($this->inscritoModel->gravaInscricao($inscricoes_id,$_SESSION[SE . '_user_id'])){ 
          unset($data);
          $inscricoesArr = $this->inscricaoModel->getInscricoes();
          foreach($inscricoesArr as $row){
            $inscricoes[] = [
              'id' => $row->id,
              'nome_curso' => $row->nome_curso,
              'descricao' => $row->descricao,
              'data_inicio' => $row->data_inicio,
              'data_termino' => $row->data_termino,
              'numero_certificado' => $row->numero_certificado,
              'localEvento' => $row->localEvento,
              'periodo' => $row->periodo,
              'fase' => $row->fase,
              'horario' => $row->horario,
              'usuarioInscrito' => $this->inscritoModel->estaInscrito($row->id,$_SESSION[SE . '_user_id']),
              'usuarioTemPresenca' => $this->inscricaoModel->getPresencasUsuarioById($_SESSION[SE . '_user_id'],$row->id),
              'existeInscritos' => $this->inscritoModel->existeInscritos($row->id),
              'existePresenca' => $this->presencaModel->getPresencas($row->id),
              'existePresencaEmAndamento' => $this->abrePresencaModel->temPresencaEmAndamento($row->id),
              'cargaHoraria' => $this->temaModel->getTotalCargaHoraria($row->id)
            ];
          }          
          $data = [
            'titulo' => 'Inscrições',
            'description'=> 'Inscrições',
            'inscricoes' => $inscricoes        
          ];                       
          $this->view('inscricoes/index', $data);
        }   
      } else {
        $data = [
          'titulo' => 'Inscrições Abertas',
          'description'=> 'Inscrições Abertas',
          'inscricoes' => $this->inscricaoModel->getInscricoes()                          
        ];  
        flash('mensagem', 'Ops! Usuário já inscrito no curso.','alert alert-warning');                       
        $this->view('inscricoes/index', $data);           
      } 
    }

    public function cancelar($inscricoes_id){  
      $error=[];
      if(empty($inscricoes_id)){
        $error['inscricoes_id_err'] = 'Id obrigatório';
      }        
      if(empty($error['inscricoes_id_err'])){          
        if($this->inscritoModel->cancelaInscricao($inscricoes_id,$_SESSION[SE . '_user_id'])){
          unset($data);
          $inscricoesArr = $this->inscricaoModel->getInscricoes();
          foreach($inscricoesArr as $row){
            $inscricoes[] = [
              'id' => $row->id,
              'nome_curso' => $row->nome_curso,
              'descricao' => $row->descricao,
              'data_inicio' => $row->data_inicio,
              'data_termino' => $row->data_termino,
              'numero_certificado' => $row->numero_certificado,
              'localEvento' => $row->localEvento,
              'periodo' => $row->periodo,
              'fase' => $row->fase,
              'horario' => $row->horario,
              'usuarioInscrito' => $this->inscritoModel->estaInscrito($row->id,$_SESSION[SE . '_user_id']),
              'usuarioTemPresenca' => $this->inscricaoModel->getPresencasUsuarioById($_SESSION[SE . '_user_id'],$row->id),
              'existeInscritos' => $this->inscritoModel->existeInscritos($row->id),
              'existePresenca' => $this->presencaModel->getPresencas($row->id),
              'existePresencaEmAndamento' => $this->abrePresencaModel->temPresencaEmAndamento($row->id),
              'cargaHoraria' => $this->temaModel->getTotalCargaHoraria($row->id)
            ];
          }          
          $data = [
            'titulo' => 'Inscrições',
            'description'=> 'Inscrições',
            'inscricoes' => $inscricoes        
          ];                             
          $this->view('inscricoes/index', $data); 
        }   
      } else {
        return $error['inscricoes_id_err'];
      } 
    }

    public function add(){ 
      if($_SERVER['REQUEST_METHOD'] == 'POST'){   
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);  
        $data = [ 
          'titulo' => 'Adicionar uma inscrição',
          'inscricoes_id' => '',
          'nome_curso' => mb_strtoupper(post('nome_curso')),
          'descricao' => mb_strtoupper(post('descricao')),
          'data_inicio' => post('data_inicio'),
          'data_termino' => post('data_termino'),
          'localEvento' => post('localEvento'),
          'horario' => post('horario'),
          'periodo' => post('periodo'),
          'fase' => post('fase'),
          'certificado' => post('certificado'),          
          'fase_err' => '',
          'editavel' => false,
          'tema' => '',
          'tema_err' => '',
          'carga_horaria_tema' => '',
          'carga_horaria_tema_err' => '',
          'formador' => '',
          'formador_err' => '',
          'nome_curso_err'  => '',
          'descricao_err' => '',
          'data_inicio_err' => '',
          'data_termino_err' => '',
          'localEvento_err' => '',
          'horario_err' => '',
          'periodo_err' => '',        
          'certificado_err' => ''  
        ];
                
        if(empty($data['nome_curso'])){
          $data['nome_curso_err'] = 'Por favor informe o nome do curso';
        }
        
        if(empty($data['descricao'])){
          $data['descricao_err'] = 'Por favor informe a descrição do curso';
        }              
        
        if (!validaData($data['data_inicio'])){
          $data['data_inicio_err'] = 'Data inválida';
        }

        if (!validaData($data['data_termino'])){
          $data['data_termino_err'] = 'Data inválida';
        } else {
          if($data['data_termino'] < $data['data_inicio']){
            $data['data_termino_err'] = 'Data de termino menor que data de início';
          }
        } 
        
        if(empty($data['localEvento'])){
          $data['localEvento_err'] = 'Por favor informe o local onde será realizado o curso';
        } 

        if(empty($data['horario'])){
          $data['horario_err'] = 'Por favor informe o horário que iniciará o curso';
        } 

        if(empty($data['periodo'])){
          $data['periodo_err'] = 'Por favor informe período que acontecerá o curso';
        }         
        
        if(                    
          empty($data['nome_curso_err']) &&
          empty($data['descricao_err']) &&                  
          empty($data['data_inicio_err']) &&
          empty($data['data_termino_err']) &&
          empty($data['localEvento_err']) &&
          empty($data['horario_err']) &&
          empty($data['periodo_err'])            
        ){               
          try {                          
            if($lastId = $this->inscricaoModel->register($data)){
              // verifico se a inscrição é editavel ou seja se ela não está fechada ou arquivada
              $editavel = $this->inscricaoModel->inscricaoEditavel($lastId);
              // pego o id da inscrição criada
              $inscricoes_id = $lastId; 
              // pega os temas se o usuário estiver adicionando
              $temas = $this->temaModel->getTemasInscricoesById($lastId);
              $data = [
                'titulo' => 'Adicionar uma inscrição',
                'nome_curso' => mb_strtoupper(post('nome_curso')),
                'descricao' => mb_strtoupper(post('descricao')),
                'data_inicio' => post('data_inicio'),
                'data_termino' => post('data_termino'),
                'localEvento' => post('localEvento'),
                'horario' => post('horario'),
                'periodo' => post('periodo'),
                'fase' => post('fase'),
                'editavel' => $editavel,
                'inscricoes_id' => $inscricoes_id,
                'temas' => $temas,
                'certificado' => post('certificado'),
                'fase_err' => '',
                'tema' => '',
                'tema_err' => '',
                'carga_horaria_tema' => '',
                'carga_horaria_tema_err' => '',
                'formador' => '',
                'formador_err' => '',
                'nome_curso_err'  => '',
                'descricao_err' => '',
                'data_inicio_err' => '',
                'data_termino_err' => '',
                'localEvento_err' => '',
                'horario_err' => '',
                'periodo_err' => '',
                'certificado_err' => '' 
              ];
              flash('message', 'Dados registrados com sucesso');  
              $this->view('inscricoes/add', $data);  
            } else {
              throw new Exception('Ops! Algo deu errado ao tentar gravar os dados!');
            }                 
          } catch (Exception $e) {
            $erro = 'Erro: '.  $e->getMessage(). "\n";
            flash('message', $erro,'error');
            $this->view('inscricoes/add');
          } 
        } else {          
          // Load the view with errors          
          $this->view('inscricoes/add', $data);
        } 
      } else {
        $modelosCertificados = $this->certificadoModel->getCertificados();  

        $data = [ 
          'titulo' => 'Adicionar uma inscrição',
          'inscricoes_id' => '',
          'nome_curso' => '',
          'descricao' => '',
          'data_inicio' => '',
          'data_termino' => '',
          'localEvento' => '',
          'horario' => '',
          'periodo' => '',
          'fase' => '',
          'nome_curso_err' => '', 
          'descricao_err' => '',   
          'data_inicio_err' => '',   
          'data_termino_err' => '',   
          'localEvento_err' => '',   
          'horario_err' => '',   
          'periodo_err' => '',                
          'fase_err' => '',
          'editavel' => false,
          'tema' => '',
          'tema_err' => '',
          'carga_horaria_tema' => '',
          'certificado' => '',
          'carga_horaria_tema_err' => '',
          'formador' => '',
          'formador_err' => '',
          'nome_curso_err'  => '',
          'descricao_err' => '',
          'data_inicio_err' => '',
          'data_termino_err' => '',
          'localEvento_err' => '',
          'horario_err' => '',
          'periodo_err' => '',
          'certificado_err' => '',
          'modelosCertificados' => $modelosCertificados     
        ];
        $this->view('inscricoes/add', $data);
      }     
    }//add

    public function edit($id){  

      $modelosCertificados = $this->certificadoModel->getCertificados();

      if($_SERVER['REQUEST_METHOD'] == 'POST'){           
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); 
        $temas = $this->temaModel->getTemasInscricoesById($id); 
        $editavel = $this->inscricaoModel->inscricaoEditavel($id);  
        $data = [ 
          'titulo' => 'Editar uma inscrição',    
          'id' => $id,         
          //'inscricoes_id' => $id,            
          'nome_curso' => mb_strtoupper(post('nome_curso')),
          'descricao' => mb_strtoupper(post('descricao')),            
          'data_inicio' => post('data_inicio'),
          'data_termino' => post('data_termino'),
          'data_atual' => DATAATUAL,
          'localEvento' => post('localEvento'),
          'horario' => post('horario'),
          'periodo' => post('periodo'),
          'fase' => post('fase'),
          'temas' => ($temas) ? $temas : 'null',
          'editavel' => $editavel,
          'certificado' => post('certificado'),
          'data_inicio_err' => '', 
          'data_termino_err' => '',
          'nome_curso_err' => '',
          'descricao_err' => '',
          'localEvento_err' => '',
          'horario_err' => '',
          'periodo_err' => '',   
          'fase_err' => '',
          'tema' => '',
          'tema_err' => '',
          'carga_horaria_tema' => '',
          'carga_horaria_tema_err' => '',
          'formador' => '',
          'formador_err' => '',
          'certificado_err' => '',
          'modelosCertificados' => $modelosCertificados
        ];
        
        //se a data atual for menor que a data de início permito a edição e faço a validação
        if (DATAATUAL < $data['data_inicio']){
          if (!validaData($data['data_inicio'])){
            $data['data_inicio_err'] = 'Data inválida';
          }
        }

        //se a data atual for menor que a data de termino permito a edição e faço a validação
        if (DATAATUAL < $data['data_termino']){
          if (!validaData($data['data_termino'])){
            $data['data_termino_err'] = 'Data inválida';
          } else {
            if($data['data_termino'] < $data['data_inicio']){
              $data['data_termino_err'] = 'Data de termino menor que data de início';
            }
          }
        }      
      
        if(empty($data['nome_curso'])){
          $data['nome_curso_err'] = 'Por favor informe o nome do curso';
        }
      
        if(empty($data['descricao'])){
          $data['descricao_err'] = 'Por favor informe a descrição do curso';
        }
        if(empty($data['localEvento'])){
          $data['localEvento_err'] = 'Por favor informe o local onde será realizado o curso';
        } 

        if(empty($data['horario'])){
          $data['horario_err'] = 'Por favor informe o horário que iniciará o curso';
        } 

        if(empty($data['periodo'])){
          $data['periodo_err'] = 'Por favor informe período que acontecerá o curso';
        }  

        if(                    
          empty($data['nome_curso_err']) &&
          empty($data['descricao_err']) &&                  
          empty($data['data_inicio_err']) &&
          empty($data['data_termino_err']) &&
          empty($data['localEvento_err']) &&
          empty($data['horario_err']) &&
          empty($data['periodo_err']) 
        ){             
          try { 
            if($this->inscricaoModel->update($data)){                   
              // verifico se a inscrição é editavel ou seja se ela não está fechada ou arquivada
              $editavel = $this->inscricaoModel->inscricaoEditavel($id);
              // pego o id da inscrição criada
              $inscricoes_id = $id;  
              // pega os temas se o usuário estiver adicionando
              $temas = $this->temaModel->getTemasInscricoesById($id);
              $data = [
                'titulo' => 'Editar uma inscrição', 
                'nome_curso' => mb_strtoupper(post('nome_curso')),
                'descricao' => mb_strtoupper(post('descricao')),
                'data_inicio' => post('data_inicio'),
                'data_termino' => post('data_termino'),
                'localEvento' => post('localEvento'),
                'horario' => post('horario'),
                'periodo' => post('periodo'),
                'fase' => post('fase'),
                'editavel' => getData($editavel),
                'inscricoes_id' => getData($inscricoes_id),
                'temas' => getData($temas),
                'certificado' => post('certificado'),
                'data_atual' => DATAATUAL,
                'data_inicio_err' => '', 
                'data_termino_err' => '',
                'nome_curso_err' => '',
                'descricao_err' => '',
                'localEvento_err' => '',
                'horario_err' => '',
                'periodo_err' => '',
                'fase_err' => '',
                'tema' => '',
                'tema_err' => '',
                'carga_horaria_tema' => '',
                'carga_horaria_tema_err' => '',
                'formador' => '',
                'formador_err' => '',
                'certificado_err' => '',
                'modelosCertificados' => $modelosCertificados
              ];
              flash('message', 'Dados atualizados com sucessso!', 'success');   
              $this->view('inscricoes/edit', $data);  
            } else {
              throw new Exception('Ops! Algo deu errado ao tentar gravar os dados!');
            }                 
          } catch (Exception $e) {
            $erro = 'Erro: '.  $e->getMessage();
            flash('message', $erro, 'error');                
            $this->view('inscricoes/edit',$data);
          }
        } else {          
          $this->view('inscricoes/edit',$data);
        }   
      } else {
        $data = $this->inscricaoModel->getInscricaoById($id);        
        $data = [
          'titulo' => 'Editar uma inscrição', 
          'editavel' => $this->inscricaoModel->inscricaoEditavel($id),
          'inscricoes_id' => getData($id),
          'nome_curso' => getData($data->nome_curso),
          'descricao' => getData($data->descricao),             
          'data_inicio' => getData($data->data_inicio),
          'data_termino' => getData($data->data_termino),
          'numero_certificado' => getData($data->numero_certificado),          
          'localEvento' => getData($data->localEvento),
          'horario' => getData($data->horario),
          'periodo' => getData($data->periodo),
          'fase' => getData($data->fase),
          'certificado' => getData($data->certificado),
          'data_atual' => DATAATUAL,
          'data_inicio_err' => '', 
          'data_termino_err' => '',
          'nome_curso_err' => '',
          'descricao_err' => '',
          'localEvento_err' => '',
          'horario_err' => '',
          'periodo_err' => '',
          'fase_err' => '',
          'tema' => '',
          'tema_err' => '',
          'carga_horaria_tema' => '',
          'carga_horaria_tema_err' => '',
          'formador' => '',
          'formador_err' => '',
          'certificado_err' => '',
          'modelosCertificados' => $modelosCertificados  
        ];        
        $this->view('inscricoes/edit', $data);
      }     
    }//edit
    
    public function certificado($inscricoes_id){
      if($this->inscritoModel->estaInscrito($inscricoes_id,$_SESSION[SE . '_user_id'])){
        //$qrUrl = URLROOT . '/inscricoes/certificado&cursoId=' . $data['curso']->id . '&userId=' . $_SESSION[SE . '_user_id'];       
        $data = [
          'curso' => $this->inscricaoModel->getInscricaoById($inscricoes_id),
          'temas' => $this->temaModel->getTemasInscricoesById($inscricoes_id),
          'usuario' =>$this->userModel->getUserById($_SESSION[SE . '_user_id']),
          'presencas' =>$this->inscricaoModel->getPresencasUsuarioById($_SESSION[SE . '_user_id'],$inscricoes_id),
          'qrCode' => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . rawurlencode(URLROOT . '/validar&cursoId=' . $this->inscricaoModel->getInscricaoById($inscricoes_id)->id . '&userId=' . $_SESSION[SE . '_user_id']),
        ];         
        $this->view('relatorios/certificado', $data);
      } else {
        echo "Você não está inscrito para este curso!";
      }          
    }

    public function inscritos($inscricoes_id){      
      $data = [
        'inscritos' => $this->inscritoModel->getInscritos($inscricoes_id),
        'curso' => $this->inscricaoModel->getInscricaoById($inscricoes_id)
      ];       
      $this->view('relatorios/inscritos',$data);       
    }

    public function presentes($inscricoes_id){      
      $data=[
        'presentes' => $this->presencaModel->getPresencas($inscricoes_id),
        'curso' => $this->inscricaoModel->getInscricaoById($inscricoes_id)
      ];      
      $this->view('relatorios/presentes',$data);           
    }

    public function livroregistro($inscricoes_id){    
      $data=[
        'inscritos' => $this->inscritoModel->getInscritos($inscricoes_id),        
        'curso' => $this->inscricaoModel->getInscricaoById($inscricoes_id),
        'temas' => $this->temaModel->getTemasInscricoesById($inscricoes_id)        
      ];      
      $this->view('relatorios/livroregistro',$data);           
    }

    // Retorna true or false
    public function estaInscrito(){
      $userId = post('user_id');
      $inscricoes_id = post('inscricoes_id');          
      $json_ret = $this->inscritoModel->estaInscrito($inscricoes_id,$userId);   
      echo json_encode($json_ret);       
    }

    //monta o form para o usuário selecionar qual a abre inscrição ele que gerenciar
    public function abrePresencas($inscricoes_id){     
      if($abrePresencas = $this->abrePresencaModel->getAbrePresencasInscricaoById($inscricoes_id)){
        $data = [
          'curso' => $this->inscricaoModel->getInscricaoById($inscricoes_id),
          'temas' => $this->temaModel->getTemasInscricoesById($inscricoes_id),
          'usuario' =>$this->userModel->getUserById($_SESSION[SE . '_user_id']),
          'abre_presencas' => $abrePresencas
        ];            
        $this->view('inscricoes/abrePresencas', $data);
      } else {
        echo "Não tem nenhuma presença registrada para esta inscrição";
      }     
    }

    public function gerenciarPresencas($abrePresenca_id){      
      $inscricoes_id = $this->abrePresencaModel->getInscricaoById($abrePresenca_id)->id;  
      $data = [
        'abrePresencaId' => $abrePresenca_id,
        'curso' => $this->abrePresencaModel->getInscricaoById($abrePresenca_id),          
        'usuario' => $this->userModel->getUserById($_SESSION[SE . '_user_id']),
        'inscritos' => $this->inscritoModel->getInscritos($inscricoes_id),
        'todosPresentes' => $this->presencaModel->todosPresentes($abrePresenca_id)
      ];   
      
      $this->view('inscricoes/gerenciarPresencas', $data);      
    }

    /* carrega o formulário para que o  administrador selecione a inscrição que deseja inscrever um usuário*/
    public function inscreverUsuario($user_Id){     
      $data = [
        'titulo' => 'Inscrição de usuário',
        'user' => $this->userModel->getUserById($user_Id),        
        'inscricoes' => $this->inscricaoModel->getInscricoes()               
      ];
      $this->view('inscricoes/selecionarInscricao', $data);
    }

    /* quando a inscrição do usuário é feita pelo administrador */
    public function registrarInscricao(){  
      
      if(empty($_POST['userId'])){
        $error['userId_err'] = 'Ops! Algo deu errado ao selecionar o usuário! Tente novamente.';
      }
      
      if(empty($_POST['inscricaoId'])){
        $error['inscricaoId_err'] = 'Selecione uma inscrição!';
      }       
      
      $user_Id = post('userId');
      $inscricaoId = post('inscricaoId');   

      if(
        empty($error['userId_err']) &&
        empty($error['inscricaoId_err'])
      ){  
        /* primeiro verifico se o usuário já está inscrito */
        if($this->inscritoModel->estaInscrito($inscricaoId,$user_Id)){
          $json_ret = array(
            'class'=>'error', 
            'message'=>'Usuário já está inscrito!',
            'error'=>true
          );                     
          echo json_encode($json_ret); 
          die() ;
        }        
        /* caso o usuário não esteja inscrito realizo a inscrição */
        try{
          if($this->inscritoModel->gravaInscricao($inscricaoId,$user_Id)){  
            /* pego os abrepresença da inscrição */
            if(!$abrepresenca = $this->abrePresencaModel->getAbrePresencasInscricaoById($inscricaoId)){
              $json_ret = array(
                'class'=>'error', 
                'message'=>'Nenhuma presença aberta para esta inscrição!',
                'error'=>true
              );                     
              echo json_encode($json_ret); 
              die() ;
            }
            /* defino uma variável erro como null */
            $erro_presenca == NULL;
            /* para cada abrepresença registro a presença exemplo se for duas abrepersença de 4 horas vai registrar nas duas */
            foreach($abrepresenca as $row){
              $data['abre_presenca_id'] = $row->id;
              $data['user_id'] = $user_Id;
              if(!$this->presencaModel->register($data)){
                $erro_presenca = 'Houve um erro ao tentar registrar a presença!';
              }
            }            
            /* se não tem nenhum erro dentro de erro_presenca é que deu tudo certo*/
            //echo ($erro_presenca);
            if($erro_presenca == NULL){
              $json_ret = array(                                            
                'class'=>'success', 
                'message'=>'Inscrição realizada com sucesso!',
                'error'=>false
              );                               
              echo json_encode($json_ret); 
              die();
            } else {
              throw new Exception('Ops! Algo deu errado ao tentar registrar a presença!');
            }  
          } else {
            throw new Exception ('Ops! Algo deu errado ao tentar realizar a inscrição!');
          }     
        } catch (Exception $e) {
          $json_ret = array(
            'class'=>'error', 
            'message'=>'Erro ao tentar realizar a inscrição!' .  $e->getMessage(),
            'error'=>true
          );                     
          echo json_encode($json_ret); 
          die();
        }        
      } else {
        $json_ret = array(
          'class'=>'alert alert-danger', 
          'message'=>'Erro ao tentar realizar a inscrição tente novamente mais tarde' . $e->getMessage(),
          'error'=>true
        );
        echo json_encode($json_ret);
        die();
      }         
    }  
    
    public function confirm($id){ 
      $data = [
        'id' => $id,
        'inscricao' => $this->inscricaoModel->getInscricaoById($id)
      ];      
      $this->view('inscricoes/confirm',$data);
    }

    public function delete($id){      
      //VALIDAÇÃO DO ID
      if(!is_numeric($id)){
        flash('message', 'Id inválido!', 'error'); 
        redirect('inscricoes/arquivadas');
        die();
      }    
      if(isset($_POST['delete'])){
        if($this->inscricaoModel->delete($id)){
            flash('message', 'Inscrição excluida com sucessso!', 'success'); 
            redirect('inscricoes/arquivadas');
            die();
          } else {
            flash('message', 'Houve um erro ao tentar excluir a inscrição, tente excluir novamente.', 'error'); 
            redirect('inscricoes/arquivadas');
            die();
          }
      } else {                 
          redirect('inscricoes/arquivadas');
          die();
      }     
    } 
    
    // Função que valida se o usuário pode ou não apagar um grupo
    public function getPermicao($acao,$userId){      
      if(!$this->grupoModel->getPermicao($acao,$userId,'inscricoes')){
        flash('message', 'Você não tem permissão para '. $acao.' na tabela grupo.', 'error'); 
        if($acao === 'ler'){
          redirect('index');
        } else {
          redirect('pages/index');
        }        
        die();
      }    	  		
    }
  }//class Inscricoes
?>