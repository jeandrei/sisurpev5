<?php require APPROOT . '/views/inc/header.php';?>

<?php flash('message');?>

<div class="row align-items-center mb-3">
	<div class="col-md-12">
		<h2>Dados do Curso</h2>  	
			<form action="<?php echo URLROOT; ?>/inscricoes/add" method="post" enctype="multipart/form-data">   
					
				<input type="hidden" id="inscricoes_id" name="inscricoes_id" value="<?php echo $data['inscricoes_id'];?>">     

				<!--NOME-->
				<div class="form-row">
					<div class="form-group col-md-8">
						<label for="nome_aluno"><sup class="obrigatorio">*</sup> Nome do Curso:</label>  
						<input 
							class="form-control <?php echo (!empty($data['nome_curso_err'])) ? 'is-invalid' : ''; ?>"
							type="text" 
							name="nome_curso"
							id="nome_curso"
							value="<?php echo $data['nome_curso']; ?>"                       
							placeholder="Nome do curso"
						>
						<div class="invalid-feedback">
							<?php echo $data['nome_curso_err']; ?>
						</div>                   
					</div>
				</div> 					

				<!-- DESCRIÇÃO DO CURSO -->
				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="descricao"><sup class="obrigatorio">*</sup> Descrição do curso:</label>
						<textarea 
							class="form-control <?php echo (!empty($data['nome_curso_err'])) ? 'is-invalid' : ''; ?>" 
							id="descricao" 
							name="descricao"
							rows="3"><?php echo ($data['descricao']);?></textarea>
            <div class="text-danger">
              <?php echo $data['descricao_err'];?>
            </div>
					</div>
				</div>

        
				<div class="form-row"> 
				<!--PERÍODO-->
					<!-- INÍCIO -->
					<div class="form-group col-md-2">
						<label for="data_inicio"><sup class="obrigatorio">*</sup>Início</label>
						<input 
							class="form-control <?php echo (!empty($data['data_inicio_err'])) ? 'is-invalid' : ''; ?>"
							type="date"  
							id="data_inicio"
							name="data_inicio"
							value="<?php echo $data['data_inicio']; ?>"
						> 
						<div class="invalid-feedback">
							<?php echo $data['data_inicio_err']; ?>
						</div>  
					</div>          

          
					<!-- FIM -->
					<div class="form-group col-md-2">
						<label for="data_termino"><sup class="obrigatorio">*</sup>Fim</label>
						<input 
							class="form-control <?php echo (!empty($data['data_termino_err'])) ? 'is-invalid' : ''; ?>"
							type="date"  
							id="data_termino"
							name="data_termino"
							value="<?php echo $data['data_termino']; ?>"
						> 
						<div class="invalid-feedback">
							<?php echo $data['data_termino_err']; ?>
						</div>  
					</div>

					<!-- LOCAL -->
					<div class="form-group col-md-4">
						<label for="local"><sup class="obrigatorio">*</sup>Local</label>
						<input 
							class="form-control <?php echo (!empty($data['localEvento_err'])) ? 'is-invalid' : ''; ?>"
							type="text"  
							id="localEvento"
							name="localEvento"
							value="<?php echo $data['localEvento']; ?>"
						> 
						<div class="invalid-feedback">
							<?php echo $data['localEvento_err']; ?>
						</div>  
					</div>

					<!-- Hora início -->
					<div class="form-group col-md-2">
						<label for="horario"><sup class="obrigatorio">*</sup>Horário de Início</label>
						<input 
							class="form-control <?php echo (!empty($data['horario_err'])) ? 'is-invalid' : ''; ?>"
							type="time"
							id="horario"
							name="horario"
							value="<?php echo $data['horario']; ?>"
						> 
						<div class="invalid-feedback">
							<?php echo $data['horario_err']; ?>
						</div>  
					</div>

					<!-- Período -->
					<div class="form-group col-md-2">
						<label for="periodo"><sup class="obrigatorio">*</sup>Período</label>
						<select 
							name="periodo" 
							id="periodo" 
							class="form-control <?php echo (!empty($data['periodo_err'])) ? 'is-invalid' : ''; ?>"                                       
						>
							<option value="">Selecione período</option>
							<option value="M" <?php echo $data['periodo'] == 'M' ? 'selected':'';?>>Matutino</option>
							<option value="V" <?php echo $data['periodo'] == 'V' ? 'selected':'';?>>Vespertino</option> 
							<option value="D" <?php echo $data['periodo'] == 'D' ? 'selected':'';?>>Dia Todo</option>  
						</select>                      
						<div class="invalid-feedback">
							<?php echo $data['periodo_err']; ?>
						</div>  
					</div>    
				</div>
				<!-- row -->
				<!-- row -->
				<div class="row">
					<!-- FASE -->
					<div class="form-group col-md-4">  
						<label for="nome_aluno"><sup class="obrigatorio">*</sup> Fase do Curso:</label>                     
							<select 
								name="fase" 
								id="fase" 
								class="form-control <?php echo (!empty($data['fase_err'])) ? 'is-invalid' : ''; ?>"                                       
						>
								<option value="">Selecione a fase atual do curso</option>
								<option value="ABERTO" <?php echo $data['fase'] == 'ABERTO' ? 'selected':'';?>>Aberto</option>
								<option value="FECHADO" <?php echo $data['fase'] == 'FECHADO' ? 'selected':'';?>>Fechado</option>  
								<option value="CANCELADO" <?php echo $data['fase'] == 'CANCELADO' ? 'selected':'';?>>Cancelado</option>    
								<option value="ARQUIVADO" <?php echo $data['fase'] == 'ARQUIVADO' ? 'selected':'';?>>Arquivado</option>  
								<option value="CERTIFICADO" <?php echo $data['fase'] == 'CERTIFICADO' ? 'selected':'';?>>Certificado liberado</option>                                                                                                              													
							</select>                                           
							<span class="text-danger">
								<?php echo $data['fase_err'];?>
							</span>
					</div>
					<!-- FASE -->
					<!-- MODELO DO CERTIFICADO -->
					<div class="form-group col-md-4">  
						<label for="nome_aluno"><sup class="obrigatorio">*</sup> Modelo do Certificado:</label>  
						<input 
							class="form-control <?php echo (!empty($data['certificado_err'])) ? 'is-invalid' : ''; ?>"
							type="text" 
							name="certificado"
							id="certificado"
							value="<?php echo $data['certificado']; ?>"                       
							placeholder="Modelo do certificado"
							readonly
						>
						<div class="invalid-feedback">
							<?php echo $data['certificado_err']; ?>
						</div>   
					</div>
					<!-- MODELO DO CERTIFICADO -->
					<div class="row align-items-center">
						<div class="col mt-3">
							<button type="button" id="addTema" class="btn btn-primary" onClick="colar()">
								Colar
							</button> 
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelos-model">Modelos</button>
						</div>
					</div>
				</div>
				<!-- row -->  
					
				<!-- $data['editavel'] se for verdadeiro é que pode ser editado
				vem de controller\inscricoes -->      
				<?php if($data['editavel']) : ?>
						<!-- Button trigger modal -->
						<button type="button" id="addTema" class="btn btn-primary" data-toggle="modal" data-target="#addTemaModal" onClick="clearInput()">
						Adicionar Tema
						</button>           
				<?php else : ?>           
				
						<button type="submit" class="btn btn-primary">Gravar</button>         
			
				<?php endif; ?> 
			</form>
	</div><!--col-md-12-->
