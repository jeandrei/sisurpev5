<?php require APPROOT . '/views/inc/header.php'; ?>

<?php 
//var_dump($data);
?>

<?php flash('mensagem');?>

<hr>
<div class="p-3 text-center">
  <h2><?php echo $data['title'];?></h2>
</div>
<hr>

<div class="alert alert-warning" role="alert">
  Inscrição do usuário <b><?php echo $data['user']->name;?></b>!
</div>
<div class="row">
    <div class="col-lg-12">      
          <select 
          name="inscricao_id" 
          id="inscricao_id" 
          class="form-control"                                        
          >
            <option value="NULL">Selecione a inscrição</option>
            <?php     
          
            foreach($data['inscricoes'] as $row) : ?>
            <?php        

            switch ($row->periodo) {
              case 'M':
                  $periodo = 'Matutino';
                  break;
              case 'V':
                  $periodo = 'Vespertino';
                  break;
              case 'D':
                  $periodo = 'Dia todo';
                  break;
              default:
                  $periodo = 'Não informado';
            }
            
            $texto = substr($row->nome_curso,0,100) . ' | Data: ' . formatadata($row->data_inicio) . ' | Período: ' . $periodo;
            
            ?>            
              <option value="<?php echo $row->id; ?>"
                          <?php if(isset($_POST['inscricao_id'])){
                          echo $_POST['inscricao_id'] == $row->id ? 'selected':'';
                          }
                          ?>
              >            
                <?php echo $texto; ?>
              </option>
            <?php endforeach; ?>  
          </select>
      </div>
</div>

<div class="row mt-3">
  <div class="col-12 text-center">    
    <button id="btnInscrever" name="btnInscrever" type="button" class="btn btn-primary">Inscrever</button>                  
  </div>
</div>

  


<?php require APPROOT . '/views/inc/footer.php'; ?>


<script>
$(document ).ready(function() { 

  $('#btnInscrever').click(function() {
    inscreveUser();
  });    
    
});//Fecha document ready function



function inscreveUser(){
    let userId = <?php echo $data['user']->id;?>;
    let inscricaoId = $("#inscricao_id").val();    
    $.ajax({  
        url: `<?php echo URLROOT; ?>/inscricoes/registrarInscricao`,                
        method:'POST',                 
        data:{
          userId,
          inscricaoId                                     
        },         
        success: function(retorno_php){ 
            var responseObj = JSON.parse(retorno_php);                     
            createNotification(responseObj['message'], responseObj['class']);
        }
    });//Fecha o ajax    
}



</script>

