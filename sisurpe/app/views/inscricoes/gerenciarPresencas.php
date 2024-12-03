<?php require APPROOT . '/views/inc/header.php'; ?>

<script>
  $(document ).ready(function() { 
    $('#select-all').click(function(event) {  
      let usersToCheck = [] ;      
      if(this.checked) {        
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true; 
            if (!isNaN(this.value[0])) {
              usersToCheck.push(this.value.split(",")[0]);            
            }            
        });
      } else {
        $(':checkbox').each(function() {
            this.checked = false;              
        });
      }     
      checkAll(usersToCheck);      
    }); 
  });
</script>

<?php flash('mensagem');?>

<div class="row d-flex flex-column">        
    
      <div id="messageBox" style="display:none"></div>
  
</div>
<h3>Gerenciamento de presentes do curso.</h3>
<hr>
<p><b>Curso:</b> <?php echo $data['curso']->nome_curso;?></p>
<hr>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Nome</th>
      <th scope="col">CPF</th>
      <th class="text-center" scope="col">Todos <input type="checkbox" name="select-all" id="select-all" 
      <?php echo ($data['todosPresentes']) ? "checked" : "";?>
      /></th>
    </tr>
  </thead>
  <tbody>  
  
    <?php $count = 0;?>
    <?php foreach($data['inscritos'] as $row) : ?>
      <?php $count++;?>
      <tr>
          <th scope="row"><?php echo $count;?></th>
          <td><?php echo $row->name; ?></td>
          <td><?php echo $row->cpf; ?></td>
          <td class="text-center">
            <input 
                  id="presenca" 
                  name="presenca" 
                  type="checkbox" 
                  class="form-check-input" 
                  value="<?php echo $row->user_id;?>,<?php echo $data['abrePresencaId'];?>"
                  onChange="atualizaPresenca([<?php echo $row->user_id;?>,<?php echo $data['abrePresencaId'];?>],this)"
                  <?php echo ($this->presencaModel->presente($data['abrePresencaId'],$row->user_id)) ? "checked" : "";?>

              >        
          </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>


<?php require APPROOT . '/views/inc/footer.php'; ?>

<script> 


  function atualizaPresenca(data,val){ 
    console.log(data);     
    console.log(val);
    $.ajax({  
        url: `<?php echo URLROOT; ?>/presencas/update`,                
        method:'POST',                 
        data:{
          user_id:data[0],
          abre_presenca_id:data[1],   
          presenca:val.checked                                       
        },         
        success: function(retorno_php){                    
          var responseObj = JSON.parse(retorno_php);   
          createNotification(responseObj['message'], responseObj['class']);
        }
    });//Fecha o ajax 
  }

  function checkAll(usersIds){           
    $.ajax({  
        url: `<?php echo URLROOT; ?>/presencas/checkAll`,                
        method:'POST',                 
        data:{
          usersIds,
          abre_presenca_id:<?php echo ($data['abrePresencaId']) ? $data['abrePresencaId'] : 'NULL' ;?>, 
          presenca:true                                      
        },         
        success: function(retorno_php){ 
          var responseObj = JSON.parse(retorno_php);   
          createNotification(responseObj['message'], responseObj['class']);
        }
    });//Fecha o ajax 
  }

</script>