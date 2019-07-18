<?php

$this->title = 'Configuração';

?>

<div class="row">
    <div class="col-sm-12">


        <?php

        if (isset($error))
            echo '<div class="alert alert-danger">' . $error . '</div>';

        if (isset($success))
            echo '<div class="alert alert-success">' . $success . '</div>';

        ?>


        <p>
            <a class="btn btn-primary" href="<?= Yii::getAlias('@root') . '/admin/sca/configuration/novo'; ?>">Novo</a>
        </p>


        <div class="panel panel-custom panel-border">

            <div class="panel-heading">
                <h3 class="panel-title">Listagem</h3>
            </div>

            <div class="panel-body">


                <table id="datatable" class="table table-bordered table-hover datatable">
                    <thead>
                        <tr>
                            <th>Titulo</th>
                            <th width="100px">Galeria</th>
                            <th width="100px">Status</th>
                            <th width="100px">Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        if( isset($areas) and count($areas)>0 ){

                            foreach ( $areas as $line ) {
                                ?>
                                <tr>
                                    <td><?= $line['label'];   ?></td>
                                    <td><?= ( $line['gallery'] == 1 ) ? 'Sim' : 'Não' ; ?></td>
                                    <td>
                                        <?php if($line['status'] == 1){ ?>
                                            <a class="text-success desativa" href="<?= Yii::getAlias('@root').'/admin/sca/configuration/desativar/' . $line['url']; ?>"><i class="fa fa-check"></i></a>
                                        <?php }else{ ?>
                                            <a class="text-danger ativa" href="<?= Yii::getAlias('@root').'/admin/sca/configuration/ativar/' . $line['url']; ?>"><i class="fa fa-times"></i></a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a class="text-info" href="<?= Yii::getAlias('@root') . '/admin/sca/configuration/editar/'  . $line['url']; ?>"><i class="fa fa-pencil"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>


            </div>

        </div>


    </div> <!-- end col -->
</div> <!-- end row -->




<script type="text/javascript">

$(document).ready(function(){

    $('.ativa').on('click', function (e) {

        var href = $(this).attr('href');
        e.preventDefault();
        swal({
            title: "Realmente deseja Ativar?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-success',
            confirmButtonText: "Sim",
            cancelButtonText: 'Não',
            closeOnConfirm: false
        }, function () {

            window.location.href = href;
        });
    });



    $('.desativa').on('click', function (e) {

        var href = $(this).attr('href');
        e.preventDefault();
        swal({
            title: "Realmente deseja Desativar?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-success',
            confirmButtonText: "Sim",
            cancelButtonText: 'Não',
            closeOnConfirm: false
        }, function () {

            window.location.href = href;
        });
    });


});

</script>


