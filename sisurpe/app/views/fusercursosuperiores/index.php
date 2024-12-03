<!-- HEADER -->
<?php require APPROOT . '/views/inc/header.php';?>

<!-- FLASH MESSAGE -->
<?php flash('message'); ?>

<div class="alert alert-warning" role="alert">
  <h4 class="alert-heading">Curso superior.</h4>
  <p>Abaixo são listados os cursos superiores já adicionados.</p>
  <p>Para adicionar um curso superior clique em <b>+ Adicionar</b>. 
  <hr>
  <p class="mb-0"><p>Após adicionar todos os seus cursos, clique em <b>Avançar</b>.
</div>

<!-- ADD NEW -->
<div class="row mb-3">
  <div class="col-md-12">
    <a href="<?php echo URLROOT; ?>/fusercursosuperiores/add" class="btn btn-primary float-end">
        <i class="fa fa-plus"></i> Adicionar
    </a>

    <a href="<?php echo URLROOT; ?>/fuserformacoes/index" class="btn bg-warning"><i class="fa-solid fa-backward"></i> Voltar</a>

    <?php if($data['userCursosSup']) : ?>
      <a href="<?php echo URLROOT; ?>/fusercomplementacoes/index" class="btn btn-success"><i class="fa fa-forward"></i> Avançar</a>
    <?php endif;?>
  </div>
</div>

<table class="table table-striped table-responsive-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Área</th> 
      <th scope="col">Nível</th> 
      <th scope="col">Curso</th> 
      <th scope="col">Tipo Instituição</th> 
      <th scope="col">Instituição de Ensino</th> 
      <th scope="col">Município</th> 

      <th scope="col"></th>     
    </tr>
  </thead>
  <tbody>
    <?php if($data['userCursosSup']) : ?>
      <?php $i=0;?>
      <?php foreach($data['userCursosSup'] as $row) :?>
        <?php $i++;?>
        <tr>
          <th scope="row"><?php echo $i;?></th>
          <td><?php echo $row['area'];?></td> 
          <td><?php echo $row['nivel'];?></td> 
          <td><?php echo $row['curso'];?></td> 
          <td><?php echo $row['tipoInstituicao'];?></td> 
          <td><?php echo $row['instituicaoEnsino'];?></td> 
          <td><?php echo $row['municipioInstituicao'];?></td>           

          <?php if($row['file']) : ?>                    
            <td><a href="<?php echo URLROOT; ?>/fusercursosuperiores/download/<?php echo $row['ucsId'];?>"><i class="fa-solid fa-paperclip"></i></a></td>
          <?php else: ?>
            <td></td>
          <?php endif; ?>
         
          <td><a href="<?php echo URLROOT.'/fusercursosuperiores/delete/'.$row['ucsId'];?>"><i class="fa-solid fa-trash" style="color: #f20707;"></i></a></td>     
        </tr>
      <?php endforeach; ?>    
    <?php else: ?>
      <tr class='text-center'>
          <td colspan='8'>
              Nenhum curso superior adicionado!
          </td> 
      </tr>   
    <?php endif;?>
  </tbody>
</table>

<script>  
  function showImageModal(ucsId){
    //const modalImage = document.querySelector('#modalImage');
    if(typeof ucsId != 'undefined'){
        $.ajax({ 
                url: '<?php echo URLROOT; ?>/Fusercursosuperiores/getImagenFormacao/'+ucsId,                
                method:'POST', 
                success: function(retorno_php){   
                  document.getElementById('modalImage').innerHTML = retorno_php;                
                }
        });//Fecha o ajax 
    }
  }
</script>

<!-- FOOTER -->
<?php require APPROOT . '/views/inc/footer.php'; ?>