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

<div class="alert alert-warning" role="alert">
  <h4 class="alert-heading">Localizar a área de curso.</h4>
  <p>Você pode visualizar o quadro de cursos de formação superior para facilitar a localização do seu curso superior.</p>
  <hr>
  <p class="mb-0"><p>Dificuldades em localizar seu curso? <b><a class="alert-link" target="_blank" href="<?php echo URLROOT; ?>/downloads/caderno_de_cursos.pdf">Clique aqui e visualize o quadro de cursos</a>.</b>
</div>

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
                    <option value="null">Selecione uma área</option>
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
        <!-- SEGUNDA LINHA -->

        <!-- TERCEIRA LINHA -->
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
        <!-- QUINTA LINHA -->

    </fieldset>
    <!-- fim do grup de dados 1 --> 

    <!-- grup de dados 2 -->
    <fieldset class="bg-light p-2 mt-3">
         <!-- SEXTA LINHA -->
        <div class="row mb-3">
            
            <!--	instituicaoEnsino-->
            <div class="col-12"> 
                <label for="instituicaoEnsino">
                    <b class="obrigatorio">*</b> Instituição de Ensino Nome: 
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
        <!-- SEXTA LINHA -->

        <!-- SETIMA LINHA -->
        <div class="row mb-3">
         <!--regiaoId-->
         <div class="col-12">
                <label for="regiaoId">
                    <b class="obrigatorio">*</b> Instituição de Ensino Região: 
                </label>
                <select
                    name="regiaoId"
                    id="regiaoId"
                    class="form-control <?php echo (!empty($data['regiaoId_err'])) ? 'is-invalid' : ''; ?>"
                >
                    <option value="null">Selecione a Região</option>
                    <?php foreach($data['regioes'] as $regiao) : ?>
                    <option 
                        value="<?php htmlout($regiao->id); ?>"
                        <?php echo ($data['regiaoId']) == $regiao->id ? 'selected' : '';?>
                    >
                    <?php htmlout($regiao->regiao); ?>
                    </option>
                    <?php endforeach; ?>  
                </select>
                <span class="text-danger">
                    <?php echo $data['regiaoId_err']; ?>
                </span>
            </div>
            <!--regiaoId-->
        </div>
         <!-- SETIMA LINHA -->

        <!-- OITAVA LINHA -->
        <div class="row mb-3">
            <!--estadoId-->
            <div class="col-12"> 
                <label for="estadoId">
                    <b class="obrigatorio">*</b> Instituição de Ensino Estado: 
                </label>
                <select
                    name="estadoId"
                    id="estadoId"
                    class="form-control <?php echo (!empty($data['estadoId_err'])) ? 'is-invalid' : ''; ?>"
                >
                    <option value="null">Selecione a Região</option>
                    <?php foreach($data['estados'] as $estado) : ?> 
                            <option value="<?php echo $estado->id; ?>"
                            <?php echo $data['estadoId'] == $estado->id ? 'selected':'';?>
                            >
                                <?php echo $estado->estado;?>
                            </option>
                    <?php endforeach; ?>
                </select>
                <span class="text-danger">
                    <?php echo $data['estadoId_err']; ?>
                </span>
            </div>
            <!--estadoId-->
        </div>
        <!-- OITAVA LINHA -->

        <!-- NONA LINHA -->
        <div class="row mb-3">
            <!--municipioId-->
            <div class="col-12"> 
                <label for="municipioId">
                    <b class="obrigatorio">*</b> Instituição de Ensino Município: 
                </label> 
                <select
                    name="municipioId"
                    id="municipioId"
                    class="form-control <?php echo (!empty($data['municipioId_err'])) ? 'is-invalid' : ''; ?>"
                >
                    <option value="null">Selecione a Região</option>
                    <?php foreach($data['municipios'] as $municipio) : ?> 
                            <option value="<?php echo $municipio->id; ?>"
                            <?php echo $data['municipioId'] == $municipio->id ? 'selected':'';?>
                            >
                                <?php echo $municipio->nomeMunicipio;?>
                            </option>
                    <?php endforeach; ?>
                </select>
                <span class="text-danger">
                    <?php echo $data['municipioId_err']; ?>
                </span>
            </div>
            <!--municipioId-->
        </div>
        <!-- NONA LINHA -->        
    </fieldset>
    <!-- fim do grup de dados 2 --> 

    <fieldset>
    <!-- DECIMA LINHA -->
       <!-- Adicionar arquivo-->
        <div class="row" style="margin:5px;">  
            <!-- Mensagem -->    
            <div class="alert alert-warning mt-2" role="alert">
                Arquivos permitidos com extenção <strong>jpg, png e pdf</strong>, e no máximo com <strong>20 MB</strong>. <b>Dica:</b> Se estiver utilizano o celular para bater uma foto do seu diploma, diminua a resolução da foto para não exceder o tamanho máximo permitido.
            </div>
            <!-- Input file -->
            <div class="input-group mb-3">
                <label class="input-group-text" for="file_post">Upload</label>
                <input 
                    type="file" 
                    class="form-control" 
                    id="file_post"
                    name="file_post" 
                    onchange="return fileValidation('file_post','file_post_err',['jpg', 'jpeg', 'png', 'pdf']);"                
                ><!-- A função fileValidation está no arquivo main.js-->                   
            </div><!--onchange="return fileValidation('file_post','file_post_err');" -->
            <!-- Span para caso tenha erros -->
            <span id="file_post_err" name="file_post_err" class="text-danger">
                <?php echo $data['file_post_err']; ?>
            </span>
        </div><!-- row -->            
        <!-- Fim Adicionar arquivo -->                 
    <!-- DECIMA LINHA -->                   
    </fieldset>   
         
    

    <!-- BOTÕES -->
    <div class="form-group mt-3 mb-3">           
        <button type="submit" id="btnSalvar" name="btnSalvar" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Salvar</button> 
        <a href="<?php echo URLROOT; ?>/fusercursosuperiores/index" class="btn bg-warning"><i class="fa-solid fa-backward"></i> Voltar</a>            
    </div>   
    <!-- BOTÕES -->

