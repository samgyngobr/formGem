<?php

$this->title = $config['area']['label'];

?>

<div class="row">
    <div class="col-sm-12">

        <?php

        if (
            ( isset( $error ) and $error != '' ) or
            ( isset( $_SESSION['error'] ) AND !is_array( $_SESSION['error'] ) AND $_SESSION['error'] != '' ) or
            ( is_array( $_SESSION['error'] ) AND count( $_SESSION['error'] ) > 0 ) )
        {
            echo '<div class="alert alert-danger">';

            if( is_array( $_SESSION['error'] ) )
                echo implode( '<br>', $_SESSION['error'] );
            else
                echo $_SESSION['error'];

            unset( $_SESSION['error'] );

            echo '</div>';
        }

        if (
            ( isset( $success ) and $success != '' ) or
            ( isset( $_SESSION['success'] ) AND !is_array( $_SESSION['success'] ) AND $_SESSION['success'] != '' ) or
            ( is_array( $_SESSION['success'] ) AND count( $_SESSION['success'] ) > 0 ) )
        {
            echo '<div class="alert alert-success">';

            echo $success;

            if( is_array( $_SESSION['success'] ) )
                echo implode( '<br>', $_SESSION['success'] );
            else
                echo $_SESSION['success'];

            unset( $_SESSION['success'] );

            echo '</div>';
        }

        ?>

        <p>
            <a class="btn btn-primary" href="<?= Yii::getAlias('@root') . '/admin/sca/' . $area . '/novo' ; ?>">Novo</a>
        </p>


        <div class="panel panel-custom panel-border">

            <div class="panel-heading">
                <h3 class="panel-title">Listagem</h3>
            </div>

            <div class="panel-body">

                <?php echo Yii::$app->ScarletHelper->listGen( $config['data'], $config['fieldLabels'], $config['area'] ); ?>

            </div>

        </div>


    </div> <!-- end col -->
</div> <!-- end row -->

