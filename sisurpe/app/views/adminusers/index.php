<?php require APPROOT . '/views/inc/header.php'; ?>

<script>  
  function limpar(){
          document.getElementById('cpf').value = ""; 
          document.getElementById('name').value = "";  
          document.getElementById('cpf').focus(); 
      }  
    document.getElementById('nome_aluno').focus(); 
</script>

<?php flash('message');?>

<?php
  $paginate = $data['paginate'];
  $result = $data['results'];  
?>


<form id="filtrar" action="<?php echo URLROOT; ?>/adminusers/index" method="get" enctype="multipart/form-data">
  <div class="row">
    
   <!-- COLUNA 1 CPF-->
   <div class="col-lg-3">
        <label for="name">
            Buscar por CPF
        </label>
        <input 
            type="text" 
            name="cpf" 
            id="cpf" 
            maxlength="60"
            class="form-control cpfmask"
            value="<?php if(isset($_GET['cpf'])){htmlout($_GET['cpf']);} ?>"
            autofocus
            >
    </div>
    <!-- COLUNA 1 CPF-->

    <!-- COLUNA 2 name-->
    <div class="col-lg-4">
        <label for="name">
            Buscar por name
        </label>
        <input 
            type="text" 
            name="name" 
            id="name" 
            maxlength="60"
            class="form-control"
            value="<?php if(isset($_GET['name'])){htmlout($_GET['name']);} ?>"
            onkeydown="upperCaseF(this)"   
            >
    </div>
    <!-- COLUNA 2 name-->      
      
    <!-- LINHA PARA O BOTÃO ATUALIZAR -->
    <div class="row" style="margin-top:30px;">
        <div class="col" style="padding-left:0;">
            <div class="form-group mx-sm-3 mb-2">
                <input type="submit" class="btn btn-primary mb-2" value="Atualizar">  
                <input type="button" class="btn btn-primary mb-2" value="Limpar" onClick="limpar()">   
            </div>                                                
        </div>  
    </div> 
    <!-- FIM LINHA BOTÃO ATUALIZAR -->     
            

  <!--div class="row"-->
  </div>

</form>

<br>

<!-- MONTAR A TABELA -->
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Nome</th>      
      <th scope="col">Email</th>           
      <th scope="col">Criado em</th>
      <th scope="col">Ações</th> 
    </tr>
  </thead>
  <tbody>
    <?php if($result) : ?>
      <?php foreach($result as $row) : ?> 
          <tr>   
              <td><?php echo $row['name']; ?></td>
              <td><?php echo $row['email']; ?></td>
              <td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>                                
              <!--BTN EDITAR-->            
              <td style="text-align:right;">
                  <a class="btn btn-success" href="<?php echo URLROOT; ?>/users/edit/<?php echo $row['id'];?>" class="pull-left"> Editar</a>                          
              
                  <a class="btn bg-warning" href="<?php echo URLROOT; ?>/inscricoes/inscreverUsuario/<?php echo $row['id'];?>" class="pull-left"> Inscrever</a>                          
              </td>
          </tr>
      <?php endforeach; ?> 
    <?php else: ?>   
      <tr class='text-center'>
          <td colspan='5'>
              Nenhum resultado encontrado
          </td> 
      </tr>  
    <?php endif;?>
  </tbody>
</table>
<?php  

    /*
     * Echo out the UL with the page links
     */
    echo '<p>'.$paginate->links_html.'</p>';

    /*
     * Echo out the total number of results
     */
    echo '<p style="clear: left; padding-top: 10px;">Total de rows: '.$paginate->total_results.'</p>';

    /*
     * Echo out the total number of pages
     */
    echo '<p>Total de Paginas: '.$paginate->total_pages.'</p>';

    echo '<p style="clear: left; padding-top: 10px; padding-bottom: 10px;">-----------------------------------</p>';
  
?>
<?php require APPROOT . '/views/inc/footer.php'; ?>