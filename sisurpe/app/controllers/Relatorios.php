<?php
  class Relatorios extends Controller{

    public function __construct(){          
      $this->relatorioModel = $this->model('Relatorio');
      $this->escolaModel = $this->model('Escola');
      $this->coletaModel = $this->model('Coleta');
      $this->turmaModel = $this->model('Turma');
      $this->userescolacoletaModel = $this->model('Userescolacoleta');
      $this->posModel = $this->model('Fpo');
      $this->fuserPos = $this->model('Fuserpo');
    }

    public function index(){       
      $data = [
        'titulo' => 'Relatórios',              
      ];
      $this->view('relatorios/index',$data);
    }

    public function selectEscola($view){          
      $data = [
        'titulo' => 'Selecione a escola',
        'view' => $view,
        'escolaId_err' => '',
        'escolas' => $this->escolaModel->getEscolas(),
        'escolaId' => '',
        'erro' => ''
      ];     
      $this->view('relatorios/selectEscola',$data);
    }

    public function uniformePorEscola(){  
      if($escola = $this->escolaModel->getEscolaById($_GET['escolaId'])){
        if($turmas = $this->turmaModel->getTurmasEscolaById($_GET['escolaId'])){          
          foreach($turmas as $row){        
            $turmasArr[] = [
              'turmaId' => $row->id,
              'turma' => $row->descricao,
              'coleta' => $this->coletaModel->getColetaByTurma($row->id),
              'kit_inverno' => $this->coletaModel->totaisUniforme($row->id,'kit_inverno'),
              'kit_verao' => $this->coletaModel->totaisUniforme($row->id,'kit_verao'),     
              'tam_calcado' => $this->coletaModel->totaisCalcado($row->id,'tam_calcado')            
              
            ]; 
          } 
          $data = [            
            'escola' => $escola,
            'turmas' => $turmasArr,
            'totalUniforme' => $this->coletaModel->totaisEscolaUniforme($_GET['escolaId']),
            'totalCalcado' => $this->coletaModel->totaisEscolaCalcado($_GET['escolaId'])
          ];   
          $this->view('relatorios/coletaPorEscola',$data);             
        } else {
          $data = [             
            'erro' => 'Escola não possui turmas!'
          ];         
          $this->view('relatorios/coletaPorEscola',$data);
        }
      } else {
        $data = [           
          'erro' => 'Selecione uma escola!'          
        ];          
        $this->view('relatorios/coletaPorEscola',$data);
      }    
    } 

    public function transportePorEscola(){        
      if($escola = $this->escolaModel->getEscolaById($_GET['escolaId'])){
        if($turmas = $this->turmaModel->getTurmasEscolaById($_GET['escolaId'])){          
          foreach($turmas as $row){        
            $turmasArr[] = [
              'turmaId' => $row->id,
              'turma' => $row->descricao,
              'coleta' => $this->coletaModel->getColetaByTurma($row->id),
              'totaisTransporte' => $this->coletaModel->totaisTransporte($row->id)
            ]; 
          } 
          $linhasArr = getLinhas();          
          foreach($linhasArr as $row){
            $totaisLinhasArr[] = [
              'linha' => $row,
              'total' => $this->coletaModel->getTotaisLinhasEscola($_GET['escolaId'],$row)
            ];
          }
          
          $data = [            
            'escola' => $escola,
            'turmas' => $turmasArr,
            'totalGeral' => $totaisLinhasArr            
          ];          
          $this->view('relatorios/transportePorEscola',$data);             
        } else {
          $data = [             
            'erro' => 'Escola não possui turmas!'
          ];         
          $this->view('relatorios/transportePorEscola',$data);
        }
      } else {
        $data = [           
          'erro' => 'Selecione uma escola!'          
        ];          
        $this->view('relatorios/transportePorEscola',$data);
      }    
    } 
         
    public function rfespecializacao(){ 
      if($_GET['escolaId']=='null'){
        $escolaId = 'null';
        $data['escola']='Todas';
      } else {
        $data['escola'] = $this->escolaModel->getEscolaById($_GET['escolaId']);
        $escolaId = $data['escola']->id;
      }       
      $data['result'] = $this->fuserPos->getUsersPos($escolaId,date("Y"));       
      if($data['result']){        
        $this->view('relatorios/rfuserposporescola',$data);
      } else {
        die('Sem dados para emitir');
      }          
    }

    // o mesmo relatório do rfespecializacao só que com o CPF
    public function rfespecializacaocpf(){  
      if($_GET['escolaId']=='null'){
        $escolaId = 'null';
        $data['escola']='Todas';
      } else {
        $data['escola'] = $this->escolaModel->getEscolaById($_GET['escolaId']);
        $escolaId = $data['escola']->id;
      }                           
      $data['result'] = $this->fuserPos->getUsersPos($escolaId,date("Y")); 
      if($data['result']){
        $this->view('relatorios/rfuserposporescolacpf',$data);
      } else {
        die('Sem dados para emitir');
      }          
    }

    public function fusersemrespostapos(){           
      if($_GET['escolaId']=='null'){
        $escolaId = 'null';
        $data['escola']='Todas';
      } else {
        $data['escola'] = $this->escolaModel->getEscolaById($_GET['escolaId']);
        $escolaId = $data['escola']->id;
      }       
      $data['result'] = $this->fuserPos->getUsersSemRespostaPos($escolaId,date("Y")); 
      if($data['result']){
        $this->view('relatorios/rfusersemrespostapos',$data);
      } else {
        die('Sem dados para emitir');
      }          
    }    
  }