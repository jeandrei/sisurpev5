<!-- HEADER -->
<?php require APPROOT . '/views/inc/header.php';?>

<!-- FLASH MESSAGE -->
<!-- pessoa_message é o nome da menságem está lá no controller -->
<?php flash('message'); ?>
<!-- mb-3 marging bottom -->


<!-- FORMULÁRIO nonvalidate é para impedir a validação direta do navegador-->
<form id="addGrupo" action="<?php echo URLROOT; ?>/grupos/new" method="POST" novalidate enctype="multipart/form-data">

    <legend>Dados do grupo</legend>
    
    <fieldset class="bg-light p-2"><!-- grupo de dados -->
        <!-- PRIMEIRA LINHA -->
        <div class="row">
            
            <!--nome do grupo-->        
            <div class="col-md-8">    
                <div class="mb-3">   
                    <label 
                        for="grupo"><b class="obrigatorio">*</b> Grupo: 
                    </label>                        
                    <input 
                        type="text" 
                        name="grupo" 
                        id="grupo" 
                        class="form-control <?php echo (!empty($data['grupo_err'])) ? 'is-invalid' : ''; ?>"                             
                        value="<?php echo htmlout($data['grupo']);?>"
                        onkeydown="upperCaseF(this)" 
                    >
                    <span class="text-danger">
                        <?php echo $data['grupo_err']; ?>
                    </span>
                </div>
            </div><!-- col -->            
          
        </div><!-- row -->
    </fieldset><!-- fim do grup de dados 1 -->  
    
    <!-- BOTÕES -->
    <div class="form-group mt-3 mb-3">
      <button type="submit" class="btn btn-success"><i class="fa fa-floppy-disk"></i> Enviar</button>
      <a href="<?php echo URLROOT ?>/grupos">
          <button type="button" class="btn bg-warning"><i class="fa fa-backward"></i> Voltar</button>
      </a>
    </div> 
    <!-- BOTÕES -->
    
</form>

<!-- FOOTER -->
<?php require APPROOT . '/views/inc/footer.php'; ?>
<script> 
/* custommsg está no main.js  */
 $(document).ready(function(){
	$('#addGrupo').validate({
		rules : {			
			  grupo : {
				required : true,				
			}
		},
		messages : {			
			  grupo : {
				required : custommsg['required'],			
			}	
    }
  });
});
</script>




