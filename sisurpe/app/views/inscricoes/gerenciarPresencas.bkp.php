<?php require APPROOT . '/views/inc/header.php'; ?>

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
      <th class="text-center" scope="col">Presença</th>
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
                  value="<?php echo $row->user_id;?>"
                  onChange="atualizaPresenca(<?php echo $row->user_id;?>,this)"
                  <?php echo ($this->presencaModel->presente($data['abrePresencaId'],$row->user_id)) ? "checked" : "";?>

              >        
          </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>


<?php require APPROOT . '/views/inc/footer.php'; ?>

<script> 


  function atualizaPresenca(user_id,val){       
    $.ajax({  
        url: `<?php echo URLROOT; ?>/presencas/update`,                
        method:'POST',                 
        data:{
          abre_presenca_id:<?php echo ($data['abrePresencaId']) ? $data['abrePresencaId'] : 'NULL' ;?>,                   
          user_id:user_id,
          presenca:val.checked                                       
        },         
        success: function(retorno_php){                    
          var responseObj = JSON.parse(retorno_php);   
          createNotification(responseObj['message'], responseObj['class']);
        }
    });//Fecha o ajax 

   
}

</script>