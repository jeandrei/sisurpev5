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
    <div class="col-12">
        <h1><?php echo $data['titulo']; ?></h1>
    </div>    
</div>


<!-- FORMULÁRIO -->
<form id="frmGeraBilhete" action="<?php echo URLROOT; ?>/coletas/bilhetecoleta" method="POST" novalidate enctype="multipart/form-data">

 <!-- linha -->
  <div class="row mt-2 col-md-8 mx-auto">  
    <!-- coluna -->  
    <div class="col-md-12">

      <!-- ESCOLA -->
      <div class="form-group"> 
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
      </div>
      <!-- ESCOLA -->

      <!-- TURMA -->
      <div class="form-group"> 
        <label for="turmaId">
            Turma: 
        </label>
        <select
            name="turmaId"
            id="turmaId"
            class="form-control <?php echo (!empty($data['turmaId_err'])) ? 'is-invalid' : ''; ?>"
        >
            <option value="null">Selecione a Escola</option>
            <?php foreach($data['turmas'] as $row) : ?>
            <option 
                value="<?php htmlout($row->id); ?>"
                <?php echo ($data['turmaId']) == $row->id ? 'selected' : '';?>
            >
            <?php htmlout($row->descricao); ?>
            </option>
            <?php endforeach; ?>                
        </select>
        <span class="text-danger">
            <?php echo $data['turmaId_err']; ?>
        </span>
      </div>
      <!-- TURMA -->

      <!-- TEXTO DO BILHETE -->
      <div class="form-group">
        <label for="texto">Texto do bilhete:</label>
        <textarea 
        class="form-control mb-5" 
        id="texto"
        name="texto"
        rows="3"><?php htmlout($data['texto']);?></textarea>
      </div>
      <!-- TEXTO DO BILHETE -->

      <!-- BILHETE POR FOLHA -->
      <div class="form-group"> 
        <label for="turmaId">
            <b class="obrigatorio">*</b>
            Bilhetes pof folha: 
        </label>
        <select
            name="bilheteFolha"
            id="bilheteFolha"
            class="form-control <?php echo (!empty($data['bilheteFolha_err'])) ? 'is-invalid' : ''; ?>"
        >
            <option value="null">Selecione</option>              
            <option value="1">1</option> 
            <option value="2">2</option> 
            <option value="3">3</option> 
            <option value="4">4</option> 
            <option value="5">5</option> 
        </select>
        <span class="text-danger">
            <?php echo $data['bilheteFolha_err']; ?>
        </span>
      </div>
      <!-- BILHETE POR FOLHA -->


      <button type="submit" class="btn btn-primary">Gerar</button>
    </div>
    <!-- coluna -->     

  </div>
 <!-- linha -->

 
</form>



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
        

        //CARREGA O LINK DO RELATÓRIO
        $('#turmaId').change(function(){                           
            $('#result').load('<?php echo URLROOT; ?>/coletas/coletaTurma/'+$('#turmaId').val());
        });
        
    });
   

   function selectTurma(){
       document.getElementById('turmaId')[0].innerHTML = '<option value="null">Selecione a Turma</option>';       
   }
</script>
<!-- SELECT DINÂMICO -->