<?php require APPROOT . '/views/inc/header.php';?>

<main>
  
    <h2 class="mt-2">Excluir Modelo de Certificado</h2>

    <form action="<?php echo URLROOT.'/certificados/delete&arquivo='.$data['arquivo'];?>" method="post" enctype="multipart/form-data">
        
        <div class="form-group">
            <p>Você deseja realmente excluir o certificado: <strong><?php echo $data['arquivo'];?></strong></p>          
            
            <p>Só execute esta ação se você realmente sabe o que está fazendo.</p>
        </div>  
        
        <div class="form-group mt-3">
        
            <a class="btn btn-success" href="<?php echo URLROOT ?>/escolas">
            Cancelar
            </a>
        
            <button type="submit" name="delete" id="delete" class="btn btn-danger">Excluir</button>
        </div>

    </form>

</main>
<?php require APPROOT . '/views/inc/footer.php'; ?>