</div><!--div class="row align-items-center mb-3--> 






<!-- TEMAS -->
<div role="alert" id="msgAddTema"></div> 
<table class="table" id="tabelaTemas"></table>




<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="addTemaModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Adicionar Tema</h5>        
      </div>
      
      <form method="post" enctype="multipart/form-data"> 
        <!-- MODAL -->
        <div class="modal-body"> 
            
            <!--TEMA-->
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="nome_aluno"><sup class="obrigatorio">*</sup> Tema:</label>  
                    <input 
                        class="form-control <?php echo (!empty($data['tema_err'])) ? 'is-invalid' : ''; ?>"
                        type="text" 
                        name="tema"
                        id="tema"
                        value="<?php echo $data['tema']; ?>"                       
                        placeholder="Informe o tema"
                    >
                    <div class="invalid-feedback">
                        <?php echo $data['tema_err']; ?>
                    </div>                   
                </div>
            </div> 
            <!--TEMA-->

            <!--CARGA HORÁRIA-->
            <div class="form-row">
              <div class="form-group col-md-2">
                  <label for="carga_horaria"><sup class="obrigatorio">*</sup> Carga Horária:</label>  
                  <input 
                      class="form-control <?php echo (!empty($data['carga_horaria_tema_err'])) ? 'is-invalid' : ''; ?>"
                      type="text" 
                      name="carga_horaria_tema"
                      id="carga_horaria_tema"
                      value="<?php echo $data['carga_horaria_tema']; ?>"                       
                      placeholder="Carga Horária"
                  >
                  <div class="invalid-feedback">
                      <?php echo $data['carga_horaria_tema_err']; ?>
                  </div>                   
              </div>
            </div>                        
            <!--CARGA HORÁRIA-->


            <!--FORMADOR-->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nome_aluno"><sup class="obrigatorio">*</sup> Formador:</label>  
                    <input 
                        class="form-control <?php echo (!empty($data['formador_err'])) ? 'is-invalid' : ''; ?>"
                        type="text" 
                        name="formador"
                        id="formador"
                        value="<?php echo $data['formador']; ?>"                       
                        placeholder="Informe o formador"
                    >
                    <div class="invalid-feedback">
                        <?php echo $data['formador_err']; ?>
                    </div>                   
                </div>
            </div> 
            <!--NOME-->
        </div>
        <!-- FIM MODAL -->

        <!-- BOTÕES -->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary gravar" data-dismiss="modal" disabled>Gravar</button>
        </div>
        <!-- FIM BOTÕES -->

      </form>
      
    </div>
  </div>
  
