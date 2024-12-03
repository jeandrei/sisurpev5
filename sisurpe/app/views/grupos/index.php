<?php require APPROOT . '/views/inc/header.php'; ?>
<script>
/**
 * Funções para manipulação do formulário
 * limpar - limpa os campos com valores do formulário
 * focofield - seta o foco em um campo do formulário
 * 
 */
function limpar(){
        document.getElementById('grupo').value = "";                
        focofield("grupo");         
    }    
    
    window.onload = function(){
        focofield("grupo");
    }     

</script>
  
<?php flash('message');?>


<div class="row">
  <div class="col">
      <div class="text-end">
          <a href="<?php echo URLROOT; ?>/grupos/new" class="btn btn-primary pull-right">
              <i class="fa fa-pencil"></i> Adicionar
          </a>
      </div>
  </div>
</div>

<!-- FORMULÁRIO -->
<form id="filtrar" action="<?php echo URLROOT; ?>/grupos/index" method="get" enctype="multipart/form-data">
  <div class="row mt-2">
    
    <div class="col-md-3">
      <label for="grupo">
        Buscar por Grupo:
      </label>
      <input
        type="text"
        name="grupo"
        id="grupo"
        class="form-control"
        value="<?php echo get('grupo');?>"
      >
      <span class="invalid-feedback">
      </span>
    </div>

  </div> 
  
  <div class="col-md-6 align-self-end mt-2" style="padding-left:5;">           
    <input type="submit" class="btn btn-primary" value="Atualizar">                   
    <input type="button" class="btn btn-primary" value="Limpar" onClick="limpar()">     
  </div> 
  
</form>
<!-- FORMULÁRIO -->
 
<table class="table table-striped table-sm">
  <thead>
    <tr class="text-center">      
      <th class="col-sm-4">Grupo</th>                 
      <th class="col-sm-3">Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php if($data['results']) : ?>
      <?php foreach($data['results'] as $group) : ?>
        <tr class="text-center align-middle">
          <td><?php echo $group['grupo'];?></td>            
          <td> 
            <a href="<?php echo URLROOT; ?>/grupos/edit/<?php echo $group['id']; ?>" class="fa fa-edit btn btn-success pull-right btn-sm m-0"></a>
            <a href="<?php echo URLROOT; ?>/grupos/delete/<?php echo $group['id'];?>" class="fa-solid fa-trash btn btn-danger pull-left btn-sm m-0"></a>
          </td>  
        </tr>
      <?php endforeach; ?> 
    <?php else : ?>  
    <tr>
      <td colspan="4" class="text-center">
        Nenhum registro encontrado
      </td>
    </tr>
    <?php endif; ?>
  </tbody>
</table>

<!-- PAGINAÇÃO -->
<?php
    $pagination = $data['pagination'];     
    // no index a parte da paginação é só essa    
    echo '<p>'.$pagination->links_html.'</p>';   
    echo '<p style="clear: left; padding-top: 10px;">Total de Registros: '.$pagination->total_results.'</p>';   
    echo '<p>Total de Paginas: '.$pagination->total_pages.'</p>';
    echo '<p style="clear: left; padding-top: 10px; padding-bottom: 10px;">-----------------------------------</p>';
?>

<?php require APPROOT . '/views/inc/footer.php'; ?>