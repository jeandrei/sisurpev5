<?php require APPROOT . '/views/inc/header.php';?>

<?php flash('mensagem');?>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-2">
                <h2>Criar uma conta</h2>
                <p>Por favor preencha os dados abaixo para se registrar</p> 
                <form action="<?php echo URLROOT; ?>/users/register" method="post" enctype="multipart/form-data" onsubmit="return validation(
                                                                                                                                               [noempty=['name']],
                                                                                                                                           [validaradio=['moradia']]                                                                                                                                               
                                                                                                                                               )">   
                    
                    
                    <!--Nome-->
                    <div class="form-group">   
                        <label 
                            for="name"><b class="obrigatorio">* </b>Nome Completo:
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
                            class="form-control form-control-lg cpfmask <?php echo (!empty($data['cpf_err'])) ? 'is-invalid' : ''; ?>"                             
                            placeholder="Informe seu CPF",
                            value="<?php echo $data['cpf'];?>"
                        >
                        <span class="invalid-feedback">
                            <?php echo $data['cpf_err']; ?>
                        </span>
                    </div>


                    <!--EMAIL-->
                    <div class="form-group">   
                        <label 
                            for="email"><b class="obrigatorio">* </b>Email: 
                        </label>                        
                        <input 
                            type="text" 
                            name="email" 
                            class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>"                             
                            placeholder="Informe seu email",
                            value="<?php echo $data['email'];?>"
                        >
                        <span class="invalid-feedback">
                            <?php echo $data['email_err']; ?>
                        </span>
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
                    <div class="form-row">
                       
                        <div class="d-grid gap-2">                      
                          <input type="submit" value="Registre-se" class="btn btn-success btn-block mt-2"> 
                        </div>

                        <div class="col">
                            <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-light btn-block">Já tem uma conta? Login</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>   
<?php require APPROOT . '/views/inc/footer.php'; ?>
