<?php

namespace app\modules\scarlet\models;

use Exception;
use Yii;
use yii\base\Model;


class ScarletArea extends Model
{



    private $area = null;



    /**
     * set current area
     *
     * @param string $area
     */
    public function __construct( $area = null )
    {
        if( $area )
        {
            if( is_string( $area ) )
                $a = $this->detalhesURL( $area );
            else
                $a = $this->detalhesId( $area );

            $this->area = $a['id'];
        }
    }



    /**
     * list content
     *
     * @param string $query
     * @return array
     */
    public function listar( $query = null )
    {
        return Yii::$app->db->createCommand( "SELECT * FROM scarlet_area {$query}" )->queryAll();
    }



    /**
     * details
     *
     * @return array
     */
    public function detalhes()
    {
        $x = Yii::$app->db->createCommand( "SELECT * FROM scarlet_area WHERE id=:id" )
            ->bindValues([ ':id' => $this->area ])
            ->queryOne();

        if(!$x)
            throw new Exception("Área não encontrada!", 002);

        return $x;
    }



    /**
     * details using url
     *
     * @param string $url
     * @return array
     */
    public function detalhesURL( $url )
    {
        $x = Yii::$app->db->createCommand( "SELECT * FROM scarlet_area WHERE url=:url" )
            ->bindValues([ ':url' => $url ])
            ->queryOne();

        if( !$x )
            throw new Exception("Área não identificada!", 002);

        return $x;
    }




    /**
     * get details using id
     *
     * @param Integer $id
     * @return Array
     */
    public function detalhesId( $id )
    {
        $x = Yii::$app->db->createCommand( "SELECT * FROM scarlet_area WHERE id=:id" )
            ->bindValues([ ':id' => $id ])
            ->queryOne();

        if(!$x)
            throw new Exception("Área não encontrada!", 002);

        return $x;
    }






    /**
     * new
     *
     * @param array $arr
     * @return void
     */
    public function novo( $arr )
    {
        try
        {
            $url = Yii::$app->ScarletHelper->urlAmigavel( $arr['name'] );

            if( Yii::$app->db->createCommand( "SELECT * FROM scarlet_area WHERE url LIKE '{$url}' ")->queryOne() )
                throw new Exception("Título Inválido", 1);

            $fields = json_decode( $arr['json'], true );

            $db          = Yii::$app->db;
            $transaction = $db->beginTransaction();
            $ti          = true;

            Yii::$app->db->createCommand()->insert( 'scarlet_area', [
                'name'     => $url,
                'label'    => $arr['name'],
                'multiple' => $arr['multiple'],
                'gallery'  => $arr['gallery'],
                'area_id'  => ( $arr['area'] != '' ) ? $arr['area'] : null,
                'url'      => $url
            ])->execute();

            $area_id = Yii::$app->db->getLastInsertID();

            Yii::$app->db->createCommand()->insert( 'scarlet_version', [
                'area_id' => $area_id,
                'active'  => 1
            ])->execute();

            $version_id = Yii::$app->db->getLastInsertID();

            foreach ($fields as $key => $value)
            {
                $ar = [];

                foreach ($value as $k => $v)
                    $ar[ $v['name'] ] = $v['value'];

                Yii::$app->db->createCommand()->insert( 'scarlet_field', [
                    'version_id' => $version_id,
                    'name'       => Yii::$app->ScarletHelper->urlAmigavel( $ar['field'] ),
                    'label'      => $ar['field'],
                    'type'       => $ar['type'],
                    'required'   => $ar['required'],
                    'order'      => ( $ar['order'] ) ? $ar['order'] : 0,
                    'index'      => $ar['index'],
                ])->execute();

                $field_id = Yii::$app->db->getLastInsertID();

                foreach ($ar as $ke => $va)
                {
                    $posA = strpos( $ke, 'label[' );
                    $posB = strpos( $ke, ']' );

                    if( $posA !== false && $posB !== false )
                    {
                        $val = substr( $ke, $posA+6, $posB-($posA+6) );

                        Yii::$app->db->createCommand()->insert( 'scarlet_field_options', [
                            'field_id' => $field_id,
                            'name'     => $va,
                            'value'    => $ar[ 'value[' . $val . ']' ]
                        ])->execute();
                    }
                }

            } // foreach ($fields as $key => $value)

            $transaction->commit();

        }
        catch(\Exception $e)
        {
            if( isset( $ti ) and $ti )
                $transaction->rollBack();

            throw $e;
        }

    }




    /**
     * Edit content
     *
     * @param array $arr
     * @return void
     */
    public function edit( $arr )
    {
        try
        {
            $fields = json_decode( $arr['json'], true );

            Yii::$app->db->createCommand()->update( 'scarlet_area', ['label' => $arr['name'] ], [ 'id' => $arr['id'] ] )->execute();

            $db          = Yii::$app->db;
            $transaction = $db->beginTransaction();
            $ti          = true;

            Yii::$app->db->createCommand()->update( 'scarlet_version', ['active' => 0], [ 'area_id' => $arr['id'] ] )->execute();

            Yii::$app->db->createCommand()->insert( 'scarlet_version', [
                'area_id' => $arr['id'],
                'active'  => 1
            ])->execute();

            $version_id = Yii::$app->db->getLastInsertID();

            foreach ($fields as $key => $value)
            {
                $ar = [];

                foreach ($value as $k => $v)
                    $ar[ $v['name'] ] = $v['value'];

                Yii::$app->db->createCommand()->insert( 'scarlet_field', [
                    'version_id' => $version_id,
                    'label'      => $ar['field'],
                    'name'       => ( $ar['name'] != '' ) ? $ar['name'] : Yii::$app->ScarletHelper->urlAmigavel( $ar['field'] ),
                    'type'       => $ar['type'],
                    'required'   => $ar['required'],
                    'order'      => ( $ar['order'] ) ? $ar['order'] : 0,
                    'index'      => $ar['index'],
                ])->execute();

                $field_id = Yii::$app->db->getLastInsertID();

                foreach ($ar as $ke => $va)
                {
                    $posA = strpos( $ke, 'label[' );
                    $posB = strpos( $ke, ']' );

                    if( $posA !== false && $posB !== false )
                    {
                        $val = substr( $ke, $posA+6, $posB-($posA+6) );

                        Yii::$app->db->createCommand()->insert( 'scarlet_field_options', [
                            'field_id' => $field_id,
                            'name'     => $va,
                            'value'    => $ar[ 'value[' . $val . ']' ]
                        ])->execute();
                    }
                }

            } // foreach ($fields as $key => $value)

            $transaction->commit();


        }
        catch(\Exception $e)
        {
            if( isset( $ti ) and $ti )
                $transaction->rollBack();

            throw $e;
        }

    }








    /**
     * activate
     *
     * @return void
     */
    public function ativarArea()
    {
        Yii::$app->db->createCommand()->update( 'scarlet_area', ['status' => 1], ['id' => $this->area ] )->execute();
    }



    /**
     * disable
     *
     * @return void
     */
    public function desativarArea()
    {
        Yii::$app->db->createCommand()->update( 'scarlet_area', ['status' => 0], [ 'id' => $this->area ] )->execute();
    }



}
