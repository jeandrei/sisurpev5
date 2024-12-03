<?php require APPROOT . '/views/inc/header.php'; ?>

  <?php flash('mensagem');?>

  <hr>
    <div class="p-3 text-center">
      <h2><?php echo $data['title'];?></h2>
    </div>
  <hr>

  <!-- SE FOR UM USUÁRIO ADMIN OU SEC ADICIONO O BOTÃO CRIAR INSCRIÇÃO -->
  <?php if((isset($_SESSION[DB_NAME . '_user_type']))&&((($_SESSION[DB_NAME . '_user_type']) == "admin")||(($_SESSION[DB_NAME . '_user_type']) == "sec"))) : ?>    
    <div class="row mb-3">  
      <div class="col-md-12">
          <a href="<?php echo URLROOT; ?>/inscricoes/add" class="btn btn-primary pull-right ml-2">
              <i class="fa fa-pencil"></i> Criar uma Inscrição
          </a>
      
          <a href="<?php echo URLROOT; ?>/inscricoes/arquivadas" class="btn btn-secondary pull-right">
              <i class="fa fa-folder ml-2"></i> Arquivadas
          </a>
      </div>
    </div> 
  <?php endif; ?>

  <?php
    // Caso ainda não tenham registros de aluno para o usuário
    if(empty($data['inscricoes'])){ 
      $data = ['error' => "No momento não temos nenhuma inscrição em aberto"]; 
    }
    if(isset($data['error'])){ 
      die($data['error']);
    } 
  ?> 
  <?php foreach ($data['inscricoes'] as $registro): ?>
    <!-- INÍCIO DE CADA CARD -->
    <div class="card mb-3">
      <!-- card-body -->
      <div class="card-body">
        <!-- SE FOR UM USUÁRIO ADMIN OU SEC ADICIONO O BOTÃO EDITAR -->
        <?php if((isset($_SESSION[DB_NAME . '_user_type']))&&((($_SESSION[DB_NAME . '_user_type']) == "admin")||(($_SESSION[DB_NAME . '_user_type']) == "sec"))) : ?>
          <!-- row dos comandos -->
          <div class="row">
            <div class="col-12">
              <div class="d-flex p-3 bg-dark text-white">
                <a href="<?php echo URLROOT; ?>/inscricoes/edit/<?php echo $registro->id?>" class="btn btn-primary btn-sm m-2">
                  <i class="fa fa-pencil"></i> 
                  Editar
                </a>                  
                <?php if($registro->fase == 'FECHADO') : ?>
                  <a href="<?php echo URLROOT; ?>/abrepresencas/index/<?php echo $registro->id?>" class="btn btn-secondary btn-sm m-2">
                    <i class="fa fa-check"></i> 
                    Presenças
                  </a> 
                <?php endif;?>
                <?php if($this->inscritoModel->existeInscritos($registro->id)) : ?>
                  <a href="<?php echo URLROOT; ?>/inscricoes/abrePresencas/<?php echo $registro->id?>" class="btn btn-success btn-sm m-2">
                  <i class="fa fa-globe"></i> 
                    Gerenciar Presenças
                  </a>
                <?endif;?>
                <?php if($this->abrePresencaModel->temPresencaEmAndamento($registro->id)) : ?>
                  <span class="bg-danger btn-sm m-2">
                    Presença em andamento
                  </span>
                <?endif;?>
              </div>
            </div>
          </div>
          <!-- row dos comandos -->
        <?endif;?>
        <!-- FIM SE FOR UM USUÁRIO ADMIN OU SEC ADICIONO O BOTÃO EDITAR -->
        <p class="card-title <?php echo(retornaClasseFase($registro->fase));?>">Fase: <?php echo($registro->fase);?></p>
        <!-- CURSO -->
        <p class="card-title"><b>Curso: </b><?php echo ($registro->nome_curso);?></p>
        <!-- DESCRIÇÃO -->
        <p class="card-text"><b>Descrição: </b><?php echo($registro->descricao);?></p>
        <!-- PERIODO -->
        <p class="card-text"><b>Período: </b><?php echo(formatadata($registro->data_inicio) . ' a '. formatadata($registro->data_termino));?></p> 
        <!-- LOCAL -->
        <p class="card-text"><b>Local: </b><?php echo($registro->localEvento);?></p>
        <!-- HORÁRIO DE INÍCIO -->
        <p class="card-text"><b>Horário de Início: </b><?php echo($registro->horario);?></p>
        <!-- PERÍODO DO DIA -->
        <p class="card-text"><b>Turno/Período: </b>
          <?php 
            switch ($registro->periodo) {
              case 'M':
                echo "Matutino";
                break;
              case 'V':
                echo "Vespertino";
                break;
              case 'D':
                echo "Dia Todo";
                break;
            }        
          ?>
        </p>
        <!-- CARGA HORÁRIA -->     
        <p class="card-text"><b>Carga Horária: </b><?php echo($this->temaModel->getTotalCargaHoraria($registro->id)) ? $this->temaModel->getTotalCargaHoraria($registro->id) . ' Horas' : 'Sem carga horária.';?></p>
        <!-- FIM CARGA HORÁRIA -->  
        <!-- SE FASE ABERTO HABILITAMOS O BOTÃO INSCREVER-SE -->
        <?php if($registro->fase == 'ABERTO') : ?>
          <?php if(!$this->inscritoModel->estaInscrito($registro->id,$_SESSION[DB_NAME . '_user_id'])) : ?>
            <a href="<?php echo URLROOT; ?>/inscricoes/inscrever/<?php echo $registro->id?>" class="btn btn-primary">Inscrever-se</a>
          <?php else: ?>
            <a href="<?php echo URLROOT; ?>/inscricoes/cancelar/<?php echo $registro->id?>" class="btn btn-warning">Cancelar Inscrição</a>
          <?php endif; ?>  
        <?php endif; ?> 
        <!-- FIM SE FASE ABERTO HABILITAMOS O BOTÃO INSCREVER-SE -->
        <!-- SE A FASE FOR CERTIFICADO -->
        <?php if($registro->fase == 'CERTIFICADO') : ?>  
          <!-- SE O USUÁRIO ESTIVER INSCRITO NO CURSO IMPRIMIMOS O BOTÃO CERTIFICADO -->
          <?php if($this->inscritoModel->estaInscrito($registro->id,$_SESSION[DB_NAME . '_user_id'])) : ?>
              <!-- SE TIVER PRESENÇA -->
              <?php if($this->inscricaoModel->getPresencasUsuarioById($_SESSION[DB_NAME . '_user_id'],$registro->id)) : ?>
                <a href="<?php echo URLROOT; ?>/inscricoes/certificado/<?php echo $registro->id?>" class="btn btn-success">Certificado Disponível</a>
              <?php else: ?>
                <div class="alert alert-warning" role="alert">
                  Que pena! Você se inscreveu, mas não marcou presença!
                </div> 
              <?php endif; ?>
              <!-- SE TIVER PRESENÇA -->
          <?php endif; ?>          
        <?php endif; ?>
        <!-- FIM SE A FASE FOR CERTIFICADO -->
        <!-- BOTÕES PARA IMPRIMIR AS LISTAS --> 
        <?php if((isset($_SESSION[DB_NAME . '_user_type']))&&((($_SESSION[DB_NAME . '_user_type']) == "admin")||(($_SESSION[DB_NAME . '_user_type']) == "sec"))) : ?>
          <p class="card-text mt-3">
          <?php if($this->inscritoModel->existeInscritos($registro->id)) : ?>
            <a href="<?php echo URLROOT; ?>/inscricoes/inscritos/<?php echo $registro->id?>" class="card-link"  target="_blank">
              Lista de Inscritos
            </a>
          <?endif;?>      
          <?php if($this->presencaModel->getPresencas($registro->id)) : ?>
            <a href="<?php echo URLROOT; ?>/inscricoes/presentes/<?php echo $registro->id?>" class="card-link" target="_blank">
              Lista de Presentes
            </a>
          <?endif;?> 
        <?php endif; ?>
        </p>
        <!-- FIM BOTÕES PARA IMPRIMIR AS LISTAS -->  
      </div>
      <!-- card-body -->
    </div>
    <!-- INÍCIO DE CADA CARD -->
  <?php endforeach; ?>              
 
<?php require APPROOT . '/views/inc/footer.php'; ?>

