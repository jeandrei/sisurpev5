<!-- HEADER -->
<?php require APPROOT . '/views/inc/header.php';?>


<!-- FLASH MESSAGE -->
<?php flash('message'); ?>

<div class="alert alert-warning" role="alert">
  <h4 class="alert-heading">Curso de pós graduação concluída.</h4>
  <p>Informe os cursos de pós graduação concluída e clique em <b>Salvar</b>.</p>
  <hr>
  <p class="mb-0"><p>Após adicionar todos os seus cursos, clique em <b>Avançar</b>.
</div>

<form id="frmUserPos" action="<?php echo URLROOT.'/fuserpos/index'?>" method="POST" novalidate enctype="multipart/form-data"> 

  <?php if($data['pos']) : ?>
    <?php foreach($data['pos'] as $row) : ?>      
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="pos[]" value="<?php echo $row->posId;?>" id="<?php echo $row->posId;?>"
        
        <?php 
          if($data['userPosId'] != 'null' && empty($data['pos_err']))          
          if (in_array($row->posId, $data['userPosId'])) {
            echo 'checked';
          }
        ?>        
        >
        <label class="form-check-label" for="<?php echo $row->posId;?>">
          <?php echo $row->pos; ?>
        </label>
      </div>
    <?php endforeach;?>
    <span class="text-danger">
        <?php echo $data['pos_err']; ?>
    </span>
  <?php else: ?>
    Erro ao carregar as opções de pós graduação!
  <?php endif;?>

  <!-- BOTÕES -->
  <div class="form-group mt-3 mb-3"> 

      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Salvar</button> 

      <a href="<?php echo URLROOT; ?>/fusercomplementacoes/index" class="btn bg-warning"><i class="fa-solid fa-backward"></i> Voltar</a>
      
      <?php if($data['userPosId'] != 'null'): ?>            
        <a href="<?php echo $data['avancarLink']?>" class="btn btn-success"><i class="fa fa-forward"></i> Avançar</a>
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