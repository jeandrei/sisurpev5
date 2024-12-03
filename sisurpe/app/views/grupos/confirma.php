
<?php require APPROOT . '/views/inc/header.php';?>

<main>
   
    <form action="<?php echo URLROOT; ?>/grupos/delete/<?php echo $data['grupo']->id;?>" method="post" enctype="multipart/form-data">
        
        <div class="form-group">
            <p>Você deseja realmente excluir o grupo <strong><?php echo $data['grupo']->grupo; ?>?</strong></p>
        </div>  
        
        <div class="form-group mt-3">
        
            <a class="btn btn-success" href="<?php echo URLROOT ?>/grupos">
            Cancelar
            </a>
        
            <button type="submit" name="delete" id="delete" class="btn btn-danger">Excluir</button>
        </div>

    </form>

</main>
<?php require APPROOT . '/views/inc/footer.php'; ?>