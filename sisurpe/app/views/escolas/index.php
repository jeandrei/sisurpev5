<?php require APPROOT . '/views/inc/header.php'; ?>
 <div class="row align-items-center mb-3"> 
    <div class="col-md-10">
        <h1>Escolas</h1>
    </div>
    <div class="col-md-2">
        <a href="<?php echo URLROOT; ?>/escolas/new" class="btn btn-primary pull-right">
            <i class="fa fa-pencil"></i> Adicionar
        </a>
    </div>
 </div> 
 <?php flash('message');?>
<table class="table table-striped">
    <thead>
        <tr class="text-center">      
            <th class="col-sm-3">Nome</th>
            <th class="col-sm-3">Logradouro</th>
            <th class="col-sm-1">Número</th>
            <th class="col-sm-2">Bairro</th>
            <th class="col-sm-1">Em Atividade</th>
            <th class="col-sm-2">Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php if($data) : ?>
        <?php foreach($data['escolas'] as $escola) : ?>
            <tr class="text-center">
                <td><?php echo $escola['nome'];?></td>
                <td><?php echo $escola['logradouro'];?></td>
                <td><?php echo $escola['numero'];?></td>   
                <td><?php echo $escola['bairro'];?></td>  
                <td><?php echo $escola['emAtividade'];?></td>              
                <td> 
                    <a href="<?php echo URLROOT; ?>/escolas/edit/<?php echo $escola['id']; ?>" class="btn btn-success">
                        <i class="fas fa-edit"></i>
                    </a>      
                    <a href="<?php echo URLROOT; ?>/escolas/delete/<?php echo $escola['id'];?>" class="btn btn-danger">
                        <i class="far fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>  
    <?php else: ?> 
        <tr>
            <td colspan="6" class="text-center">
                Nenhuma escola cadastrada
            </td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<?php require APPROOT . '/views/inc/footer.php'; ?>