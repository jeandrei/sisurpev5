<!-- HEADER -->
<?php require APPROOT . '/views/inc/header.php';?>


<!-- FLASH MESSAGE -->
<?php flash('message'); ?>

<!-- TÍTULO -->
<div class="row">
    <div class="col-12 text-center">
        <h3><?php echo $data['titulo']; ?></h3>
    </div>    
</div>


<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Área</th> 
      <th scope="col">Nível</th> 
      <th scope="col">Curso</th> 
      <th scope="col">Tipo Instituição</th> 
      <th scope="col">Instituição de Ensino</th> 
      <th scope="col"></th>     
    </tr>
  </thead>
  <tbody>
    <?php if($data['userCursosSup']) : ?>
      <?php $i=0;?>
      <?php foreach($data['userCursosSup'] as $row) :?>
        <?php $i++;?>
        <tr>
          <th scope="row"><?php echo $i;?></th>
          <td><?php echo $row['area'];?></td> 
          <td><?php echo $row['nivel'];?></td> 
          <td><?php echo $row['curso'];?></td> 
          <td><?php echo $row['tipoInstituicao'];?></td> 
          <td><?php echo $row['instituicaoEnsino'];?></td> 

          <td><a href="<?php echo URLROOT.'/fuserescolaanos/delete/'.$row->fuEscolaId;?>"><i class="fa-solid fa-trash" style="color: #f20707;"></i></a></td>     
        </tr>
      <?php endforeach; ?>    
    <?php else: ?>
      <tr class='text-center'>
          <td colspan='7'>
              Nenhuma escola adicionada!
          </td> 
      </tr>   
    <?php endif;?>
  </tbody>
</table>


<!-- FORMULÁRIO -->
<form id="frmUserCursoSuperior" action="<?php echo URLROOT.'/fusercursosuperiores/add'?>" method="POST" novalidate enctype="multipart/form-data">    
    
    <!-- grup de dados 1 -->
    <fieldset class="bg-light p-2">
        
        <!-- PRIMEIRA LINHA -->
        <div class="row mb-3">
            
            <!--areaId-->
            <div class="col-12"> 
                <label for="areaId">
                    <b class="obrigatorio">*</b> Área do curso: 
                </label> 
                <select
                    name="areaId"
                    id="areaId"
                    class="form-control <?php echo (!empty($data['areaId_err'])) ? 'is-invalid' : ''; ?>"
                >
                    <option value="null">Selecione</option>
                    <?php foreach($data['areasCurso'] as $row) : ?> 
                            <option value="<?php htmlout($row->areaId); ?>"
                            <?php echo $data['areaId'] == $row->areaId ? 'selected':'';?>
                            >
                                <?php htmlout($row->area);?>
                            </option>
                    <?php endforeach; ?>
                </select>
                <span class="text-danger">
                    <?php echo $data['areaId_err']; ?>
                </span>
            </div>
            <!--areaId-->

        </div>
        <!-- PRIMEIRA LINHA --> 

        <!-- SEGUNDA LINHA -->
        <div class="row mb-3">
            
            <!--nivelId-->
            <div class="col-12"> 
                <label for="nivelId">
                    <b class="obrigatorio">*</b> Nível: 
                </label> 
                <select
                    name="nivelId"
                    id="nivelId"
                    class="form-control <?php echo (!empty($data['nivelId_err'])) ? 'is-invalid' : ''; ?>"
                >
                    <option value="null">Selecione</option>
                    <?php foreach($data['nivelCurso'] as $row) : ?> 
                            <option value="<?php htmlout($row->nivelId); ?>"
                            <?php echo $data['nivelId'] == $row->nivelId ? 'selected':'';?>
                            >
                                <?php htmlout($row->nivel);?>
                            </option>
                    <?php endforeach; ?>
                </select>
                <span class="text-danger">
                    <?php echo $data['nivelId_err']; ?>
                </span>
            </div>
            <!--nivelId-->

        </div>
        <!-- SEGUNDA LINHA -->

        <!-- TERCEIRA LINHA -->
        <div class="row mb-3">
            
            <!--cursoId-->
            <div class="col-12"> 
                <label for="cursoId">
                    <b class="obrigatorio">*</b> Curso: 
                </label> 
                <select
                    name="cursoId"
                    id="cursoId"
                    class="form-control <?php echo (!empty($data['cursoId_err'])) ? 'is-invalid' : ''; ?>"
                >
                    <option value="null">Selecione</option>
                    <?php foreach($data['cursosSuperiores'] as $row) : ?> 
                            <option value="<?php htmlout($row->cursoId); ?>"
                            <?php echo $data['cursoId'] == $row->cursoId ? 'selected':'';?>
                            >
                                <?php htmlout($row->curso);?>
                            </option>
                    <?php endforeach; ?>
                </select>
                <span class="text-danger">
                    <?php echo $data['cursoId_err']; ?>
                </span>
            </div>
            <!--cursoId-->

        </div>
        <!-- TERCEIRA LINHA -->

        <!-- QUARTA LINHA -->
        <div class="row mb-3">
            
            <!--tipoInstituicao-->
            <div class="col-12"> 
                <label for="tipoInstituicao">
                    <b class="obrigatorio">*</b> Tipo da instiuição: 
                </label> 
                <select
                    name="tipoInstituicao"
                    id="tipoInstituicao"
                    class="form-control <?php echo (!empty($data['tipoInstituicao_err'])) ? 'is-invalid' : ''; ?>"
                >
                    <option value="null">Selecione</option>
                    <?php foreach($data['tiposInstituicoes'] as $row) : ?> 
                            <option value="<?php htmlout($row); ?>"
                            <?php echo $data['tipoInstituicao'] == $row ? 'selected':'';?>
                            >
                                <?php htmlout($row);?>
                            </option>
                    <?php endforeach; ?>
                </select>
                <span class="text-danger">
                    <?php echo $data['tipoInstituicao_err']; ?>
                </span>
            </div>
            <!--tipoInstituicao-->

        </div>
         <!-- QUARTA LINHA -->

         <!-- QUINTA LINHA -->
        <div class="row mb-3">
            
            <!--	instituicaoEnsino-->
            <div class="col-12"> 
                <label for="instituicaoEnsino">
                    <b class="obrigatorio">*</b> Nome da Instituição de Ensino: 
                </label> 
                <input 
                    type="text" 
                    name="instituicaoEnsino" 
                    id="instituicaoEnsino" 
                    class="form-control <?php echo (!empty($data['instituicaoEnsino_err'])) ? 'is-invalid' : ''; ?>"                             
                    value="<?php htmlout($data['instituicaoEnsino']);?>"
                    onkeydown="upperCaseF(this)"                    
                >
                <span class="text-danger">
                    <?php echo $data['instituicaoEnsino_err']; ?>
                </span>
            </div>
            <!--	instituicaoEnsino-->

        </div>
         <!-- QUINTA LINHA -->

    </fieldset>
    <!-- fim do grup de dados 1 -->      
    

    <!-- BOTÕES -->
    <div class="form-group mt-3 mb-3">           
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Salvar</button> 

        <a href="<?php echo URLROOT; ?>/estruturas" class="btn bg-warning"><i class="fa-solid fa-backward"></i> Voltar</a>
            
    </div>   
    <!-- BOTÕES -->

</form>

<!-- FOOTER -->
<?php require APPROOT . '/views/inc/footer.php'; ?>