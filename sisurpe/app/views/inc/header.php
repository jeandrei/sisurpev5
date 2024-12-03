<?php ini_set('default_charset', 'utf-8');?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo SITENAME; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   
   <!--Bootstrap CSS-->
   <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/bootstrap.min.css">
   
   <!--Font Awesome CDN-->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   
   <!--CSS MIDIFICAÇÕES SOBESCREVER Botstrap-->
   <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css"> 

  <!--jquery-->
  <script src="<?php echo URLROOT; ?>/js/jquery-3.1.1.js"></script> 

  <!--jquery validation-->
  <script src="<?php echo URLROOT; ?>/js/jquery.validate.js"></script> 

  <!--jquery mask-->
  <script src="<?php echo URLROOT; ?>/js/jquery.mask.js" data-autoinit="true"></script> 

  <!--Botstrap main-->
  <script src="<?php echo URLROOT; ?>/js/bootstrap.min.js"></script>
    
  <!--Javascript funções-->
  <script src="<?php echo URLROOT; ?>/js/main.js"></script>  
  
</head>
<body>
  
<!-- as mensagens são adicionadas pelo javascript nesse elemento toasts -->
<div id="toasts"></div>

<?php include APPROOT . '/views/inc/navbar.php'; ?>
  <div class="container">   
 
