<!-- HEADER -->
<?php require APPROOT . '/views/inc/header.php';?>

<!-- FLASH MESSAGE -->
<!-- pessoa_message é o nome da menságem está lá no controller -->
<?php flash('message'); ?>
<!-- mb-3 marging bottom -->

<!-- FORMULÁRIO nonvalidate é para impedir a validação direta do navegador-->
<form id="editGrupo" action="<?php echo URLROOT; ?>/grupos/edit/<?php echo $data['id'];?>" method="POST" novalidate enctype="multipart/form-data">

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

    <!-- TABELA COM AS PERMIÇÕES -->
      <table class="table table-sm">
        <thead>
          <tr class="text-center">          
            <th scope="col">Tabela</th>
            <th scope="col">Ler - Todos <input type="checkbox" name="select-ler" id="select-ler"></th>
            <th scope="col">Editar - Todos <input type="checkbox" name="select-editar" id="select-editar"></th>
            <th scope="col">Criar - Todos <input type="checkbox" name="select-criar" id="select-criar"></th>
            <th scope="col">Apagar - Todos <input type="checkbox" name="select-apagar" id="select-apagar"></th>
          </tr>
        </thead>
        <tbody>
          <?php if(($data['gruoPermicoes'])) : ?>
            <?php
            foreach($data['gruoPermicoes'] as $permicao) :
            ?>
            <tr class="text-center">            
              <td><?php echo $permicao->tabela;?></td>
              <td>
                <input type="checkbox" class="check-ler" name="ler[]" id="<?php echo $permicao->id;?>" value="<?php echo $permicao->id;?>" 
                <?php echo ($permicao->ler === 's') ? "checked" : "";?>/>
              </td>
              <td>
                <input type="checkbox" class="check-editar" name="editar[]" id="<?php echo $permicao->id;?>" value="<?php echo $permicao->id;?>" 
                <?php echo ($permicao->editar === 's') ? "checked" : "";?>/>
              </td>
              <td>
                <input type="checkbox" class="check-criar" name="criar[]" id="<?php echo $permicao->id;?>" value="<?php echo $permicao->id;?>" 
                <?php echo ($permicao->criar === 's') ? "checked" : "";?>/>
              </td>
              <td>
                <input type="checkbox" class="check-apagar" name="apagar[]" id="<?php echo $permicao->id;?>" value="<?php echo $permicao->id;?>" 
                <?php echo ($permicao->apagar === 's') ? "checked" : "";?>/>
              </td>
            </tr>          
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center">
                Nenhuma permição configurada para este grupo
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>    
    <!-- TABELA COM AS PERMIÇÕES -->
   
    <!-- BOTÕES -->
    <div class="form-group mt-3 mb-3">
      <button type="submit" class="btn btn-success"><i class="fa fa-floppy-disk"></i> Atualizar</button>
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
	$('#editGrupo').validate({
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


/**
 * Função que verifica se todos os checkbox estão marcados
 * o checkbox tem que ter a classe check-classe
 * daí no array toCheck eu coloco só a parte da classe depois do traço
 * que dentro da função vai formar check-ler, check-editar ...
 * daí eu dou um foreach selecionando a classe e verifico se o total de checkbox
 * selecionado é o mesmo do total de chebckbox da classe se sim defino o check Todos como checked
 * 
 */
(function issAllChecked(){
  const toCheck = ['ler','editar','criar','apagar'];
  toCheck.forEach(function(check){
    const checkedLength = document.querySelectorAll(`.check-${check}:checked`).length;   
    const checkNum = document.querySelectorAll(`.check-${check}`).length;  
    if(checkedLength == checkNum){
      document.getElementById(`select-${check}`).checked = true;
    }
  })
})();


/**
 * função que vai marcar todos os checkboxes de uma classe
 * primeiro eu crio um array to Check ler,editar,criar
 * eu uso esses valores para montar a classe exemplo check-ler, check-editar
 * para cada item dentro do array toCheck eu primeiro seleciono o checkbox para marcar todos
 * de classe select- o que está no array exemplo select-ler
 * neste item selecionado adiciono um addeventlistener change executando uma função
 * crio uma variavel checkboxes pegando todos os elementos querySelectorAll de 
 * classe check- o que estiver no forEach nesse caso check-ler
 * para cada item selecionado eu mudo o checkbox para checked
 */
(function(){
  const toCheck = ['ler','editar','criar','apagar'];
  toCheck.forEach(function(check){
    document.getElementById(`select-${check}`).addEventListener('change', function(){
    checkboxes = document.querySelectorAll(`.check-${check}`);  
    checkboxes.forEach(function(checkbox){
    checkbox.checked = this.checked;
  }, this)
});
  })
})();

/*
selectler.addEventListener('change', function(){
  checkboxes = document.querySelectorAll('.check-ler');  
  checkboxes.forEach(function(checkbox){
    checkbox.checked = this.checked;
  }, this)
});

const selecteditar = document.getElementById('select-editar');
selecteditar.addEventListener('change', function(){
  checkboxes = document.querySelectorAll('.check-editar');
  checkboxes.forEach(function(checkbox){
    checkbox.checked = this.checked;
  }, this)
});

const selectcriar = document.getElementById('select-criar');
selectcriar.addEventListener('change', function(){
  checkboxes = document.querySelectorAll('.check-criar');
  checkboxes.forEach(function(checkbox){
    checkbox.checked = this.checked;
  }, this)
});

const selectapagar = document.getElementById('select-apagar');
selectapagar.addEventListener('change', function(){
  checkboxes = document.querySelectorAll('.check-apagar');
  checkboxes.forEach(function(checkbox){
    checkbox.checked = this.checked;
  }, this)
});*/


</script>




