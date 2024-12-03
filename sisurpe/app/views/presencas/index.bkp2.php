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

   // aguarda 2 segundos toda vez que é digitado uma tecla para evitar executar toda a função toda vez que for digitado uma tecla
   const cpf = await delay(e.target.value);
  // uma vez que o usuário demorou mais que dois segundos sem digitar nada ele passa o valor para validar o cpf
  cpfValido = validacaocpf(cpf);
  
  //se o cpf for inválido emito a mensagem de cpf infálido
  if(!cpfValido && cpf.length > 0){  
    message('CPF Inválido!','alert alert-danger');     
    return;
  }
    
}


// 1 CHAMO A FUNÇÃO Search(e) para cada tecla pressionada
searchCPF.addEventListener('keyup',(e)=>{ 
  clearMessage();
  Search(e);
})









function message(msg,classmsg){
  $("#messageBox")
    .removeClass()      
    .addClass(classmsg)                           
    .html(msg) 
    .fadeIn(1000);
}

function clearMessage(){
  $("#messageBox")
    .removeClass()
    .html('');
}

</script>

