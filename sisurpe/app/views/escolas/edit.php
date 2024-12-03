<?php require APPROOT . '/views/inc/header.php';?>

<div class="row">
    <div class="col-md-6 mx-auto">
    <?php flash('message');?>
    <a href="<?php echo URLROOT; ?>/escolas" class="btn btn-light mt-3"><i class="fa fa-backward"></i>Voltar</a>
        <div class="card card-body bg-ligth mt-5">
            <h2>Atualizar escola</h2>
            <p>Por favor informe os dados da escola</p>
            <form id="editescola" action="<?php echo URLROOT; ?>/escolas/edit/<?php echo $data['id'];?>" method="post">                
                
                
                <!--nome-->        
                <div class="form-group">  
                    <label 
                        for="nome"><b class="obrigatorio">*</b> Nome: 
                    </label>                        
                    <input 
                        type="text" 
                        name="nome" 
                        id="nome" 
                        class="form-control <?php echo (!empty($data['nome_err'])) ? 'is-invalid' : ''; ?>"                             
                        value="<?php echo htmlout($data['nome']);?>"
                    >
                    <span class="text-danger">
                        <?php echo $data['nome_err']; ?>
                    </span>
                </div>
                <!-- nome -->

                <!--logradouro-->        
                <div class="form-group">  
                    <label 
                        for="logradouro"><b class="obrigatorio">*</b> Logradouro: 
                    </label>                        
                    <input 
                        type="text" 
                        name="logradouro" 
                        id="logradouro" 
                        class="form-control <?php echo (!empty($data['logradouro_err'])) ? 'is-invalid' : ''; ?>"                             
                        value="<?php echo htmlout($data['logradouro']);?>"
                    >
                    <span class="text-danger">
                        <?php echo $data['logradouro_err']; ?>
                    </span>
                </div>
                <!-- logradouro -->

                <!--numero-->        
                <div class="form-group">  
                    <label 
                        for="numero"><b class="obrigatorio"></b> Número: 
                    </label>                        
                    <input 
                        type="number" 
                        name="numero" 
                        id="numero" 
                        class="form-control <?php echo (!empty($data['numero_err'])) ? 'is-invalid' : ''; ?>"                             
                        value="<?php echo htmlout($data['numero']);?>"
                    >
                    <span class="text-danger">
                        <?php echo $data['numero_err']; ?>
                    </span>
                </div>
                <!-- numero -->

                
                <div class="form-group">            
                    <label for="bairro_id">
                        <strong><b class="obrigatorio">*</b></strong> Bairro:
                    </label>                               
                    <select 
                        name="bairro_id" 
                        id="bairro_id" 
                        class="form-control"                                        
                    >
                            <option value="null">Selecione um Bairro</option>
                            <?php                                                 
                            foreach($data['bairros'] as $bairro) : ?> 
                                <option value="<?php echo $bairro->id; ?>"
                                            <?php if($data['bairro_id']){
                                            echo $data['bairro_id'] == $bairro->id ? 'selected':'';
                                            }
                                            ?>
                                >
                                    <?php echo $bairro->bairro;?>
                                </option>
                            <?php endforeach; ?>  
                    </select>
                    <span class="text-danger">
                        <?php echo $data['bairro_id_err']; ?>
                    </span>
                </div>

                <!-- ativo na fila -->
                <div class="row">                    
                   
                    <div class="form-group col-12">               
                        
                        <strong><b class="obrigatorio">*</b> Ativa?</strong>

                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="emAtividade" id="sim" value='1' <?php echo ($data['emAtividade']=='1') ? 'checked' : '';?>>
                        <label class="form-check-label" for="sim">
                            Sim
                        </label>
                        </div>
                        
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="emAtividade" id="nao" value='0'<?php echo ($data['emAtividade']=='0') ? 'checked' : '';?>>
                        <label class="form-check-label" for="nao">
                            Não
                        </label>
                        </div> 
                    <label for="emAtividade" class="error text-danger"><?php echo $data['emAtividade_err'];?></label>
                    </div><!-- col --> 

                </div><!-- row -->
                <!-- ativo -->                 

                
                
                 <!--BOTÕES-->
                 <div class="row">
                    <div class="col">                    
                        <input type="submit" value="Atualizar" class="btn btn-success btn-block">                        
                    </div>                    
                 </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php';?>

<script>  
 $(document).ready(function(){
        $('#editescola').validate({
            rules : {	
                nome : {
                    required : true,
                    minlength : 6,
                },		
                logradouro : {
                    required : true
                },		
                bairro_id : {
                    selectone: "null"
                }
                
            },

            messages : {
                nome : {
                    required : 'Por favor informe o nome do usuário.',
                    minlength : 'A senha deve ter, no mínimo, 6 caracteres.'
                },			
                logradouro : {
                    required : 'Por favor informe seu email.'
                },
                bairro_id : {
                    selectone: 'Por favor informe o bairro.'
                }
            }
        });
});
</script>

