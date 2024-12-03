<?php require APPROOT . '/views/inc/header.php';?>

<style>
    .escolaUsuario{
        display: none;
    }
</style>

<script>
    function checkIfColeta(obj){
        if(obj.options[obj.selectedIndex].text == 'coleta'){
            document.querySelector('.escolaUsuario').style.display = 'block';
        } else {
            document.querySelector('.escolaUsuario').style.display = 'none';
        }
    }
</script>


<?php flash('mensagem');?>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card card-body bg-light mt-2">
                <h2>Editar a conta</h2>
                <p>Por favor preencha os dados abaixo para se registrar</p> 
                <form action="<?php echo URLROOT; ?>/users/edit/<?php echo $data['user_id'];?>" method="post" enctype="multipart/form-data" onsubmit="return validation(
                                                                                                                                               [noempty=['name']],
                                                                                                                                           [validaradio=['moradia']]                                                                                                                                               
                                                                                                                                               )">   
                    
                    
                    <!--Nome-->
                    <div class="form-group">   
                        <label 
                            for="name"><b class="obrigatorio">* </b>Nome:
                        </label>                        
                        <input 
                            type="text" 
                            name="name" 
                            class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>"                             
                            placeholder="Informe seu nome",
                            value="<?php echo $data['name'];?>"
                        >
                        <span class="invalid-feedback">
                            <?php echo $data['name_err']; ?>
                        </span>
                    </div>


                     <!--CPF-->
                     <div class="form-group">   
                        <label 
                            for="cpf"><b class="obrigatorio">* </b>CPF:
                        </label>                        
                        <input 
                            type="text" 
                            name="cpf" 
                            class="form-control form-control-lg>"   
                            value="<?php echo $data['cpf'];?>"
                            disabled
                        >                        
                    </div>


                    <!--EMAIL-->
                    <div class="form-group">   
                        <label 
                            for="email"><b class="obrigatorio">* </b>Email: 
                        </label>                        
                        <input 
                            type="text" 
                            name="email"
                            class="form-control form-control-lg"
                            value="<?php echo $data['email'];?>"
                            disabled
                        >
                    </div>
                    
                    <!-- TIPO DO USUÁRIO -->          
                    <div class="form-group"> 
                        <label 
                            for="usertype"><b class="obrigatorio">* </b>Tipo: 
                        </label>
                        <select class="form-control form-control-lg"
                            name="usertype" 
                            id="usertype" 
                            class="form-control" 
                            onchange="checkIfColeta(this)"
                            >                   
                            <?php 
                            $tipos = array('admin','sec','user','coleta');                    
                            foreach($tipos as $tipo => $value) : ?> 
                                <option value="<?php echo $value; ?>" 
                                            <?php echo $value == $data['usertype'] ? 'selected':'';?>
                                >
                                    <?php echo $value;?>
                                </option>
                            <?php endforeach; ?>  
                        </select>
                    </div>
                    <!-- TIPO DO USUÁRIO --> 


                    <!-- row escola-->
                    <div class="container-fluid escolaUsuario">   
                        <div class="row">
                            <!-- col-8 -->
                            <div class="col-8">
                                <!-- ESCOLA -->
                                <div class="form-group">
                                    <select
                                        name="escolaId"
                                        id="escolaId" 
                                        class="form-control form-control-lg <?php echo (!empty($data['escolaId_err'])) ? 'is-invalid' : ''; ?>"
                                    >
                                        <option value="null">Selecione a Escola</option>
                                        <?php foreach($data['escolas'] as $row) : ?>
                                        <option 
                                            value="<?php htmlout($row->id); ?>"
                                            <?php echo (isset($data['escolaId']) && ($data['escolaId']) == $row->id) ? 'selected' : '';?>
                                        >
                                        <?php htmlout($row->nome); ?>
                                        </option>
                                        <?php endforeach; ?>  
                                    </select>
                                    <span class="text-danger">
                                        <?php echo $data['escolaId_err']; ?>
                                    </span>
                                </div>                                

                                <!-- ESCOLA -->
                            </div>
                            <!-- col-8 -->
                            <!-- col-4  -->
                            <div class="col-4">
                                <button class="btn btn-lg btn-primary" type="button" id="addEscola">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>
                                    <span>+ Escola</span>
                                </button>  
                            </div>                            
                            <!-- col-4  --> 
                        </div>
                    <!-- Onde é carregado as escolas do usuário -->
                    <table class="table table-striped" id="tabelaEscolasUsuario"></table>
                    </div>                       

                    <!--PASSWORD-->
                    <div class="form-group">   
                        <label 
                            for="password"><b class="obrigatorio">* </b>Senha: 
                        </label>                        
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Informe sua senha",
                            class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>"                             
                            value="<?php echo $data['password'];?>"
                        >
                        <span class="invalid-feedback">
                            <?php echo $data['password_err']; ?>
                        </span>
                    </div>

                     
                     <!--CONFIRM PASSWORD-->
                     <div class="form-group">   
                        <label 
                            for="confirm_password"><b class="obrigatorio">* </b>Confirma Senha: 
                        </label>                       
                        <input 
                            type="password" 
                            name="confirm_password" 
                            placeholder="Confirme sua senha",
                            class="form-control form-control-lg <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>"                             
                            value="<?php echo $data['confirm_password'];?>"
                        >
                        <span class="invalid-feedback">
                            <?php echo $data['confirm_password_err']; ?>
                        </span>
                    </div>  
                    
                    <!--BUTTONS-->
                    <div class="row">
                        <div class="col">                            
                            <input type="submit" value="Atualizar" class="btn btn-success btn-block">                        
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>   
<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>

