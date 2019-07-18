<?php

namespace app\modules\formGem\controllers;

use Exception;
use Yii;
use yii\web\Controller;

use app\modules\formGem\models\FormGem;
use app\modules\formGem\models\FormGemArea;


/**
 * Default controller for the `formGem` module
 */
class ConfigurationController extends Controller
{

    public function init()
    {
    }


    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        try
        {
        }
        catch(Exception $e)
        {
            $_SESSION['error'][] = $e->getMessage();
        }

        return $this->render( 'index', [] );
    }



    public function actionNovo()
    {
        try
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $post = Yii::$app->request->post();
            }
        }
        catch(Exception $e)
        {
            $_SESSION['error'][] = $e->getMessage();
        }

        return $this->render( 'novo', [] );
    }



    public function actionEditar()
    {
        try
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $post = Yii::$app->request->post();
            }
        }
        catch(Exception $e)
        {
            $dados['post']       = ( isset( $post ) ) ? $post : null;
            $_SESSION['error'][] = $e->getMessage();
        }

        return $this->render( 'editar', [] );
    }



    public function actionAtivar()
    {
        try
        {
        }
        catch(Exception $e)
        {
            $_SESSION['error'][] = $e->getMessage();
        }
    }



    public function actionDesativar()
    {
        try
        {
        }
        catch(Exception $e)
        {
            $_SESSION['error'][] = $e->getMessage();
        }
    }



}
