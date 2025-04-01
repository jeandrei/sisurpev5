<?php 
//$_SESSION[SE.'user_permit'] vem do controller users
//debug($_SESSION[SE.'_user_permit']); 
function getPermition($table,$action){
  if(isset($_SESSION[SE.'_user_permit'][$table]) && $_SESSION[SE.'_user_permit'][$table][$action] == 's'){
    return true;
  } else {
    return false;
  }
}
?>


<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo URLROOT; ?>"><?php echo SITENAME; ?></a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav flex-grow-1">

        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>">Início</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>/pages/about">Sobre</a>
        </li> 

        <?php  if((isLoggedIn())) : ?>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>/pages/leitorqr">Leitor QR</a>
        </li> 
        <?php endif;?>

        <?php  if((isLoggedIn())) : ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarConsulta" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Consulta
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarConsulta">
            <?php if(getPermition('users','ler')) : ?>
            <a class="dropdown-item" href="<?php echo URLROOT; ?>/fbuscaservidores">Busca Servidor</a>
            <?php endif; ?>  
            <!-- 
            <a class="dropdown-item" href="<?php echo URLROOT; ?>/buscaalunos">Busca Alunos</a>
            <a class="dropdown-item" href="<?php echo URLROOT; ?>/buscadadosescolars">Busca Dados Escolares</a>
            <a class="dropdown-item" href="<?php echo URLROOT; ?>/buscadadostransportes">Busca Dados Transporte</a>-->
          </div>
        </li> 
        
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarAdministracao" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Administração
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarAdministracao">
            <?php if(getPermition('users','ler')) : ?>
            <a class="dropdown-item" href="<?php echo URLROOT; ?>/adminusers/index">Usuários</a> 
            <?php endif; ?>  
            <?php if(getPermition('grupos','ler')) : ?>
            <a class="dropdown-item" href="<?php echo URLROOT; ?>/grupos">Grupos</a>  
            <?php endif; ?>      
            <?php if(getPermition('escolas','ler')) : ?>
            <a class="dropdown-item" href="<?php echo URLROOT; ?>/escolas/index">Unidades</a>
            <?php endif; ?> 
            <?php if(getPermition('inscricoes','ler')) : ?>
            <a class="dropdown-item" href="<?php echo URLROOT; ?>/certificados/index">Certificados</a>
            <?php endif; ?> 
          </div>
        </li>  
        
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>/relatorios/index">Relatórios</a>
        </li> 

        <?php if(getPermition('coleta','ler')) : ?>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarColeta" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Coletas
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarColeta">            
            <a class="dropdown-item" href="<?php echo URLROOT; ?>/coletas/index">Coletar Dados</a>            
            <a class="dropdown-item" href="<?php echo URLROOT; ?>/coletas/geradordebilhetes">Gerador de bilhetes</a>            
          </div>
        </li>  
        <?php endif; ?> 
        
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>/datausers/show">Alunos</a>
        </li>         
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>/inscricoes/index">Inscrições</a>
        </li>       
      </ul>
      <?php endif;?>


      <ul class="navbar-nav ml-auto">
        <?php if(isset($_SESSION[SE . '_user_id'])) : ?>

          <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarBemVindo" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Bem vindo <?php echo $_SESSION[SE . '_user_name']; ?>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarBemVindo">
                <a class="dropdown-item" href="<?php echo URLROOT; ?>/users/alterasenha">Alterar a Senha</a>
                <a class="dropdown-item" href="<?php echo URLROOT; ?>/fuserescolaanos/userEscolaAno/<?php echo $_SESSION[SE . '_user_id']; ?>">Dados do Servidor</a>                          
                <a class="dropdown-item" href="<?php echo URLROOT; ?>/userscertificados/index/<?php echo $_SESSION[SE . '_user_id']; ?>">Meus Certificados</a>
              </div>
          </li>  
          
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/logout">Sair</a>
          </li>
        <?php else : ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/register">Registre-se</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Entrar</a>
          </li>           
        <?php endif; ?>         
      </ul>
    </div><!-- navbarNav -->

  </div><!-- container-fluid -->
</nav>

<?php if(isset($data['titulo'])) : ?>
  <div class="container-fluid bg-secondary text-white p-1 text-center">   
    <?php echo $data['titulo'];?>
  </div>
<?php endif; ?> 