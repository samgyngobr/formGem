<?php

namespace app\modules\scarlet\models;

use Exception;
use Yii;

use app\modules\scarlet\models\ScarletArea;
use app\modules\scarlet\models\ScarletField;
use app\modules\scarlet\models\ScarletFieldOptions;
use app\modules\scarlet\models\ScarletData;
use app\modules\scarlet\models\ScarletVersion;
use app\modules\scarlet\models\ScarletHistory;


class Scarlet
{





    private $oArea;


    private $area    = null;
    private $acao    = null;
    private $id      = null;

    private $fields  = null;
    public  $areaArr = null;
    public  $version = null;
    public  $error   = null;





    public function __construct()
    {
    }





    /**
     * List Areas
     *
     * @param array $query
     * @return array
     */
    public function listarAreas( $query = null )
    {
        return ( new ScarletArea() )->listar( $query );
    }





    /**
     * Set current area
     *
     * @param string $area
     * @return void
     */
    public function setArea( $area )
    {
        $this->area  = $area;
        $this->oArea = new ScarletArea( $area );
    }





    /**
     * get area
     *
     * @return array
     */
    public function getArea()
    {
        return $this->oArea;
    }





    /**
     * List Content
     *
     * @return array
     */
    public function listar()
    {
        $area    = $this->oArea->detalhes();
        $version = ( new ScarletVersion() )->getAtual( $area['id'] );

        return [
            'view'        => 'index',
            'area'        => $area,
            'data'        => ( new ScarletData()  )->getListBasic( $area['id'], $version ),
            'fieldLabels' => ( new ScarletField() )->getMainFields( $version ),
        ];
    }





    /**
     * New
     *
     * @return array
     */
    public function novo()
    {
        $area    = $this->oArea->detalhes();
        $version = ( new ScarletVersion() )->getAtual( $area['id'] );
        $fields  = ( new ScarletField() )->getFields( $version );

        return array(
            'view'    => 'edit',
            'version' => $version,
            'acao'    => 'novo',
            'area'    => $area,
            'fields'  => $fields,
        );
    }




    /**
     * Find using id
     *
     * @param integer $id
     * @return array
     */
    public function findId( $id )
    {
        $area    = $this->oArea->detalhes();
        $version = ( new ScarletVersion() )->getAtual( $area['id'] );

        // pega indice dos dados
        $data = ( new ScarletData() )->getData( $id );

        // pega os dados referentes aos fields
        $currentHistory = ( new ScarletHistory() )->getById( $data['id'] );

        return $currentHistory;
    }







    /**
     * find using url
     *
     * @param string $url
     * @return array
     */
    public function findUrl( $url )
    {
        $area    = $this->oArea->detalhes();
        $version = ( new ScarletVersion() )->getAtual( $area['id'] );

        // pega indice dos dados
        $data = ( new ScarletData() )->getDataUrl( $area['id'], $url );

        // pega os dados referentes aos fields
        $currentHistory = ( new ScarletHistory() )->getById( $data['id'] );

        return $currentHistory;
    }








    /**
     * get content to Edit
     *
     * @param id $id
     * @return array
     */
    public function editar( $id )
    {
        $area    = $this->oArea->detalhes();
        $version = ( new ScarletVersion() )->getAtual( $area['id'] );

        // pega indice dos dados
        $data = ( new ScarletData() )->getData( $id );

        // pega os fields da versao atual
        $fields = ( new ScarletField() )->getFields( $version );

        // pega os dados referentes aos fields
        $currentHistory = ( new ScarletHistory() )->getById( $data['id'] );

        // mescla os campos com seus valores
        foreach ($fields as $key => &$value)
            $value['value'] = ( isset( $currentHistory[ $value['name'] ] ) ) ? $currentHistory[ $value['name'] ] : null;

        return array(
            'view'    => 'edit',
            'data'    => $data,
            'version' => $version,
            'area'    => $area,
            'fields'  => $fields,
        );

    }





    /**
     * del
     *
     * @param integer $id
     * @return void
     */
    public function excluir( $id )
    {
        try
        {
            ( new ScarletData() )->delete( $id );

            return true;
        }
        catch(Exception $e)
        {
            $this->error = $e->getMessage();
        }
    }






    /**
     * remove published
     *
     * @param integer $id
     * @return boolean
     */
    public function removerPublicacao( $id )
    {
        try
        {
            ( new ScarletData() )->removerPublicacao( $id );

            return true;
        }
        catch(Exception $e)
        {
            $this->error = $e->getMessage();
            return false;
        }
    }






    /**
     * publish
     *
     * @param integer $id
     * @return boolean
     */
    public function publicar( $id )
    {
        try
        {
            ( new ScarletData() )->publicar( $id );

            return true;
        }
        catch(Exception $e)
        {
            $this->error = $e->getMessage();
            return false;
        }
    }





    /**
     * get unique current date
     *
     * @return array
     */
    public function uniqueData()
    {
        $area = $this->oArea->detalhes();

        return ( new ScarletData() )->getAtual( $area['id'] );
    }





