<!-- HEADER -->
<?php require APPROOT . '/views/inc/header.php';?>
<style>
       .faq .detail {
        display: none;
      }
      .faq.active .detail {
        display: block;
      }

   /* parte de style do botão */
   .faq-toggle {
        background-color: transparent;
        border: 0;
        border-radius: 50%;
        cursor: pointer;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        padding: 0;
        top: 30px;
        right: 30px;
        height: 30px;
        width: 30px;
      }

      .faq-toggle:focus {
        outline: 0;
      }

      .faq-toggle .fa-times {
        display: none;
      }

      .faq.active .faq-toggle .fa-times {
        color: #fff;
        display: block;
      }

      .faq.active .faq-toggle .fa-chevron-down {
        display: none;
      }

      .faq.active .faq-toggle {
        background-color: #9fa4a8;
      }
      /* parte do style do botão */
</style>

<!-- FLASH MESSAGE -->
<?php flash('message'); ?>

<!-- TÍTULO -->
<div class="row text-center">
    <h1><?php echo $data['titulo']; ?></h1>
</div>


<!-- FORMULÁRIO -->
<form id="frmColeta" action="<?php echo URLROOT; ?>/coletas/index" method="GET" novalidate enctype="multipart/form-data">

 <!-- linha -->
  <div class="row mt-2 col-md-8 mx-auto">  
    <!-- coluna -->  
    <div class="col-md-8">

        <!-- ESCOLA -->
        <label for="escolaId">
            <b class="obrigatorio">*</b> Escola: 
        </label>
        <select
            name="escolaId"
            id="escolaId"
            class="form-control <?php echo (!empty($data['escolaId_err'])) ? 'is-invalid' : ''; ?>"
        >
            <option value="null">Selecione a Escola</option>
            <?php foreach($data['escolas'] as $row) : ?>
            <option 
                value="<?php htmlout($row->escolaId); ?>"
                <?php echo ($data['escolaId']) == $row->escolaId ? 'selected' : '';?>
            >
            <?php htmlout($row->nome); ?>
            </option>
            <?php endforeach; ?>  
        </select>
        <span class="text-danger">
            <?php echo $data['escolaId_err']; ?>
        </span>
        <!-- ESCOLA -->

        <!-- TURMA -->
        <label for="turmaId">
            <b class="obrigatorio">*</b> Turma: 
        </label>
        <select
            name="turmaId"
            id="turmaId"
            class="form-control mb-5 <?php echo (!empty($data['turmaId_err'])) ? 'is-invalid' : ''; ?>"
        >
            <option value="null">Selecione a Escola</option>              
        </select>
        <span class="text-danger">
            <?php echo $data['turmaId_err']; ?>
        </span>
        <!-- TURMA -->


    </div>
    <!-- coluna --> 
    
    

     
      

  </div>
 <!-- linha -->


</form>

<div id='result'>

</div>




<!-- FOOTER -->
<?php require APPROOT . '/views/inc/footer.php'; ?>


<!-- SELECT DINÂMICO -->
<script>
    //preciso declarar toggles aqui pois se eu declrar no controler Coletas ele vai redeclarar toda vez que o uma turma for selecionada
    let toggles;
    $(document).ready(function(){

              
        if($("#escolaId").val() !== 'null'){
          selectTurma();
        }         
       
        //CARREGA AS TURMAS
        $('#escolaId').change(function(){
          selectTurma();                     
            $('#turmaId').load('<?php echo URLROOT; ?>/turmas/turmasEscola/'+$('#escolaId').val());
        });
        

        //CARREGA OS ALUNOS
        $('#turmaId').change(function(){                           
            $('#result').load('<?php echo URLROOT; ?>/coletas/coletaTurma/'+$('#turmaId').val());
        });
        
    });
   

   function selectTurma(){
       document.getElementById('turmaId')[0].innerHTML = '<option value="null">Selecione a Turma</option>';       
   }

   

   function atualiza(field,val,id){      
      let act = field.slice(0, field.indexOf('_'));
      $.ajax({  
          url: `<?php echo URLROOT; ?>/coletas/update`,                
          method:'POST',                 
          data:{
              id,                   
              val,
              act                                        
          },         
          success: function(retorno_php){                          
              let responseObj = JSON.parse(retorno_php);             
              createNotification(responseObj['message'], responseObj['class']);
              //console.log(retorno_php);
          }
      });//Fecha o ajax    
   }

   function checkAllFilled(id){
    const classCheck = document.querySelectorAll(`.class_${id}`);
    //console.log(classCheck);
    let filled = true;
    classCheck.forEach(function (obj) {
        if(obj.value === 'null'){            
            filled = false;
        }
        if(filled === true){ 
            let checkImg = document.querySelector(`.row_${id}`);
            checkImg.querySelector('.fa-square-check').style.display = 'block';  
        } else {
            let checkImg = document.querySelector(`.row_${id}`);
            checkImg.querySelector('.fa-square-check').style.display = 'none'; 
        }
    });
   }

</script>
<!-- SELECT DINÂMICO -->