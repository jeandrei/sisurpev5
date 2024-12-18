<!-- HEADER -->
<?php require APPROOT . '/views/inc/header.php';?>

<?php flash('message'); ?>


<form id="editEscola" action="<?php echo URLROOT; ?>/escolas/edit/<?php echo $data['id'];?>" method="post">  

  <legend>Dados da escola</legend>
    
  <fieldset class="bg-light p-2"><!-- grupo de dados -->

    <!-- PRIMEIRA LINHA -->
    <div class="row mb-2">        
      <!--NOME-->
      <div class="col-12 col-md-6 col-lg-6">
        <div class="form-group">  
          <label 
            for="nome"><b class="obrigatorio">*</b> Nome: 
          </label>                        
          <input 
            type="text" 
            name="nome" 
            id="nome" 
            class="form-control <?php echo (!empty($data['nome_err'])) ? 'is-invalid' : ''; ?>"                             
            value="<?php echo htmlout($data['nome']);?>"
          >
          <span class="text-danger">
            <?php echo $data['nome_err']; ?>
          </span>
        </div>
      </div>      
      <!--NOME-->  
      
      <!--LOGRADOURO-->
      <div class="col-12 col-md-6 col-lg-6">
        <div class="form-group">  
          <label 
            for="logradouro"><b class="obrigatorio">*</b> Logradouro: 
          </label>                        
          <input 
              type="text" 
              name="logradouro" 
              id="logradouro" 
              class="form-control <?php echo (!empty($data['logradouro_err'])) ? 'is-invalid' : ''; ?>"                             
              value="<?php echo htmlout($data['logradouro']);?>"
          >
          <span class="text-danger">
            <?php echo $data['logradouro_err']; ?>
          </span>
        </div>
      </div> 
      <!--LOGRADOURO-->
      
    </div><!-- row -->
    <!-- PRIMEIRA LINHA -->

    <!-- SEGUNDA LINHA -->
    <div class="row mb-2">
      <!-- NÚMERO -->
      <div class="col-12 col-md-6 col-lg-6">
        <div class="form-group">  
          <label 
            for="numero"><b class="obrigatorio"></b> Número: 
          </label>                        
          <input 
            type="number" 
            name="numero" 
            id="numero" 
            class="form-control <?php echo (!empty($data['numero_err'])) ? 'is-invalid' : ''; ?>"                             
            value="<?php echo htmlout($data['numero']);?>"
          >
          <span class="text-danger">
            <?php echo $data['numero_err']; ?>
          </span>
        </div>
      </div> 
      <!-- NÚMERO -->

      <!-- BAIRRO -->
      <div class="col-12 col-md-6 col-lg-6">
        <div class="form-group">            
          <label for="bairro_id">
            <strong><b class="obrigatorio">*</b></strong> Bairro:
          </label>                               
          <select 
            name="bairro_id" 
            id="bairro_id" 
            class="form-control <?php echo (!empty($data['bairro_id_err'])) ? 'is-invalid' : ''; ?>"                                
          >
            <option value="null">Selecione um Bairro</option>
            <?php                                                 
            foreach($data['bairros'] as $bairro) : ?> 
              <option value="<?php echo $bairro->id; ?>"
                <?php echo $data['bairro_id'] == $bairro->id ? 'selected':''; ?>
              >
                <?php echo $bairro->bairro;?>
              </option>
            <?php endforeach; ?>  
          </select>
          <span class="text-danger">
            <?php echo $data['bairro_id_err']; ?>
          </span>
        </div>
      </div>
      <!-- BAIRRO -->
    </div>
    <!-- SEGUNDA LINHA -->

    <!-- TERCEIRA LINHA -->
    <div class="row mt-3 p-3 border border-warning">
      <!-- ATIVO -->
      <div class="col-12">
        <div class="form-group">  
          <strong><b class="obrigatorio">*</b> Ativa?</strong>
          <div class="form-check">
          <input class="form-check-input" type="radio" name="emAtividade" id="sim" value='1' <?php echo ($data['emAtividade']=='1') ? 'checked' : '';?>>
          <label class="form-check-label" for="sim">
              Sim
          </label>
          </div>
          
          <div class="form-check">
          <input class="form-check-input" type="radio" name="emAtividade" id="nao" value='0'<?php echo (!$data['emAtividade']) ? 'checked' : '';?>>
          <label class="form-check-label" for="nao">
              Não
          </label>
          </div> 
          <label for="emAtividade" class="error text-danger"><?php echo $data['emAtividade_err'];?></label>
        </div><!-- col -->
      </div>
      <!-- ATIVO -->
    </div>
    <!-- TERCEIRA LINHA -->

  </fieldset><!-- fim do grup de dados 1 -->

  <!-- BOTÕES -->
  <div class="form-group mt-3 mb-3">
      <button type="submit" class="btn btn-success"><i class="fa fa-floppy-disk"></i> Enviar</button>
      <a href="<?php echo URLROOT ?>/escolas">
          <button type="button" class="btn bg-warning"><i class="fa fa-backward"></i> Voltar</button>
      </a>
  </div>  
  <!-- BOTÕES -->   
    
</form>

<!-- FOOTER -->
<?php require APPROOT . '/views/inc/footer.php'; ?>
<script>  
 $(document).ready(function(){
  $('#editEscola').validate({
    rules : {	
      nome : {
        required : true,
        minlength : 6
      },		
      logradouro : {
        required : true,
        minlength : 6
      },
      bairro_id : {
        selectone: "null"                 
      },
      emAtividade : {
        required : true
      }
    },
    messages : {
      nome : {
        required : 'Campo obrigatório',
        minlength : 'O nome deve ter no mínimo 6 caracteres.'
      },			
      logradouro : {
        required : 'Campo obrigatório',                      
        minlength : 'O logradouro deve ter no mínimo 6 caracteres.'
      },
      bairro_id : {
        selectone : 'Selecione um bairro',                     
      },
      emAtividade : {
        required : 'Por favor informe se deseja manter a escola ativa.',
      }
    }
  });
});
</script>