$(document).ready(function(){

    let tipo = document.getElementById('usertype').value;    
    if(tipo == 'coleta'){        
        document.querySelector('.escolaUsuario').style.display = 'block';
        carregaUserEscolaColeta(<?php echo $data['user_id'];?>);
    }    
    

    $('#addEscola').click(function() {
        let escolaId = $('#escolaId').val();        
        let error = null;        
        if(escolaId == 'null'){
            error = 'Informe a Escola';  
        }
        if(error == null){
            gravaUserEscolaColeta(escolaId); 
        } else {
            createNotification(error, 'error');
        }
        
    });//Fecha o gravarTipo click
});

    function gravaUserEscolaColeta(escolaId){
        showLoading('#addEscola');
        $.ajax({  
            url: `<?php echo URLROOT; ?>/Userescolacoletas/add`,                
            method:'POST',                 
            data:{
                userId:<?php echo ($data['user_id']) ? $data['user_id'] : 'NULL' ;?>,                   
                escolaId:escolaId                                        
            },         
            success: function(retorno_php){ 
                var responseObj = JSON.parse(retorno_php);             
                createNotification(responseObj['message'], responseObj['class']);
                carregaUserEscolaColeta(<?php echo $data['user_id'];?>);
                noLoading('#addEscola','+ Escola');

            }
        });//Fecha o ajax
    }
    

    function carregaUserEscolaColeta(userId){
    if(typeof userId != 'undefined'){
        $.ajax({ 
                url: '<?php echo URLROOT; ?>/Userescolacoletas/getUserEscolaColeta/',                
                method:'POST', 
                data:{
                userId:userId                                       
            }, 
                success: function(retorno_php){   
                    document.getElementById('tabelaEscolasUsuario').innerHTML = retorno_php;
                }
        });//Fecha o ajax 
    }
    
}

function removeEscola(id){    
    const confirma = confirm('Tem certeza que deseja excluir a escola?');
        
        if(confirma){
            $.ajax({  
            url: `<?php echo URLROOT; ?>/Userescolacoletas/delete/${id}`,                
            method:'POST',
            success: function(retorno_php){                                
                var responseObj = JSON.parse(retorno_php);                
                createNotification(responseObj['message'], responseObj['class']);
                carregaUserEscolaColeta(<?php echo $data['user_id'];?>); 
                }        
            });//Fecha o ajax
        }
}

function showLoading(id){        
        btn = document.querySelector(id);                
        btn.querySelector('.spinner-border').style.display = 'inline-block';
        btn.lastElementChild.innerText = 'Aguarde...';
    }

    function noLoading(id,text){
        btn = document.querySelector(id);                
        btn.querySelector('.spinner-border').style.display = 'none';
        btn.lastElementChild.innerText = text;   
    }
</script>
