<?php require APPROOT . '/views/inc/header.php';?>

<!-- FLASH MESSAGE -->
<?php flash('message'); ?>

<!-- TÍTULO -->
<div class="row">
    <div class="col-12 mt-3 mb-3 text-center">
      <h3><?php echo $data['titulo'] . $data['ano']; ?>.</h3>
    </div>   
</div>

<div class="alert alert-warning" role="alert">
  <h4 class="alert-heading">Escolas em que o servidor leciona!</h4>
  <p>A primeira coisa que devemos fazer na atualização dos seus dados para o ano de <?php echo $data['ano']; ?> é adicionar as escolas em que você trabalha.</p>
  <p>Para isso clique em <b>+ Adicionar</b> para cada escola que você leciona em <?php echo $data['ano']; ?>.</p>
  <hr>
  <p class="mb-0">Após adicionar todas as escolas clique em Avançar.</p>
</div>

<!-- FORMULÁRIO -->
<form id="frmUserEscolaAno" action="<?php echo URLROOT.'/fuserescolaanos/add/'.$data['user']->id;?>" method="POST" novalidate enctype="multipart/form-data">
    
    <!-- grup de dados 1 -->
    <fieldset class="bg-light p-2">
        
        <!-- PRIMEIRA LINHA -->
        <div class="row">
          <!-- ESCOLA -->
          <div class="col-12">            
            <div class="form-group"> 
              <label for="escolaId">
                  <b class="obrigatorio">*</b> Escola: 
              </label>
              <select
                  name="escolaId"
                  id="escolaId"
                  class="form-control <?php echo (!empty($data['escolaId_err'])) ? 'is-invalid' : ''; ?>"
              >
                  <option value="null">Selecione a Escola</option>
                  <?php foreach($data['escolas'] as $row) : ?>
                  <option 
                      value="<?php htmlout($row->id); ?>"
                      <?php echo ($data['escolaId']) == $row->id ? 'selected' : '';?>
                  >
                  <?php htmlout($row->nome); ?>
                  </option>
                  <?php endforeach; ?>  
              </select>
              <span class="text-danger">
                  <?php echo $data['escolaId_err']; ?>
              </span>
            </div>
          </div>
          <!-- ESCOLA -->
        </div>
        <!-- PRIMEIRA LINHA --> 

    </fieldset>
    <!-- fim do grup de dados 1 -->    

    <!-- BOTÕES -->
    <div class="form-group mt-3 mb-3">           
        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Adicionar</button>        
        <?php if($data['userEscolas']) : ?>
          <a href="<?php echo $data['avancarLink']?>" class="btn btn-success"><i class="fa fa-forward"></i> Avançar</a>     
        <?php endif;?>
    </div>   
    <!-- BOTÕES -->
    
</form>

<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Escola</th> 
      <th scope="col"></th>     
    </tr>
  </thead>
  <tbody>
    <?php if($data['userEscolas']) : ?>
      <?php $i=0;?>
      <?php foreach($data['userEscolas'] as $row) :?>
        <?php $i++;?>
        <tr>
          <th scope="row"><?php echo $i;?></th>
          <td><?php echo $row->escolaNome;?></td> 
          <td><a href="<?php echo URLROOT.'/fuserescolaanos/delete/'.$row->fuEscolaId;?>"><i class="fa-solid fa-trash" style="color: #f20707;"></i></a></td>     
        </tr>
      <?php endforeach; ?>    
    <?php else: ?>
      <tr class='text-center'>
          <td colspan='3'>
              Nenhuma escola adicionada!
          </td> 
      </tr>   
    <?php endif;?>
  </tbody>
</table>

<?php require APPROOT . '/views/inc/footer.php'; ?>