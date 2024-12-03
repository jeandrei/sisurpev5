<!-- HEADER -->
<?php require APPROOT . '/views/inc/header.php';?>

<!-- FLASH MESSAGE -->
<?php flash('message'); ?>

<div class="alert alert-warning" role="alert">
  <h4 class="alert-heading">Nível de escolaridade e tipo de ensino médio.</h4>
  <p>Em Maior nível de escolaridade concluído, como o próprio texto diz, você deve informar o maior nível <b>CONCLUÍDO</b>, logo se você está cursando o ensino superior, mas ainda não finalizou, você deve informar a opção <b>Ensino médio</b>.</p>
  <p>
    Em Tipo de ensino médio cursado, informe o tipo de formação do seu ensino médio.
  </p>  
  <hr>
  <p class="mb-0">Após informar os dados, clique em <b>Salvar</b> e depois em <b>Avançar</b>.</p>
</div>


<!-- FORMULÁRIO -->
<form id="frmUserFormacao" action="<?php echo URLROOT.'/fuserformacoes/add'?>" method="POST" novalidate enctype="multipart/form-data">    
    
    <!-- grup de dados 1 -->
    <fieldset class="bg-light p-2">
        
        <!-- PRIMEIRA LINHA -->
        <div class="row">
            
            <!--maiorEscolaridade-->        
            <div class="col-md-6">    
                <div class="mb-3">   
                    <label for="maiorEscolaridade">
                        <b class="obrigatorio">*</b>
                        Maior nível de escolaridade concluída: 
                    </label>
                    <select
                        name="maiorEscolaridade"
                        id="maiorEscolaridade"
                        class="form-control <?php echo (!empty($data['maiorEscolaridade_err'])) ? 'is-invalid' : ''; ?>"
                    >
                        <option value="null">Selecione</option>              
                        
                        <option 
                            value="nao_concluiu" 
                            <?php echo ($data['maiorEscolaridade']) == "nao_concluiu" ? 'selected' : '';?>
                        >
                            Não concluiu o ensino fundamental
                        </option> 

                        <option 
                            value="e_fundamental"
                            <?php echo ($data['maiorEscolaridade']) == "e_fundamental" ? 'selected' : '';?>
                        >
                            Ensino fundamental
                        </option> 

                        <option 
                            value="e_medio"
                            <?php echo ($data['maiorEscolaridade']) == "e_medio" ? 'selected' : '';?>
                        >
                            Ensino médio
                        </option> 

                        <option 
                            value="e_superior"
                            <?php echo ($data['maiorEscolaridade']) == "e_superior" ? 'selected' : '';?>
                        >
                            Ensino superior
                        </option> 
                        
                    </select>
                    <span class="text-danger">
                        <?php echo $data['maiorEscolaridade_err']; ?>
                    </span>
                </div>
            </div>
            <!--maiorEscolaridade--> 


            <!--tipoEnsinoMedio-->        
            <div class="col-md-6">    
                <div class="mb-3">   
                    <label for="turmaId">
                        <b class="obrigatorio">*</b>
                        Tipo de ensino médio cursado: 
                    </label>
                    <select
                        name="tipoEnsinoMedio"
                        id="tipoEnsinoMedio"
                        class="form-control <?php echo (!empty($data['tipoEnsinoMedio_err'])) ? 'is-invalid' : ''; ?>"
                    >
                        <option value="null">Selecione</option> 
                        <option 
                            value="nao_concluiu"
                            <?php echo ($data['tipoEnsinoMedio']) == "nao_concluiu" ? 'selected' : '';?>
                        >
                            Não concluiu o ensino médio
                        </option>

                        <option 
                            value="geral"
                            <?php echo ($data['tipoEnsinoMedio']) == "geral" ? 'selected' : '';?>
                        >
                            Formação geral
                        </option>

                        <option 
                            value="normal"
                            <?php echo ($data['tipoEnsinoMedio']) == "normal" ? 'selected' : '';?>
                        >
                            Modalidade normal (magistério)
                        </option> 
                        
                        <option 
                            value="c_tecnico"
                            <?php echo ($data['tipoEnsinoMedio']) == "c_tecnico" ? 'selected' : '';?>
                        >
                            Curso técnico
                        </option> 
                        
                        <option 
                            value="m_indigena"
                            <?php echo ($data['tipoEnsinoMedio']) == "m_indigena" ? 'selected' : '';?>
                        >
                            Magistério indígena - modalidade normal
                        </option> 
                    </select>
                    <span class="text-danger">
                        <?php echo $data['tipoEnsinoMedio_err']; ?>
                    </span>
                </div>
            </div>
            <!--tipoEnsinoMedio--> 


        </div>
        <!-- PRIMEIRA LINHA --> 

    </fieldset>
    <!-- fim do grup de dados 1 -->    

    <!-- BOTÕES -->
    <div class="form-group mt-3 mb-3">  
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Salvar</button> 
        <a href="<?php echo URLROOT; ?>/fuserescolaanos/userEscolaAno" class="btn bg-warning"><i class="fa-solid fa-backward"></i> Voltar</a>
        <?php if(isset($data['maiorEscolaridade']) && $data['maiorEscolaridade'] != 'n_definido' && $data['maiorEscolaridade'] != 'null' && $data['tipoEnsinoMedio'] != 'null') :?>
            <a href="<?php echo $data['avancarLink']?>" class="btn btn-success"><i class="fa fa-forward"></i> Avançar</a>
        <?php endif; ?>
    </div>   
    <!-- BOTÕES -->
    
</form>

<!-- FOOTER -->
<?php require APPROOT . '/views/inc/footer.php'; ?>