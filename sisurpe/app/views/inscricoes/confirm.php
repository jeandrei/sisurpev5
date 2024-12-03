<?php require APPROOT . '/views/inc/header.php';?>
<?php //debug($data);?>
<main>
  
    <h2 class="mt-2">Excluir Inscrição Definitivamente</h2>

    <form action="<?php echo URLROOT; ?>/inscricoes/delete/<?php echo $data['id'];?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <p>Você deseja realmente excluir a inscricao <strong><?php htmlout($data['inscricao']->nome_curso); ?></strong>?</p> 
        </div>

        <div class="form-group mt-3">                

            <a href="<?php echo URLROOT; ?>/inscricoes/arquivadas" class="btn btn-primary"><i class="fa-solid fa-ban"></i> Cancelar</a>            
        
            <button type="submit" name="delete" id="delete" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Excluir</button>
        </div>
        

    </form>

</main>
<?php require APPROOT . '/views/inc/footer.php'; ?>