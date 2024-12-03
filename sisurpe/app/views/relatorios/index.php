<?php require APPROOT . '/views/inc/header.php';?>

<?php flash('message'); ?>


<!-- ADD NEW -->
<div class="row mb-3">
    <div class="col-md-12 text-center">
        <h1><?php echo $data['titulo']; ?></h1>
    </div>  
</div>

<ul class="list-group">
  <li class="list-group-item"><a href="<?php echo URLROOT; ?>/relatorios/selectEscola/uniformePorEscola">Relatório de uniforme por escola</a></li>
  <li class="list-group-item"><a href="<?php echo URLROOT; ?>/relatorios/selectEscola/transportePorEscola">Relatório de transporte por escola</a></li>
  <li class="list-group-item"><a href="<?php echo URLROOT; ?>/relatorios/selectEscola/rfespecializacao">Relatório funcionário especialização</a></li> 
  <li class="list-group-item"><a href="<?php echo URLROOT; ?>/relatorios/selectEscola/rfespecializacaocpf">Relatório funcionário especialização com CPF</a></li>
  <li class="list-group-item"><a href="<?php echo URLROOT; ?>/relatorios/selectEscola/fusersemrespostapos">Relatório de funcionários que responderam a formação mas não responderam a especialização</a></li> 
</ul>



<?php require APPROOT . '/views/inc/footer.php'; ?>