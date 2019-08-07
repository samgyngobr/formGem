<?php

namespace app\modules\scarlet\controllers;

use Exception;
use Yii;
use yii\web\Controller;

use app\modules\scarlet\models\Scarlet;
use app\modules\scarlet\models\ScarletArea;



/**
 * Default controller for the `scarlet` module
 */
class ConfigurationController extends Controller
{





    public function init()
    {
        if( Yii::$app->user->isGuest )
            $this->redirect('@adminRootLogin');

        $this->enableCsrfValidation = false;
    }





    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        try
        {
            $areas = ( new Scarlet() )->listarAreas();


            if($_SESSION['error'] != '')
            {
                $error             = $_SESSION['error'];
                $_SESSION['error'] = '';
            }

            if($_SESSION['success'] != '')
            {
                $success             = $_SESSION['success'];
                $_SESSION['success'] = '';
            }

        }
        catch(Exception $e)
        {
            $error = $e->getMessage();
        }

        return $this->render( 'index', [
            'areas'   => $areas,
            'error'   => $error,
            'success' => $success,
        ]);
    }





    public function actionNovo()
    {
        try
        {
            $areas = ( new Scarlet() )->listarAreas( 'WHERE status=1 ' );

            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $post = Yii::$app->request->post();

                ( new Scarlet() )->newArea( $post );

                $_SESSION['success'] = 'Inserido com Sucesso!';
            }
        }
        catch(Exception $e)
        {
            $dados['error'] = $e->getMessage();
        }

        return $this->render( 'novo', [
            'post'  => $post,
            'areas' => $areas
        ] );
    }





    public function actionEditar()
    {
        try
        {
            $dados        = [];
            $dados['url'] = Yii::$app->request->get('url');   // parametro passado pela url referente ao registro do banco

            $obj           = new Scarlet();
            $obj->setArea($dados['url']);
            $dados['post'] = $obj->edtView();

            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $post = Yii::$app->request->post();

                $obj->edtArea( $post );
                $dados['success'] = 'Item Inserido Com Sucesso!';

                $dados['post'] = $obj->edtView();
            }
        }
        catch(Exception $e)
        {
            $dados['post']  = ( isset( $post ) ) ? $post : null;
            $dados['error'] = $e->getMessage();
        }

        return $this->render( 'editar', $dados );
    }





    public function actionAtivar()
    {
        try
        {
            ( new ScarletArea( Yii::$app->request->get('url') ) )->ativarArea();
        }
        catch(Exception $e)
        {
            $_SESSION['error'] = $e->getMessage();
        }

        return $this->redirect('@web/scarlet/configuration');
    }






    public function actionDesativar()
    {
        try
        {
            ( new ScarletArea( Yii::$app->request->get('url') ) )->desativarArea();
        }
        catch(Exception $e)
        {
            $_SESSION['error'] = $e->getMessage();
        }

        return $this->redirect('@web/scarlet/configuration');
    }





}
