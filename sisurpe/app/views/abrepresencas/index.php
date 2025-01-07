<?php require APPROOT . '/views/inc/header.php';?>
  
  <?php flash('message');?>

  <div class="row align-items-center mb-3">
    <div class="col-md-12">
      <h2>Dados do Curso</h2> 
      <h6><b>Nome:</b> <?php echo $data['curso']->nome_curso;?></h6>   
      <h6><b>Descrição:</b> <?php echo $data['curso']->descricao;?></h6> 
      <form action="<?php echo URLROOT; ?>/abrepresencas/add" method="post" enctype="multipart/form-data">
        <input 
          type="hidden" 
          id="inscricoes_id" 
          name="inscricoes_id" 
          value="<?php echo (($data['curso']->id)) ? $data['curso']->id : $_POST['inscricoes_id']; ?>
        "> 
        <div class="form-row">
          <!--CARGA HORÁRIA-->
          <div class="form-group col-md-4">
            <label for="carga_horaria"><sup class="obrigatorio">*</sup> Carga Horária:</label>  
            <input 
                class="form-control <?php echo (!empty($data['carga_horaria_err'])) ? 'is-invalid' : ''; ?>"
                type="text" 
                name="carga_horaria"
                id="carga_horaria"
                value="<?php echo ($data['presenca_em_andamento'])?$data['presenca_em_andamento']->carga_horaria:'';?>"                                       
                <?php echo (($data['presenca_em_andamento'])) ? 'readonly' : ''; ?>
            >
            <div class="invalid-feedback">
                <?php echo $data['carga_horaria_err']; ?>
            </div>                   
          </div> 
        </div><!-- row --> 
              
            <?php if($data['presenca_em_andamento']) : ?>
              
              
              <a class="btn btn-danger" href="<?php echo URLROOT; ?>/presencas/fechar/<?php echo $data['presenca_em_andamento']->id;?>" role="button">Fechar Presença</a>

              <a class="btn btn-success" href="<?php echo URLROOT; ?>/presencas/index/<?php echo $data['presenca_em_andamento']->id;?>" role="button">Iniciar Presença</a>

            <?php else: ?>
              <button type="submit" class="btn btn-primary mt-2">Abrir Presença</button> 
            <?php endif; ?>
      </form>
    </div><!--col-md-12-->
  </div><!--div class="row align-items-center mb-3--> 
 


<?php require APPROOT . '/views/inc/footer.php'; ?>

