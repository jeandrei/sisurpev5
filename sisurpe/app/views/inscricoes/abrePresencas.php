<?php require APPROOT . '/views/inc/header.php';?>

<?php flash('message');?>

<!-- ROW 1-->
<div class="row align-items-center mb-3">
  <!-- COL 1-->
  <div class="col-md-12 mt-2">
    <h5>Curso: <?php echo $data['curso']->nome_curso; ?></h5>
    <p>Descrição: <?php echo $data['curso']->descricao; ?></p> 
  </div>
  <!-- COL 1-->
</div>
<!-- ROW 1-->

<form action="<?php echo URLROOT; ?>/inscricoes/add" method="post" enctype="multipart/form-data">
  <!-- ROW 2-->
  <div class="row align-items-center mb-3">
    <!-- COL 1-->
    <div class="col-md-8 mt-2">
      <?php if($data['abre_presencas']) : ?>
        <?php foreach($data['abre_presencas'] as $row) : ?>
          <a href="<?php echo URLROOT; ?>/inscricoes/gerenciarPresencas/<?php echo $row->id?>" class="pull-left">
              Gerenciar Presenças - <?php echo $row->carga_horaria;?> Horas
            </a><br>
        <?php endforeach; ?>
      <?php else: ?>
        não tem presença
      <?php endif;?>
    </div>
    <!-- COL 1-->
  </div>
  <!-- ROW 2-->
</form>

<?php require APPROOT . '/views/inc/footer.php'; ?>