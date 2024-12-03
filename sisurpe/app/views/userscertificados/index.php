<?php require APPROOT . '/views/inc/header.php'; ?>
  
  <?php flash('mensagem');?>

  <hr>
    <div class="p-3 text-center">
      <h2><?php echo $data['title'];?></h2>
    </div>
  <hr>    
  <?php if($data['certificados'] != 'null') : ?>
    <?php foreach($data['certificados'] as $certificado) : ?>
      <div class="row border-bottom">
        <div class="col">
          <a href="<?php echo URLROOT;?>/inscricoes/certificado/<?php echo $certificado['curso']->id;?>"><?php echo $certificado['curso']->nome_curso; ?></a>        
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="alert alert-warning" role="alert">
     Você não possui nenhum certificado!
    </div>    
  <?php endif; ?>
 
 
<?php require APPROOT . '/views/inc/footer.php'; ?>

