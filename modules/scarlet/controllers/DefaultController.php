<?php

namespace app\modules\scarlet\controllers;

use Exception;
use Yii;
use yii\web\Controller;

use app\modules\scarlet\models\Scarlet;


/**
 * Default controller for the `scarlet` module
 */
class DefaultController extends Controller
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
            // http://www.yiiframework.com/doc-2.0/guide-db-dao.html

            // parametros passados pela url
            $dados['area'] = Yii::$app->request->get('area');
            $dados['acao'] = Yii::$app->request->get('acao');
            $dados['id']   = Yii::$app->request->get('id');


            // marca na sidebar o elemento selecionado
            $this->view->params['markViews'] = $dados['area'];


            // view default
            $view = 'index';


            $objScarlet = new Scarlet();
            $objScarlet->setArea( $dados['area'] );
            $area       = $objScarlet->getArea()->detalhes();



            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {

                try
                {

                    $post = Yii::$app->request->post();
                    $post = array_merge( $post, $_FILES );


                    if( $area['multiple'] )
                    {
                        $objScarlet->save( $dados['acao'], $post, Yii::$app->user->id, $dados['id'] );
                    }
                    else
                    {
                        $data = $objScarlet->uniqueData();

                        $objScarlet->save( 'editar', $post, Yii::$app->user->id, $data['id'] );
                    }


                    $_SESSION['success'] = 'Item atualizado com sucesso!';

                }
                catch(Exception $e)
                {
                    var_dump($e);die;

                    $_SESSION['error'][] = $e->getMessage();
                }

            } // if ($_SERVER['REQUEST_METHOD'] == 'POST')


            if( $area['multiple'] )
            {

                switch ( $dados['acao'] )
                {

                    case 'novo' :
                        $dados['config'] = $objScarlet->novo();
                        break;


                    case 'editar' :
                        $dados['config'] = $objScarlet->editar( $dados['id'] );
                        break;


                    case 'excluir' :

                        if( $objScarlet->excluir( $dados['id'] ) )
                            $_SESSION['success'] = 'Item excluido com sucesso!';
                        else
                            $_SESSION['error'][] = $objScarlet->error;

                        $dados['config'] = $objScarlet->listar();

                        break;


                    case 'remover-publicacao' :

                        if( $objScarlet->removerPublicacao( $dados['id'] ) )
                            $_SESSION['success'] = 'Publicação desativada com sucesso!';
                        else
                            $_SESSION['error'][] = $objScarlet->error;

                        $dados['config'] = $objScarlet->listar();

                        break;


                    case 'publicar' :

                        if( $objScarlet->publicar( $dados['id'] ) )
                            $_SESSION['success'] = 'Item publicado com sucesso!';
                        else
                            $_SESSION['error'][] = $objScarlet->error;

                        $dados['config'] = $objScarlet->listar();

                        break;


                    default :
                        $dados['config'] = $objScarlet->listar();
                        break;
                }

            }
            else
            {
                $data            = $objScarlet->uniqueData();
                $dados['config'] = $objScarlet->editar( $data['id'] );
            }


            $view = $dados['config']['view'];


        }
        catch(Exception $e)
        {
            var_dump($e);die;
            $_SESSION['error'][] = $e->getMessage();
        }

        //var_dump($dados); die;

        return $this->render( $view, $dados );
    }










    public function actionGaleria()
    {
        try
        {

            // parametros passados pela url
            $dados['area'] = Yii::$app->request->get('area');
            $dados['acao'] = Yii::$app->request->get('acao');
            $dados['id']   = Yii::$app->request->get('id');


            // marca na sidebar o elemento selecionado
            $this->view->params['markViews'] = $dados['area'];


            $objScarlet = new Scarlet();
            $objScarlet->setArea( $dados['area'] );
            $area       = $objScarlet->getArea()->detalhes();

            $dados['area'] = $area;

            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {

                try
                {
                    $post = Yii::$app->request->post();
                    $post = array_merge( $post, $_FILES );



                    $_SESSION['success'] = 'Item atualizado com sucesso!';
                }
                catch(Exception $e)
                {
                    var_dump($e);die;

                    $_SESSION['error'][] = $e->getMessage();
                }

            } // if ($_SERVER['REQUEST_METHOD'] == 'POST') {


        }
        catch(Exception $e)
        {
            $_SESSION['error'][] = $e->getMessage();
        }

        return $this->render( 'galeria', $dados );
    }








}
