<!-- HEADER -->
<?php require APPROOT . '/views/inc/header.php';?>

<!-- FLASH MESSAGE -->
<?php flash('message'); ?>

<?php if($data['useroutrosCursosId'] != 'null' && empty($data['outros_err'])): ?>   
    <div class="alert alert-success mt-3" role="alert">
      Parabéns! Você realizou o cadastro com sucesso! clique <a href="<?php echo URLROOT; ?>" class="alert-link"><b>aqui</b></a> para retornar ao início.
    </div>          
<?php endif;?>

<div class="alert alert-warning" role="alert">
  <h4 class="alert-heading">Outros cursos.</h4>
  <p>Informe outros cursos realizados e clique em  <b>Salvar</b>.</p>
  <hr>
  <p class="mb-0"><p>Importante mínimo <b>80 horas</b></b>.
</div>

<form id="frmUserOutrosCursos" action="<?php echo URLROOT.'/fuseroutroscursos/index'?>" method="POST" novalidate enctype="multipart/form-data"> 

  <?php if($data['outrosCursos']) : ?>
    <?php foreach($data['outrosCursos'] as $row) : ?>      
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="outros[]" value="<?php echo $row->cursoId;?>" id="<?php echo $row->cursoId;?>"
        
        <?php 
          if($data['useroutrosCursosId'] != 'null' && empty($data['outros_err']))          
          if (in_array($row->cursoId, $data['useroutrosCursosId'])) {
            echo 'checked';
          }
        ?>        
        >
        <label class="form-check-label" for="<?php echo $row->cursoId;?>">
          <?php echo $row->curso; ?>
        </label>
      </div>
    <?php endforeach;?>
    <span class="text-danger">
        <?php echo $data['outros_err']; ?>
    </span>
  <?php else: ?>
    Erro ao carregar os outros cursos!
  <?php endif;?>

  <!-- BOTÕES -->
  <div class="form-group mt-3 mb-3"> 

      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Salvar</button> 

      <?php if($data['userFormacao'] == 'e_superior') : ?>
        <a href="<?php echo URLROOT; ?>/fuserpos/index" class="btn bg-warning"><i class="fa-solid fa-backward"></i> Voltar</a>
      <?php else: ?>
        <a href="<?php echo $data['voltarLink'] ?>" class="btn bg-warning"><i class="fa-solid fa-backward"></i> Voltar</a>    
      <?php endif;?>
            
  </div>   
  <!-- BOTÕES -->

</form>


<!-- FOOTER -->
<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>


let checkNao = document.querySelectorAll('.form-check-input')[0];

const allCheck = document.querySelectorAll('.form-check-input');

noValueSelected();
valueSelected();


allCheck.forEach((c) => {
  c.addEventListener('click',()=>{  
    valueSelected();
  });
});

function valueSelected(){
  $check = false;
  allCheck.forEach((c) => {
    if(c.id > 1 && c.checked){
      $check = true;
    }
  });
  if($check){
    document.querySelectorAll('.form-check-input')[0].disabled = true;
  } else {
    document.querySelectorAll('.form-check-input')[0].disabled = false;
  }
}

checkNao.addEventListener('click',()=>{  
  noValueSelected();  
});


function noValueSelected(){
  //checkValue retorna se o checkbox é true selecionado ou false não selecionado
  let checkValue = document.querySelectorAll('.form-check-input')[0].checked; 
  let checks = document.querySelectorAll('.form-check-input');    
    if(checkValue){     
      checks.forEach((c)=>{  
        if(c.id > 1){
          c.disabled = true;
        }        
    });    
  } else {
    checks.forEach((c)=>{  
      c.disabled = false;
    });  
  }  
}
</script>