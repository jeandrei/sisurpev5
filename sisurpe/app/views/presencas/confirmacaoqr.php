<?php require APPROOT . '/views/inc/header.php';?>



<div class="jumbotron">
  <h3 class="display-6">Registro de frequência!</h3> 
  <div class="alert <?php echo $data['classe']?>" role="alert">
    <?php echo $data['mensagem'];?>
  </div>
  <hr class="my-4">
  <p><b>Usuário: </b></p><p><?php echo $data['user']->name;?></p>
  <p><b>Inscrição: </b> </p><p><?php echo $data['inscricao']->nome_curso;?></p>  
  <p class="lead">
    <a class="btn btn-primary btn-lg" href="<?php echo URLROOT; ?>/pages/index" role="button">Início</a>
  </p>
</div>


<?php require APPROOT . '/views/inc/footer.php'; ?>