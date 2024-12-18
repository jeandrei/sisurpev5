<?php require APPROOT . '/views/inc/header.php';?>

<style>
  .escolaUsuario{
    display: none;
  }
</style>

<script>
  function checkIfColeta(obj){
    let coleta = <?php echo $data['coleta'];?>;    
    if(obj.options[obj.selectedIndex].text == 'interno' && coleta == true){
      document.querySelector('.escolaUsuario').style.display = 'block';
    } else {
      document.querySelector('.escolaUsuario').style.display = 'none';
    }
  }
</script>

<?php flash('message');?>

<form action="<?php echo URLROOT; ?>/users/edit/<?php echo $data['user_id'];?>" method="post" enctype="multipart/form-data" onsubmit="return validation([noempty=['name']],[validaradio=['moradia']])">   
  <legend>Dados do usuário</legend>
  <fieldset class="bg-light p-2"><!-- grupo de dados -->    
    <!-- PRIMEIRA LINHA -->
    <div class="row mb-2"> 
      <!--NOME-->
      <div class="col-12 col-md-8 col-lg-8">
        <div class="form-group">  
          <label 
            for="nome"><b class="obrigatorio">*</b> Nome: 
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
      </div>      
      <!--NOME-->
      <!--CPF-->
      <div class="col-12 col-md-4 col-lg-4">
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
      </div>      
      <!--CPF-->     
    </div>  
    <!-- PRIMEIRA LINHA -->
    <!-- SEGUNDA LINHA -->
    <div class="row mb-2"> 
      <!--EMAIL-->
      <div class="col-12 col-md-6 col-lg-6">
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
      </div>      
      <!--EMAIL-->

      <!--PASSWORD-->
      <div class="col-12 col-md-3 col-lg-3">
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
      </div>      
      <!--PASSWORD-->

      <!--CONFIRM PASSWORD-->
      <div class="col-12 col-md-3 col-lg-3">
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
      </div>      
      <!--CONFIRM PASSWORD-->    
    </div>  
    <!-- SEGUNDA LINHA -->

    <!-- TERCEIRA LINHA -->     
    <div class="row mb-2">
      <!--TIPO USUÁRIO-->
      <div class="col-12 col-md-2 col-lg-2">
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
            $tipos = array('externo','interno');                    
            foreach($tipos as $tipo => $value) : ?> 
              <option value="<?php echo $value; ?>" 
                <?php echo $value == $data['usertype'] ? 'selected':'';?>
              >
              <?php echo $value;?>
              </option>
            <?php endforeach; ?>  
          </select>
        </div>
      </div>      
      <!--TIPO USUÁRIO-->    
    </div> 
    <!-- TERCEIRA LINHA -->




    
    

              
        
     





  </fieldset>
        
  <fieldset class="bg-light p-2"><!-- grupo de dados --> 
    <div class="container-fluid border border-warning p-4 escolaUsuario"> 
      <div class="row mb-2">
        <!--ESCOLA USUÁRIO-->
        <div class="col-12 col-md-6 col-lg-6">
          <div class="form-group">
            <label 
              for="escolaId"><b class="obrigatorio"> </b>Escola: 
            </label>  
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
        </div>      
        <!--ESCOLA USUÁRIO-->        

        <div class="col-12 col-md-3 col-lg-3 mt-4 p-2">     
          <button class="btn btn btn-primary mt-1" type="button" id="addEscola">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>
            <span class="align-bottom">+ Escola</span>       
          </button> 
        </div> 
        
      </div>
      <!-- Onde é carregado as escolas do usuário -->
      <table class="table table-striped" id="tabelaEscolasUsuario"></table> 
    </div>   
  </fieldset> 

  <!-- BOTÕES -->
  <div class="form-group mt-3 mb-3">
    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-disk"></i> Enviar</button>
    <a href="<?php echo URLROOT ?>/adminusers">
      <button type="button" class="btn bg-warning"><i class="fa fa-backward"></i> Voltar</button>
    </a>    
    <?php if($data['usertype'] === 'interno'): ?>
    <a href="<?php echo URLROOT; ?>/users/grupos/<?php echo $data['user_id'];?>">
      <button type="button" class="btn bg-primary text-white"><i class="fa fa-user-group"></i> Grupos</button>
    </a> 
    <?php endif;?>     
  </div>   
  <!-- BOTÕES -->   

</form>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
$( document ).ready(function() {  
  let tipo = document.getElementById('usertype').value; 
  let coleta = <?php echo $data['coleta'];?>;   
  if(tipo == 'interno' && coleta == true){        
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

function carregaUserEscolaColeta(userId){  
  if(typeof userId != 'undefined'){    
    $.ajax({ 
      url: '<?php echo URLROOT; ?>/Userescolacoletas/getUserEscolaColeta',                
      method:'POST', 
      data:{
        userId                                      
      }, 
      success: function(retorno_php){        
        document.getElementById('tabelaEscolasUsuario').innerHTML = retorno_php;
      }
    });//Fecha o ajax 
  }    
}

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