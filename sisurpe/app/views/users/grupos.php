<?php require APPROOT . '/views/inc/header.php'; ?>

<?php flash('message');?>

<form id="addGrupo" action="<?php echo URLROOT; ?>/users/grupos/<?php echo $data['userId'];?>" method="post" enctype="multipart/form-data">  
  <div class="row">
    <div class="col-8">
        <div>        
          <label 
            for="grupoId"><b class="obrigatorio">*</b> Grupo: 
          </label>                        
            <select
                name="grupoId"
                id="grupoId"
                class="form-select <?php echo (!empty($data['grupoId_err'])) ? 'is-invalid' : ''; ?>"
            >
              <option value="null">Selecione o Grupo</option>

              <?php foreach($data['gruposCadastrados'] as $grupo) : ?>
              <option 
                  value="<?php htmlout($grupo->id); ?>"
                  <?php echo ($data['grupoId']) == $grupo->id ? 'selected' : '';?>
              >
              <?php htmlout($grupo->grupo); ?>
              </option>
            <?php endforeach; ?>  
            </select>
            <span class="text-danger">
                <?php echo $data['grupoId_err']; ?>
            </span>
        </div>
    </div>
    <div class="col-4">
        <div class="text-end">        
          <button type="submit" class="btn btn-primary pull-right">
              <i class="fa fa-plus"></i> Adicionar
          </button>
        </div>
    </div>
  </div>
</form>

<table class="table table-sm">
  <thead>
    <tr>      
      <th scope="col">Grupo</th>
      <th scope="col"></th>      
    </tr>
  </thead>
  <tbody>
    <?php if($data['gruposUsuario']) : ?>
      <?php foreach($data['gruposUsuario'] as $row) : ?>
      <tr>      
        <td><?php echo $row['grupo'];?></td>
        <td><a href="<?php echo URLROOT; ?>/users/deleteGrupo?grupoId=<?php echo $row['grupoId'];?>&userId=<?php echo $data['userId'];?>" class="fa-solid fa-trash btn btn-danger pull-left btn-sm m-0"></a></td>    
      </tr>  
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
      <td colspan="4" class="text-center">
        Nenhum grupo adicionado
      </td>
    </tr>
    <?php endif;?>
  </tbody>
</table>




<?php require APPROOT . '/views/inc/footer.php'; ?>