</form>

<!-- FOOTER -->
<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
    const btnSalvar = document.querySelector('#btnSalvar');

    btnSalvar.addEventListener("click",() => {
    loadingBtn('btnSalvar');  
    });
</script>

<!-- SELECT DINÂMICO -->
<script>
    $(document).ready(function(){

         /*     
         if($("#regiaoId").val() !== 'null'){            
            selectEstado();
        } 

        if($("#estadoId").val() !== 'null'){
             selectMunicipio(); 
        }*/
       
       //CARREGA OS ESTADOS
       $('#regiaoId').change(function(){          
          selectEstado();                      
          $('#estadoId').load('<?php echo URLROOT; ?>/estados/estadosRegiao/'+$('#regiaoId').val());
       });

       //CARREGA OS MUNICÍPIOS
       $('#estadoId').change(function(){ 
          selectMunicipio();
          $('#municipioId').load('<?php echo URLROOT; ?>/municipios/municipiosEstado/'+$('#estadoId').val());
       });

        //CARREGA OS CURSOS POR AREA
        $('#areaId').change(function(){           
          $('#cursoId').load('<?php echo URLROOT; ?>/fcursosuperiores/cursosArea/'+$('#areaId').val());
       });
       
   });
   

   function selectRegiao(){
       document.getElementById('regiaoId').innerHTML = '<option value="null">Selecione a Região</option>';
       document.getElementById('estadoId').innerHTML = '<option value="null">Selecione a Região</option>';
       document.getElementById('municipioId').innerHTML = '<option value="null">Selecione a Região</option>';
   }

   function selectEstado(){ 
        document.getElementById('estadoId').innerHTML = '<option value="null">Selecione o Estado</option>';
        document.getElementById('municipioId').innerHTML = '<option value="null">Selecione o Estado</option>';
   }

   function selectMunicipio(){        
       document.getElementById('municipioId').innerHTML = '<option value="null">Selecione o Municipio</option>';
   }   

</script>
<!-- SELECT DINÂMICO -->

<script>
    const compressImage = async (file, { quality = 1, type = file.type }) => {
        // Get as image data
        const imageBitmap = await createImageBitmap(file);

        // Draw to canvas
        const canvas = document.createElement('canvas');
        canvas.width = imageBitmap.width;
        canvas.height = imageBitmap.height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(imageBitmap, 0, 0);

        // Turn into Blob
        const blob = await new Promise((resolve) =>
            canvas.toBlob(resolve, type, quality)
        );

        // Turn Blob into File
        return new File([blob], file.name, {
            type: blob.type,
        });
    };

    // Get the selected file from the file input
    const input = document.querySelector('#file_post');
    input.addEventListener('change', async (e) => {
        // Get the files
        const { files } = e.target;

        // No files selected
        if (!files.length) return;

        // We'll store the files in this data transfer object
        const dataTransfer = new DataTransfer();

        // For every file in the files list
        for (const file of files) {
            // We don't have to compress files that aren't images
            if (!file.type.startsWith('image')) {
                // Ignore this file, but do add it to our result
                dataTransfer.items.add(file);
                continue;
            }

            // We compress the file by 50%
            const compressedFile = await compressImage(file, {
                quality: 0.5,
                type: 'image/jpeg',
            });

            // Save back the compressed file instead of the original file
            dataTransfer.items.add(compressedFile);
        }

        // Set value of the file input to our new files list        
        e.target.files = dataTransfer.files;
    });
</script>