<?php require APPROOT . '/views/inc/header.php';?>


<div class="row align-items-center mb-3">
  <div class="col-md-12">
           

      <?php //var_dump($data); ?>

      

      <section class="h-100">
        <header class="container h-100">
          <div class="d-flex align-items-center justify-content-center h-100">
            <div class="d-flex flex-column">
              <h1 class="text align-self-center p-2">Registro de Frequência</h1>
              
              
              <div class="text align-self-center p-2">                 
                  <input 
                      class="form-control cpfmask"
                      type="text" 
                      name="cpf"
                      id="cpf"                                             
                      placeholder="CPF"                      
                  >
                  <div class="text-danger" id="cpf_err">
                      <?php echo $data['cpf_err']; ?>                     
                  </div>                   
              </div> 
              
              <h3 class="text align-self-center p-2" id="nome"></h3>
              
              <!-- LINHA PARA A MENSÁGEM DO JQUERY -->              
              <div class="d-flex flex-column">                  
                <div role="alert" id="messageBox" style="display:none"></div>
              </div>
              
              
            </div>
          </div>
        </header>
      </section>

  </div><!--col-md-12-->
</div><!--div class="row align-items-center mb-3--> 


<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
// FUNÇÃO TIMER APENAS JOGA UM VALOR PARA A FUNÇÃO E RETORNA DEPOIS DE 2 SEGUNDOS
let timer;
function delay(val){
  clearTimeout(timer);
  return new Promise((resolve)=>{
    timer = setTimeout(()=>{
      resolve(val);
    },2000)
  })
}


const searchCPF = document.getElementById("cpf");


/* 2 aqui ele faz a busca e se achar grava no bd */
async function Search(e){
  document.getElementById("cpf_err").innerText = '';
  document.getElementById('nome').innerText = '';
  // aguarda 2 segundos toda vez que é digitado uma tecla para evitar executar toda a função toda vez que for digitado uma tecla
  const cpf = await delay(e.target.value);
  // uma vez que o usuário demorou mais que dois segundos sem digitar nada ele passa o valor para validar o cpf
  cpfValido = validacaocpf(cpf);
  
  //se o cpf for inválido emito a mensagem de cpf infálido
  if(!cpfValido){  
    message('CPF Inválido!','alert alert-danger');     
    return;
  }

  //caso o cpf seja válido gravo a presença no bd
  gravar(<?php echo $data['abre_presenca_id'];?>);  
}


// 1 CHAMO A FUNÇÃO Search(e) para cada tecla pressionada
searchCPF.addEventListener('keyup',(e)=>{ 
  Search(e);
})


$(document).ready(function() {
  setFocus();
});

  function getUserId(cpf){
    $.ajax({
      url: `<?php echo URLROOT; ?>/users/getUsersCpf/${cpf}`,
      method:'POST',
      data:{
        cpf:cpf
      },    
      async: false,
      dataType: 'json'
    }).done(function (response){
      ret_val = response;
    }).fail(function (jqXHR, textStatus, errorThrown) {
      ret_val = null;
    });
   return ret_val;
}


function estaInscrito(user_id,inscricoes_id){
  
  $.ajax({
      url: `<?php echo URLROOT; ?>/inscricoes/estaInscrito`,
      method:'POST',
      data:{
        user_id:user_id,
        inscricoes_id:inscricoes_id
      },    
      async: false,
      dataType: 'json'
    }).done(function (response){
      ret_val = response;
    }).fail(function (jqXHR, textStatus, errorThrown) {
      ret_val = null;
    });
   return ret_val;
}


// 3 Gravo a presença no bd
function gravar(id){
  // pego o cpf
  let cpfInput = document.getElementById("cpf").value;     
  // pego o id do usuário pelo cpf
  let user = getUserId(`${cpfInput}`);     
  
  //se recuperar o nome do usuário eu transformo em uppercase e coloco no id nome
  if(user.name){
    document.getElementById('nome').innerText = user.name.toUpperCase();
  }     

  // verifico se o usuário está inscrito 
  let userInscrito = estaInscrito(`${user.id}`,<?php echo $data['inscricoes_id'];?>);
      
  let erro = null;

  if(!userInscrito){
    erro = 'CPF não inscrito no curso';
  }

  if(cpfInput == ''){
    erro = 'Por favor informe seu cpf';
  }

  if(user == false){
    erro = 'CPF não cadastrado!';
  }

  // se tem algum erro eu apresento a mensagem e interrompo o código
  if(erro != null){
    message(erro,'alert alert-danger');    
    return;
  }

  //caso esteja tudo ok gravo no banco
  $.ajax({
    url: `<?php echo URLROOT; ?>/presencas/add/${id}`,
    method:'POST',
    data:{
      abre_presenca_id:id,
      user_id:user.id
    },
  success: function(retorno_php){
    let responseObj = JSON.parse(retorno_php);    
    $("#messageBox")
    .removeClass()      
    .addClass(responseObj.classe)                           
    .html(responseObj.message) 
    .fadeIn(1000).fadeOut(6000);       
    }
  });
  clearData();
}

function setFocus(){
  document.getElementById("cpf").focus();
}

function clearData(){
  setTimeout(()=>{
    document.getElementById('cpf').value = '';
    document.getElementById('nome').innerText = '';
    setFocus();
  },3000)    
}

function message(msg,classmsg){
  $("#messageBox")
    .removeClass()      
    .addClass(classmsg)                           
    .html(msg) 
    .fadeIn(1000).fadeOut(6000);
}

  

</script>

