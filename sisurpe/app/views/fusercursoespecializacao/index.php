<?php require APPROOT . '/views/inc/header.php';?>

<!-- FLASH MESSAGE -->
<?php flash('message'); ?>

<div class="alert alert-warning" role="alert">
  <h4 class="alert-heading">  Pós-Graduações concluídas!</h4>
  <p>Informe seus cursos de especialização.</p>
  <p>Informe a área do curso e o ano de clonclusão.</p>
  <p>Clique <b>+ Adicionar</b> para cada curso realizado.</p>
  <hr>
  <p class="mb-0">Após adicionar todos os cursos clique em Avançar.</p>
</div>

<!-- FORMULÁRIO -->
<form id="frmUserEspecializacoes" action="<?php echo URLROOT.'/fusercursoespecializacoes/add';?>" method="POST" novalidate enctype="multipart/form-data">
    
    <!-- grup de dados 1 -->
    <fieldset class="bg-light p-2">
        
        <!-- PRIMEIRA LINHA -->
        <div class="row">
          <!-- ÁREA ID -->
          <div class="col-12">            
            <div class="form-group"> 
              <label for="areaId">
                  <b class="obrigatorio">*</b> Área do curso: 
              </label>
              <select
                  name="areaId"
                  id="areaId"
                  class="form-control <?php echo (!empty($data['areaId_err'])) ? 'is-invalid' : ''; ?>"
              >
                  <option value="null">Selecione a Área do Curso</option>
                  <?php foreach($data['areas'] as $row) : ?>
                  <option 
                      value="<?php htmlout($row->areaId); ?>"
                      <?php echo ($data['areaId']) == $row->areaId ? 'selected' : '';?>
                  >
                  <?php htmlout($row->area); ?>
                  </option>
                  <?php endforeach; ?>  
              </select>
              <span class="text-danger">
                  <?php echo $data['areaId_err']; ?>
              </span>
            </div>
          </div>
          <!-- ÁREA ID -->
        </div>
        <!-- PRIMEIRA LINHA --> 

        <!-- SEGUNDA LINHA -->
        <div class="row mb-3">
             <!--anoConclusao-->
             <div class="col-12"> 
                <label for="instituicaoEnsino">
                    <b class="obrigatorio">*</b> Ano de Conclusão: 
                </label> 
                <input 
                    type="number" 
                    name="anoConclusao" 
                    id="anoConclusao" 
                    class="form-control <?php echo (!empty($data['anoConclusao_err'])) ? 'is-invalid' : ''; ?>"                             
                    value="<?php htmlout($data['anoConclusao']);?>"
                >
                <span class="text-danger">
                    <?php echo $data['anoConclusao_err']; ?>
                </span>
            </div>
            <!--anoConclusao-->
        </div>
        <!-- SEGUNDA LINHA -->

    </fieldset>
    <!-- fim do grup de dados 1 -->    

    <!-- BOTÕES -->
    <div class="form-group mt-3 mb-3">           
        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Adicionar</button>      
        
        <a href="<?php echo $data['voltarLink']?>" class="btn bg-warning"><i class="fa-solid fa-backward"></i> Voltar</a>  

        <?php if($data['userEspCursos']) : ?>          
          <a href="<?php echo $data['avancarLink']?>" class="btn btn-success"><i class="fa fa-forward"></i> Avançar</a>     
        <?php endif;?>
    </div>   
    <!-- BOTÕES -->
    
</form>

<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Área do curso</th> 
      <th scope="col">Ano de conclusão</th> 
      <th scope="col"></th>     
    </tr>
  </thead>
  <tbody>
    <?php if($data['userEspCursos']) : ?>
      <?php $i=0;?>
      <?php foreach($data['userEspCursos'] as $row) :?>
        <?php $i++;?>
        <tr>
          <th scope="row"><?php echo $i;?></th>
          <td><?php echo $row->area;?></td> 
          <td><?php echo $row->anoConclusao;?></td> 
          <td><a href="<?php echo URLROOT.'/fusercursoespecializacoes/delete/'.$row->fupcId;?>"><i class="fa-solid fa-trash" style="color: #f20707;"></i></a></td>     
        </tr>
      <?php endforeach; ?>    
    <?php else: ?>
      <tr class='text-center'>
          <td colspan='4'>
              Nenhum curso de especialização adicionado!
          </td> 
      </tr>   
    <?php endif;?>
  </tbody>
</table>

<?php require APPROOT . '/views/inc/footer.php'; ?>