<?php require APPROOT . '/views/inc/header.php'; ?>
<?php flash('message');?>
   
  

    <main role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-12 col-lg-3">
              <img class="img-fluid" src="<?php echo URLROOT; ?>/imagens/brasao.png" alt="">
            </div>
            <div class="col-12 col-lg-9">
              <h1 class="display-5"><?php echo $data['title'];?></h1>
              <p><?php echo $data['description'];?></p>
            </div>
          </div>  
        </div>
      </div>

      <div class="container">
        <!-- Example row of columns -->
        <div class="row">
          <div class="col-md-6">
            <h2>Formação do servidor</h2>
            <p>Se você é profissional da Educação da Rede Municipal de Ensino de Penha, cadastre seus dados de formação. Esses dados são muito importantes uma vez que são informados no Educacenso e ajudam a melhorar os índices de sua unidade escolar. </p>
            <p><a class="btn btn-secondary" href="<?php echo isset($_SESSION[DB_NAME . '_user_id']) ? URLROOT . '/fuserescolaanos/userEscolaAno/' . $_SESSION[DB_NAME . '_user_id'] : URLROOT . '/users/login' ?>" role="button">Dados do Servidor &raquo;</a></p>
          </div>
          <div class="col-md-6">
            <h2>Inscrições</h2>
            <p>As inscrições para cursos de formação da Secretaria de Educação são disponibilizados aqui. Você pode realizar uma inscrição, cancelar e ao final do curso emitir seu certificado.   </p>
            <p><a class="btn btn-secondary" href="<?php echo URLROOT; ?>/inscricoes/index" role="button">Inscrições &raquo;</a></p>
          </div>
          <!-- <div class="col-md-4">
            <h2>Heading</h2>
            <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
            <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
          </div> -->
        </div>

        <hr>

      </div> <!-- /container -->

    </main>

    <footer class="container">
      &copy; <?php echo date("Y"); ?>
    </footer>

   

<?php require APPROOT . '/views/inc/footer.php'; ?>