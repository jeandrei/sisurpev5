<?php require APPROOT . '/views/inc/header.php';?>

<!-- FLASH MESSAGE -->
<?php flash('message'); ?>

<div class="alert alert-warning" role="alert">
  <h4 class="alert-heading"> Formação/Complementação pedagógica!</h4>
  <p>Informe seus cursos de formação/complementação pedagógica.</p>
  <p>Para isso clique em <b>+ Adicionar</b> para cada curso realizado.</p>
  <hr>
  <p class="mb-0">Após adicionar todos os cursos clique em Avançar.</p>
</div>

<!-- FORMULÁRIO -->
<form id="frmUserComplementacao" action="<?php echo URLROOT.'/fusercomplementacoes/add';?>" method="POST" novalidate enctype="multipart/form-data">
    
    <!-- grup de dados 1 -->
    <fieldset class="bg-light p-2">
        
        <!-- PRIMEIRA LINHA -->
        <div class="row">
          <!-- FORMAÇÃO/COMPLEMENTACAO PEDAGÓGICA -->
          <div class="col-12">            
            <div class="form-group"> 
              <label for="cpId">
                  <b class="obrigatorio">*</b> Formação/Complementação pedagógica: 
              </label>
              <select
                  name="cpId"
                  id="cpId"
                  class="form-control <?php echo (!empty($data['cpId_err'])) ? 'is-invalid' : ''; ?>"
              >
                  <option value="null">Nenhuma</option>
                  <?php foreach($data['complementacoes'] as $row) : ?>
                  <option 
                      value="<?php htmlout($row->cpId); ?>"
                      <?php echo ($data['cpId']) == $row->cpId ? 'selected' : '';?>
                  >
                  <?php htmlout($row->complementacao); ?>
                  </option>
                  <?php endforeach; ?>  
              </select>
              <span class="text-danger">
                  <?php echo $data['cpId_err']; ?>
              </span>
            </div>
          </div>
          <!-- FORMAÇÃO/COMPLEMENTACAO PEDAGÓGICA -->
        </div>
        <!-- PRIMEIRA LINHA --> 

    </fieldset>
    <!-- fim do grup de dados 1 -->    

    <!-- BOTÕES -->
    <div class="form-group mt-3 mb-3">           
        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Adicionar</button>       
          <a href="<?php echo $data['voltarLink']?>" class="btn bg-warning"><i class="fa-solid fa-backward"></i> Voltar</a>
          <a href="<?php echo $data['avancarLink']?>" class="btn btn-success"><i class="fa fa-forward"></i> Avançar</a>         
    </div>   
    <!-- BOTÕES -->
    
</form>

<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Formação/Complementação</th> 
      <th scope="col"></th>     
    </tr>
  </thead>
  <tbody>
    <?php if($data['userComplementacoes']) : ?>
      <?php $i=0;?>
      <?php foreach($data['userComplementacoes'] as $row) :?>
        <?php $i++;?>
        <tr>
          <th scope="row"><?php echo $i;?></th>
          <td><?php echo $row->complementacao;?></td> 
          <td><a href="<?php echo URLROOT.'/fusercomplementacoes/delete/'.$row->fucpId;?>"><i class="fa-solid fa-trash" style="color: #f20707;"></i></a></td>     
        </tr>
      <?php endforeach; ?>    
    <?php else: ?>
      <tr class='text-center'>
          <td colspan='3'>
              Nenhuma formação/complementação adicionada!
          </td> 
      </tr>   
    <?php endif;?>
  </tbody>
</table>

<?php require APPROOT . '/views/inc/footer.php'; ?>