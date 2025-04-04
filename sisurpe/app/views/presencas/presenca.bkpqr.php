<?php require APPROOT . '/views/inc/header.php';?>

<style>

.container {
    width: 100%;
    max-width: 500px;
    margin: 5px;
}

.container h1 {
    color: #ffffff;
}

.section {
    background-color: #ffffff;
    padding: 50px 30px;
    border: 1.5px solid #b2b2b2;
    border-radius: 0.25em;
    box-shadow: 0 20px 25px rgba(0, 0, 0, 0.25);
}

#my-qr-reader {
    padding: 20px !important;
    border: 1.5px solid #b2b2b2 !important;
    border-radius: 8px;
}

#my-qr-reader img[alt="Info icon"] {
    display: none;
}

#my-qr-reader img[alt="Camera based scan"] {
    width: 100px !important;
    height: 100px !important;
}

button {
    padding: 10px 20px;
    border: 1px solid #b2b2b2;
    outline: none;
    border-radius: 0.25em;
    color: white;
    font-size: 15px;
    cursor: pointer;
    margin-top: 15px;
    margin-bottom: 10px;
    background-color: #008000ad;
    transition: 0.3s background-color;
}

button:hover {
    background-color: #008000;
}

#html5-qrcode-anchor-scan-type-change {
    text-decoration: none !important;
    color: #1d9bf0;
}

video {
    width: 100% !important;
    border: 1px solid #b2b2b2 !important;
    border-radius: 0.25em;
}

</style>

<div class="row align-items-center mb-3">
  <div class="col-md-12">
      

      <section class="h-100">
        <header class="container h-100">
          <div class="d-flex align-items-center justify-content-center h-100">
            <div class="d-flex flex-column">
              <h1 class="text align-self-center p-2">Registro de Frequência.</h1>
              <h3 class="text align-self-center p-2">CPF SOMENTE NÚMEROS.</h3>
              <p class="text align-self-center p-2">NÃO precisa apertar o enter.</p>
              
              
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

        <div>        
          <?php echo "<center><img src='" . $data['qrCode'] . "'></center>"?>
        </div>

      </section>

  </div><!--col-md-12-->
</div><!--div class="row align-items-center mb-3--> 
<hr>
<div class="container">
      <h1>Scan QR Codes</h1>
      <div class="section">
          <div id="my-qr-reader">
          </div>
      </div>
  </div>
  <script
      src="https://unpkg.com/html5-qrcode">
  </script>

  <script>
    function domReady(fn) {
    if (
        document.readyState === "complete" ||
        document.readyState === "interactive"
    ) {
        setTimeout(fn, 1000);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}

domReady(function () {

    // If found you qr code
    function onScanSuccess(decodeText, decodeResult) {
        alert("You Qr is : " + decodeText, decodeResult);
    }

    let htmlscanner = new Html5QrcodeScanner(
        "my-qr-reader",
        { fps: 10, qrbos: 250 }
    );
    htmlscanner.render(onScanSuccess);
});
  </script>


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




// da o foco no input cpf
$(document).ready(function() {
  setFocus();
});


//seleciono o campo cpf
const searchCPF = document.getElementById("cpf");


// 1 se não for o enter CHAMO A FUNÇÃO Search(e) para cada tecla pressionada
searchCPF.addEventListener('keyup',(e)=>{ 
  if(e.keyCode !== 13 || e.wich !== 13){
    clearMessage();
    Search(e);
    }  
})


/* 2 aqui ele faz a busca e se achar grava no bd */
async function Search(e){ document.getElementById("cpf_err").innerText = '';

  document.getElementById('nome').innerText = '';
  // aguarda 2 segundos toda vez que é digitado uma tecla para evitar executar toda a função toda vez que for digitado uma tecla
  const cpf = await delay(e.target.value);
  // uma vez que o usuário demorou mais que dois segundos sem digitar nada ele passa o valor para validar o cpf
  cpfValido = validacaocpf(cpf);

  //se o cpf for inválido emito a mensagem de cpf infálido
  if(cpf.length > 0){
    if(!cpfValido){  
      message('CPF Inválido!','alert alert-danger');     
      return;
    } 
  }

  //caso o cpf seja válido gravo a presença no bd
  gravar(<?php echo $data['abre_presenca_id'];?>); 
  
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



// verifica se o usuário está inscrito em um curso
// retorna true ou false
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


// retonra o id e o nome do usuário passando o cpf
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



// seta o foco no campo cpf
function setFocus(){
  document.getElementById("cpf").focus();
}


// limpa os dados impressos em tela como cpf e nome do usuário
function clearData(){
  setTimeout(()=>{
    document.getElementById('cpf').value = '';
    document.getElementById('nome').innerText = '';
    setFocus();
  },3000)    
}


// emite mensagem abaixo do campo cpf
function message(msg,classmsg){
  $("#messageBox")
    .removeClass()      
    .addClass(classmsg)                           
    .html(msg) 
    .fadeIn(1000);
}


// limpa mensagems abaixo do campo cpf
function clearMessage(){
  $("#messageBox")
    .removeClass()
    .html('');
}

</script>