</div>


<!-- MODAL MODELOS -->
<div class="modal fade bd-example-modal-lg" id="modelos-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
			<div class="modal-body">
        <?php if(isset($data['modelosCertificados'])) : ?>
						<div class="row">
            <?php foreach($data['modelosCertificados'] as $key => $modelo) : ?> 

							<?php $count = 0; ?>
							<!-- se for mod 2 crio a linha row do bootstrap e defino um contador como zero -->
							<?php if($key % 2 == 0 && $key > 1): ?>
									<div class="row">	
									<?php $count = 0; ?>								
							<?php endif;?>				
									<!-- mostro dois certificados e incremento o count -->
									<div class="col-6">
										<a href="<?php echo 'javascript:selectImage(\''.$modelo['arquivo'].'\')';?>">
										<img class="img-fluid" src="<?php echo $modelo['url'];?>" alt="">
										<span class="content-title"><?php echo $modelo['file'];?></span>
										</a>
										<?php $count++; ?>
									</div>
							<!-- se count é igual a dois então tenho que fechar a row do bootstrap -->
							<?php if($count == 2) : ?>
								</div>
							<?php endif;?>															
								
							
            <? endforeach; ?>
						</div>  
        <?php endif; ?>
			</div>
    </div>
  </div>
</div>
<!-- MODAL MODELOS -->

<!-- copia a url da imagem para localstorage -->
<script type="text/javascript">
    function selectImage(imgName){			
			let url = imgName; 
			localStorage.removeItem("imgUrl");
			localStorage.setItem("imgUrl",url); 
			colar();
			$('#modelos-model').modal('hide')
    }
</script>


<script>
		function colar(){
			const certificadoInput = document.querySelector('#certificado');
			const img = localStorage.getItem('imgUrl');
			certificado.value = img;
		}    
</script>