    /**
     * save content
     *
     * @param string $acao
     * @param array $post
     * @param integer $creator
     * @param integer $id
     * @return void
     */
    public function save( $acao, $post, $creator, $id = null )
    {
        $area   = $this->oArea->detalhes();
        $versao = ( new ScarletVersion() )->getAtual( $area['id'] );


        if( $acao == 'novo' )
        {
            return ( new ScarletData() )->novo([
                'area'    => $area['id'],
                'version' => $versao,
                'fields'  => ( new ScarletField() )->getFields( $versao ),
                'post'    => $post,
                'creator' => $creator,
            ]);
        }
        // if( $this->acao == 'novo' )
        elseif( $acao == 'editar' )
        {

            // pega historico, caso upload vazio permanece valores antigos
            $data           = ( new ScarletData() )->getData( $id );
            $currentHistory = ( new ScarletHistory() )->getById( $data['id'] );
            $fields         = ( new ScarletField() )->getFields( $versao );


            foreach ($fields as $key => &$value)
                $value['value'] = $currentHistory[$value['name']];


            return ( new ScarletData() )->editar([
                'area'    => $area['id'],
                'version' => $versao,
                'fields'  => $fields,
                'data_id' => $id,
                'post'    => $post,
                'creator' => $creator,
            ]);

        }
        // if( $this->acao == 'editar' )
        else
        {
            throw new Exception("Operação não encontrada!", 1);
        }
        // if( $this->acao == 'novo' )
    }








    /**
     * get unique date
     *
     * @return array
     */
    public function fetchUnique()
    {
        $area = $this->oArea->detalhes();
        $id   = ( new ScarletData() )->getAtual( $area['id'] );
        $id   = $id['id'];

        $area    = $this->oArea->detalhes();
        $version = ( new ScarletVersion() )->getAtual( $area['id'] );

        // pega indice dos dados
        $data = ( new ScarletData() )->getData( $id, 1 );

        // pega os fields da versao atual
        $fields = ( new ScarletField() )->getFields( $version );

        // pega os dados referentes aos fields
        $currentHistory = ( new ScarletHistory() )->getById( $data['id'] );

        // mescla os campos com seus valores
        foreach ($fields as $key => &$value)
            $value['value'] = ( isset( $currentHistory[ $value['name'] ] ) ) ? $currentHistory[ $value['name'] ] : null;

        $ret = [];

        foreach ($fields as $key => $v)
            $ret[ $v['name'] ] = $v['value'];

        return $ret;
    }





    /**
     * get date
     *
     * @return array
     */
    public function fetch()
    {

        $area    = $this->oArea->detalhes();
        $version = ( new ScarletVersion() )->getAtual( $area['id'] );             // pega versao atual
        $data    = ( new ScarletData() )->getListRaw( $area['id'], $version, 1 ); // lista dados
        $fields  = ( new ScarletField() )->getFields( $version );                 // pega os fields da versao atual

        foreach ($data as $k => &$v)
        {
            // pega os dados referentes aos fields
            $currentHistory = ( new ScarletHistory() )->getById( $v['id'] );

            // mescla os campos com seus valores
            foreach ($fields as $key => $value)
                $v[ $value['name'] ] = ( isset( $currentHistory[ $value['name'] ] ) ) ? $currentHistory[ $value['name'] ] : null;

        } // foreach ($data as $key => &$value)

        return $data;
    }







    /**
     * search
     *
     * @param array $op
     * @return array
     */
    public function search(array $op = [])
    {
        $area    = $this->oArea->detalhes();
        $version = ( new ScarletVersion() )->getAtual( $area['id'] );          // pega versao atual
        $fields  = ( new ScarletField() )->getFields( $version );              // pega os fields da versao atual

        return ( new ScarletData() )->search( $area['id'], $version, $op ); // lista dados
    }




    /**
     * new
     *
     * @param array $arr
     * @return void
     */
    public function newArea(array $arr = [])
    {
        ( new ScarletArea() )->novo( $arr );
    }




    /**
     * edit area
     *
     * @param array $arr
     * @return void
     */
    public function edtArea(array $arr = [])
    {
        $area      = $this->oArea->detalhes();
        $arr['id'] = $area['id'];

        ( new ScarletArea() )->edit( $arr );
    }




    /**
     * edit view
     *
     * @param array $arr
     * @return array
     */
    public function edtView( array $arr = [] )
    {
        $area    = $this->oArea->detalhes();
        $version = ( new ScarletVersion() )->getAtual( $area['id'] );          // pega versao atual
        $fields  = ( new ScarletField() )->getFields( $version );              // pega os fields da versao atual
        $json    = [];

        foreach ( $fields as $key => &$value )
        {
            $j = [
                [
                    'name'  => 'field',
                    'value' => $value['label'],
                ],
                [
                    'name'  => 'required',
                    'value' => $value['required'],
                ],
                [
                    'name'  => 'index',
                    'value' => $value['index'],
                ],
                [
                    'name'  => 'order',
                    'value' => $value['order'],
                ],
                [
                    'name'  => 'type',
                    'value' => $value['type'],
                ],
                [
                    'name'  => 'name',
                    'value' => $value['name'],
                ],
            ];

            foreach ($value['options'] as $k => $v)
            {
                $j[] = [
                    'name'  => "label[$k]",
                    'value' => $v['name'],
                ];
                $j[] = [
                    'name'  => "value[$k]",
                    'value' => $v['value'],
                ];
            }

            $json[] = $j;

        } // foreach ( $fields as $key => $value )

        $area['json'] = json_encode( $json );

        return $area;
    }




}
