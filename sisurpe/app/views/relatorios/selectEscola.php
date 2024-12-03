<?php require APPROOT . '/views/inc/header.php';?>


<?php flash('message'); ?>


<!-- ADD NEW -->
<div class="row mb-3">
    <div class="col-md-12 text-center">
        <h1><?php echo $data['titulo']; ?></h1>
    </div>  
</div>



<!-- FORMULÁRIO -->
<form target="_blank" id="frmRelUniformeEscola" action="<?php echo URLROOT ."/relatorios"."/".$data['view'] ?>" method="GET" novalidate enctype="multipart/form-data">

 <!-- linha -->
  <div class="row mt-2">  
    <!-- coluna -->  
    <div class="col-8 mx-auto">

        <!-- ESCOLA -->
        <label for="escolaId">
            <b class="obrigatorio">*</b> Escola: 
        </label>
        <select
            name="escolaId"
            id="escolaId"
            class="form-control <?php echo (!empty($data['escolaId_err'])) ? 'is-invalid' : ''; ?>"
        >
            <option value="null">Selecione uma escola</option>
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
            <?php echo $data['erro']; ?>
        </span>
        <!-- ESCOLA --> 
    </div>
    <!-- coluna --> 
  </div>
 <!-- linha -->

 <div class="row mt-2"> 
    <div class="col-8 mx-auto">
      <button type="submit" class="btn btn-primary">Gerar Relatório</button> 
    </div>
</div>

 


</form>







<?php require APPROOT . '/views/inc/footer.php'; ?>