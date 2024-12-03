<?php require APPROOT . '/views/inc/header.php'; ?>

<script>  
  function limpar(){        
        document.getElementById('cpf').value = ""; 
        document.getElementById('name').value = "";       
        document.getElementById('escolaId').value = "null";
        document.getElementById('maiorEscolaridade').value = "null";
        document.getElementById('tipoEnsinoMedio').value = "null";
        document.getElementById('posId').value = "null"; 
        document.getElementById('cpf').focus(); 
      }     
</script>

<?php flash('mensagem');?>


<h1><?php echo $data['title']; ?></h1>
<p><?php echo $data['description']; ?></p>

<?php
  $paginate = $data['paginate'];
  $result = $data['results'];  
?>


<form id="filtrar" action="<?php echo URLROOT; ?>/fbuscaservidores/index" method="get" enctype="multipart/form-data">
  <!-- LINHA 01 -->
  <div class="row">
    
    <!-- COLUNA 1 CPF-->
    <div class="col-lg-2">
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

      <!-- ESCOLA ID -->  
      <div class="col-lg-4">
            <label for="escolaId">
                Escola
            </label>
            <select class="form-control"
              name="escolaId" 
              id="escolaId" 
              class="form-control" 
              >
              <option value='null'>Todas</option>                   
              <?php foreach($data['escolas'] as $row) : ?>
                <option 
                    value="<?php htmlout($row->id); ?>"
                    <?php echo get('escolaId') == $row->id ? 'selected' : '';?>
                >
                <?php htmlout($row->nome); ?>
              </option>
              <?php endforeach; ?> 
          </select>
    </div>
    <!-- ESCOLA ID -->                
      
      
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
  </div>
  <!-- LINHA 01 -->

  <!-- LINHA 02 -->
  <div class="row">
    <!--maiorEscolaridade-->        
    <div class="col-md-4">    
        <div class="mb-3">   
            <label for="maiorEscolaridade">                
                Maior nível de escolaridade concluída: 
            </label>
            <select
                name="maiorEscolaridade"
                id="maiorEscolaridade"
                class="form-control <?php echo (!empty($data['maiorEscolaridade_err'])) ? 'is-invalid' : ''; ?>"
            >
                <option value="null">Todos</option>              
                
                <option 
                    value="nao_concluiu" 
                    <?php echo get('maiorEscolaridade') == "nao_concluiu" ? 'selected' : '';?>
                >
                    Não concluiu o ensino fundamental
                </option> 

                <option 
                    value="e_fundamental"
                    <?php echo get('maiorEscolaridade') == "e_fundamental" ? 'selected' : '';?>
                >
                    Ensino fundamental
                </option> 

                <option 
                    value="e_medio"
                    <?php echo get('maiorEscolaridade') == "e_medio" ? 'selected' : '';?>
                >
                    Ensino médio
                </option> 

                <option 
                    value="e_superior"
                    <?php echo get('maiorEscolaridade') == "e_superior" ? 'selected' : '';?>
                >
                    Ensino superior
                </option> 
                
            </select>            
        </div>
    </div>
    <!--maiorEscolaridade--> 


    <!--tipoEnsinoMedio-->        
    <div class="col-md-4">    
        <div class="mb-3">   
            <label for="turmaId">               
                Tipo de ensino médio cursado: 
            </label>
            <select
                name="tipoEnsinoMedio"
                id="tipoEnsinoMedio"
                class="form-control <?php echo (!empty($data['tipoEnsinoMedio_err'])) ? 'is-invalid' : ''; ?>"
            >
                <option value="null">Todos</option>              
                <option 
                    value="geral"
                    <?php echo get('tipoEnsinoMedio') == "geral" ? 'selected' : '';?>
                >
                    Formação geral
                </option>

                <option 
                    value="normal"
                    <?php echo get('tipoEnsinoMedio') == "normal" ? 'selected' : '';?>
                >
                    Modalidade normal (magistério)
                </option> 
                
                <option 
                    value="c_tecnico"
                    <?php echo get('tipoEnsinoMedio') == "c_tecnico" ? 'selected' : '';?>
                >
                    Curso técnico
                </option> 
                
                <option 
                    value="m_indigena"
                    <?php echo get('tipoEnsinoMedio') == "m_indigena" ? 'selected' : '';?>
                >
                    Magistério indígena - modalidade normal
                </option> 
            </select>           
        </div>
    </div>
    <!--tipoEnsinoMedio-->
    
    <!-- posId -->  
    <div class="col-lg-4">
        <label for="posId">
            Especialização
        </label>
        <select class="form-control"
          name="posId" 
          id="posId" 
          class="form-control" 
          >
          <option value='null'>Todas</option>                   
          <?php foreach($data['pos'] as $row) : ?>
            <option 
                value="<?php htmlout($row->posId); ?>"
                <?php echo get('posId') == $row->posId ? 'selected' : '';?>
            >
            <?php htmlout($row->pos); ?>
          </option>
          <?php endforeach; ?> 
      </select>
    </div>
    <!-- posId -->  

  </div>
  <!-- LINHA 02 -->
</form>

<br>

<!-- MONTAR A TABELA -->
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Nome</th>      
      <th scope="col">Escola</th>           
      <th scope="col">Maior Escolaridade</th> 
      <th scope="col">Tipo Ensino Médio</th>
      <th scope="col" ></th> 
    </tr>
  </thead>
  <tbody>
    <?php if($result) : ?>
        <?php foreach($result as $row) : ?> 
            <tr>   
                <td><?php echo $row['nome']; ?></td>
                <td><?php echo $row['escola']; ?></td>
                <td><?php echo getMaiorEscolaridade($row['maiorEscolaridade']);?></td>
                <td><?php echo getTipoEnsinoMedio($row['tipoEnsinoMedio']); ?></td>                   
                <!--BTN EDITAR-->            
                <td style="text-align:right;">
                    <a class="btn btn-primary" href="<?php echo URLROOT; ?>/fbuscaservidores/ver/<?php echo $row['userId'];?>" class="pull-left" target="_blank"><i class="fa-solid fa-eye"></i></a>                    
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
    echo '<p style="clear: left; padding-top: 10px;">Resultados: '.$paginate->total_results.'</p>';

    /*
     * Echo out the total number of pages
     */
    echo '<p>Total de Paginas: '.$paginate->total_pages.'</p>';

    echo '<p style="clear: left; padding-top: 10px; padding-bottom: 10px;">-----------------------------------</p>';
  
?>
<?php require APPROOT . '/views/inc/footer.php'; ?>