<script>
    
    $(document ).ready(function() { 
        
        carregaTemas();

        $('.gravar').click(function() {
            gravaTema(<?php echo $data['inscricoes_id']; ?>);   
        });//Fecha o .gravar click

    });//Fecha document ready function


    function carregaTemas(inscId){
        $.ajax({ 
            url: '<?php echo URLROOT; ?>/temas/index/<?php echo $data['inscricoes_id']; ?>',                
            method:'POST', 
            success: function(retorno_php){   
                document.getElementById('tabelaTemas').innerHTML = retorno_php;
            }
        });//Fecha o ajax    
   }//carregaTemas

   function gravaTema(id){
        //Pego os valores dos inputs            
        let tema=$("#tema").val();  
        let carga_horaria=$("#carga_horaria_tema").val(); 
        let formador=$("#formador").val();

        $.ajax({  
                url: `<?php echo URLROOT; ?>/temas/add/${id}`,                
                method:'POST',                 
                data:{                    
                    tema:tema,
                    carga_horaria:carga_horaria,
                    formador:formador                    
                },         
                success: function(retorno_php){                     
                    var responseObj = JSON.parse(retorno_php); 
                    document.querySelector('.gravar').disabled = true;
                    carregaTemas();                   
                    $("#msgAddTema")
                        .removeClass()  
                        .addClass(responseObj.classe) 
                        .html(responseObj.message) 
                        .fadeIn(2000).fadeOut(2000);
                        
                }
            });//Fecha o ajax
   }

   function remover(id){
        const confirma = confirm('Tem certeza que deseja excluir o tema?');
        
        if(confirma){
            $.ajax({  
            url: `<?php echo URLROOT; ?>/temas/delete/${id}`,                
            method:'POST',
            success: function(retorno_php){                     
                var responseObj = JSON.parse(retorno_php); 
                carregaTemas();                   
                $("#msgAddTema")
                    .removeClass()  
                    .addClass(responseObj.classe) 
                    .html(responseObj.message) 
                    .fadeIn(2000).fadeOut(2000);
                    
            }        
        });//Fecha o ajax

        carregaTemas();
        }
        
   }//remover
   

    document.getElementById('tema').addEventListener('keyup', validate);
    document.getElementById('carga_horaria_tema').addEventListener('keyup', validate);
    document.getElementById('formador').addEventListener('keyup', validate);

       

    function isEmpty(val){    
        switch (val){
        case '':
            return true;
            break;
        case null:
            return true;
            break;
        default:
            return false;
        }
    }


    function validate(){
  
        if(
            validateTema() &&
            validateCargaHoraria() &&
            validateFormador() 
          )
            {  
                document.querySelector('.gravar').disabled = false;    
            } else {
                document.querySelector('.gravar').disabled = true;
            }
    
    }

    function validateTema(){
        const tema = document.getElementById('tema');        
        if(!isEmpty(tema.value)){  
            //mínimo 3 caracteres             
            const re = /(.*[a-z]){3}/i;       
            if(!re.test(tema.value)){
                tema.classList.add('is-invalid');
                return false;
            } else {
                tema.classList.remove('is-invalid');
                return true;
            }
        }
    }

    function validateCargaHoraria(){        
        const ch = document.getElementById('carga_horaria_tema');         
        if(!isEmpty(ch.value)){            
            const re = /^[0-9]*$/;
            if(ch.value <= 0){
                ch.classList.add('is-invalid');                
                return false;
            } else if(!re.test(ch.value)){
                ch.classList.add('is-invalid');                
                return false;
            } else {
                ch.classList.remove('is-invalid');
                return true;
            }
        }
    }

    function validateFormador(){
        const formador = document.getElementById('formador');        
        if(!isEmpty(formador.value)){            
            const re = /^([a-zA-Zà-úÀ-Ú0-9_ ]|-|_|\s){2,100}$/;
            if(!re.test(formador.value)){
                formador.classList.add('is-invalid');
                return false;
            } else {
                formador.classList.remove('is-invalid');
                return true;
            }
        }
    }


    function clearInput(){
        document.getElementById('tema').value = '';
        document.getElementById('formador').value = '';
        document.getElementById('carga_horaria_tema').value = '';
        //foco no primeiro item do modal
        $("#addTemaModal").on("shown.bs.modal", function(){
            $(this).find("input").first().focus()
        })
    }   

   

</script>







<?php require APPROOT . '/views/inc/footer.php'; ?>

