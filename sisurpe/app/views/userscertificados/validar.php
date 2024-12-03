<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="card text-center">
  <div class="card-header">
    Validação de certificado
  </div>
  <div class="card-body">
    <h5 class="card-title">Validação do certificado para: <?php echo $data['user']->name;?></h5>
    <p class="card-text">Curso: <?php echo $data['inscricao']->nome_curso;?></p>
    <p class="card-text">Local: <?php echo $data['inscricao']->localEvento;?></p>    
  </div>
  <div class="card-footer text-muted">
    Certificado por: Secretaria de Educação de Penha/SC
  </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>