<?php

namespace app\modules\scarlet\models;

use Exception;
use Yii;
use yii\base\Model;
use app\modules\scarlet\models\ScarletHistory;


class ScarletData extends Model
{





    private $table = 'scarlet_data';





    public function getData( $area, $published = false )
    {
        $st = '';

        if( $published )
            $st = ' AND published=1 ';

        $x = Yii::$app->db->createCommand( "SELECT * FROM $this->table WHERE id=:id {$st}" )
            ->bindValues([
                ':id' => $area
                ])
            ->queryOne();

        if(!$x)
            throw new Exception("Área não encontrada!", 002);

        return $x;
    }




    public function getDataUrl( $area, $url )
    {
        $x = Yii::$app->db->createCommand( "SELECT * FROM $this->table WHERE area_id=:area AND url=:url" )
            ->bindValues([
                ':area' => $area,
                ':url' => $url,
                ])
            ->queryOne();

        if(!$x)
            throw new Exception("Área não encontrada!", 002);

        return $x;
    }





    public function getAtual( $area )
    {
        $x = Yii::$app->db->createCommand( "SELECT * FROM $this->table WHERE area_id=:area_id" )
            ->bindValues([
                ':area_id' => $area
                ])
            ->queryOne();

        if(!$x)
        {
            $b = Yii::$app->db->createCommand( "SELECT * FROM scarlet_area WHERE id=:id" )
                ->bindValues([
                    ':id' => $area
                    ])
                ->queryOne();

            if( !$b or $b['multiple'] == 1 )
                throw new Exception("Área não encontrada!", 002);

            Yii::$app->db->createCommand()->insert( $this->table, [
                'area_id'   => $area,
                'published' => 1
            ])->execute();

            $data_id = Yii::$app->db->getLastInsertID();

            $v = Yii::$app->db->createCommand( "SELECT * FROM scarlet_version WHERE area_id=:id" )
                ->bindValues([
                    ':id' => $area
                    ])
                ->queryOne();

            Yii::$app->db->createCommand()->insert( 'scarlet_history', [
                'version_id' => $v['id'],
                'data_id'    => $data_id,
            ])->execute();

            $x = Yii::$app->db->createCommand( "SELECT * FROM $this->table WHERE area_id=:area_id" )
                ->bindValues([
                    ':area_id' => $area
                    ])
                ->queryOne();

            if(!$x)
                throw new Exception("Área não encontrada!", 002);
        }

        return $x;
    }





    public function getListBasic( $area, $version )
    {
        $list = Yii::$app->db->createCommand( " SELECT * FROM $this->table WHERE deleted=0 AND area_id=:area_id " )
            ->bindValues([
                ':area_id' => $area
                ])
            ->queryAll();

        foreach ($list as $key => &$value)
            $value['fields'] = ( new ScarletHistory() )->listarBasic( $value['id'], $version );

        return $list;

    }



    public function getListRaw( $area, $version, $published = false )
    {
        $st = '';

        if( $published )
            $st = ' AND published=1 ';

        $list = Yii::$app->db->createCommand( " SELECT * FROM $this->table WHERE deleted=0 {$st} AND area_id=:area_id " )
            ->bindValues([
                ':area_id' => $area
                ])
            ->queryAll();

        return $list;

    }





    public function novo( $arr )
    {
        try
        {
            $url = '';

            foreach ($arr['fields'] as $key => $value)
            {
                if( $value['index'] == 1 )
                {
                    $url = $this->str2Url( $arr['post'][ $value['name'] ] );
                }
            }

            $arr = $this->validate_( $arr );

            $db          = Yii::$app->db;
            $transaction = $db->beginTransaction();
            $ti          = true;

            // cria novo indice
            Yii::$app->db->createCommand()->insert( $this->table, [
                'area_id' => $arr['area'],
                'url'     => $url
            ])->execute();

            $arr['data_id'] = Yii::$app->db->getLastInsertID();

            ( new ScarletHistory() )->insert( $arr, $db, $transaction );

            $transaction->commit();

        }
        catch(\Exception $e)
        {
            if( isset( $ti ) and $ti )
                $transaction->rollBack();

            throw $e;
        }

    }


    public function editar( $arr )
    {
        try
        {
            ( new ScarletHistory() )->insert( $this->validate_( $arr, 'edit' ) );
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }






    public function delete( $id )
    {
        Yii::$app->db->createCommand()->update( $this->table, ['deleted' => 1], 'id=' . $id )->execute();
    }





    public function publicar( $id )
    {
        Yii::$app->db->createCommand()->update( $this->table, ['published' => 1], 'id=' . $id )->execute();
    }





    public function removerPublicacao( $id )
    {
        Yii::$app->db->createCommand()->update( $this->table, ['published' => 0], 'id=' . $id )->execute();
    }





    public function validate_( $arr, $acao = '' )
    {
        foreach ( $arr['fields'] as $key => $v )
        {

            switch ( $v['type'] )
            {

                // text
                case 1:
                    $arr['post'][ $v['name'] ] = $this->processText( $v, $arr['post'][ $v['name'] ] );
                    break;


                // numeros inteiros
                case 2:
                    $arr['post'][ $v['name'] ] = $this->processInteger( $v, $arr['post'][ $v['name'] ] );
                    break;


                // numeros decimais
                case 3:
                    $arr['post'][ $v['name'] ] = $this->processDouble( $v, $arr['post'][ $v['name'] ] );
                    break;


                // textarea
                case 4:
                    $arr['post'][ $v['name'] ] = $this->processTextArea( $v, $arr['post'][ $v['name'] ] );
                    break;


                // select
                case 5:
                    $arr['post'][ $v['name'] ] = $this->processSelect( $v, $arr['post'][ $v['name'] ] );
                    break;


                // radio
                case 6:
                    $arr['post'][ $v['name'] ] = $this->processRadio( $v, $arr['post'][ $v['name'] ] );
                    break;


                // checkbox
                case 7:
                    $arr['post'][ $v['name'] ] = $this->processCheckbox( $v, $arr['post'][ $v['name'] ] );
                    break;


                // image
                case 8:
                    $arr['post'][ $v['name'] ] = $this->processImage( $v, $arr['post'][ $v['name'] ], $acao );
                    break;


                // upload
                case 9:
                    $arr['post'][ $v['name'] ] = $this->processUpload( $v, $arr['post'][ $v['name'] ], $acao );
                    break;


            } // switch ( $v['type'] ) {

        } // foreach ( $arr['fields'] as $key => $v ) {

        return $arr;
    }





    public function processText( $input, $post )
    {
        return $post;
    }





    public function processTextArea( $input, $post )
    {
        return $post;
    }





    public function processInteger( $input, $post )
    {
        if( !is_numeric( $post ) AND !strpos( $post, '.' ) )
            throw new Exception("Campo \"" . $input['label'] . "\" Precisa ser número inteiro!", 1);

        return $post;
    }





    public function processDouble( $input, $post )
    {
        if( !is_numeric( $post ) AND strpos( $post, '.' ) )
            throw new Exception("Campo \"" . $input['label'] . "\" Precisa ser número decimal!", 1);

        return $post;
    }





    public function processSelect( $input, $post )
    {
        return $post;
    }





    public function processRadio( $input, $post )
    {
        return $post;
    }





    public function processCheckbox( $input, $post )
    {
        return $post;
    }





    public function processImage( $input, $post, $acao )
    {

        // na edição, não editar quando n informado
        if( $acao=='edit' and $post['tmp_name'] == '' )
            return $input['value'];


        // item obrigatorio?
        if( $input['required'] and $post['tmp_name'] == '' )
            throw new Exception('Não foi possível encontrar o arquivo "' . $input['label'] . '"', 1);


        $ext  = explode( '.', $post['name'] );
        $file = uniqid() . '.' . $ext[ count( $ext )-1 ];

        if ( !move_uploaded_file( $post['tmp_name'], './uploads/files/' . $file ) )
            throw new Exception('Não foi possível enviar arquivo "' . $input['label'] . '"', 1);


        return $file;
    }





    public function processUpload( $input, $post, $acao )
    {

        // na edição, não editar quando n informado
        if( $acao=='edit' and $post['tmp_name'] == '' )
            return $input['value'];


        // item obrigatorio?
        if( $input['required'] and $post['tmp_name'] == '' )
            throw new Exception('Não foi possível encontrar o arquivo "' . $input['label'] . '"', 1);


        $ext  = explode( '.', $post['name'] );
        $file = uniqid() . '.' . $ext[ count( $ext )-1 ];

        if ( !move_uploaded_file( $post['tmp_name'], './uploads/files/' . $file ) )
            throw new Exception('Não foi possível enviar arquivo "' . $input['label'] . '"', 1);


        return $file;
    }














    public function str2Url($string)
    {
        $url = str_replace(' ', '_', urldecode($string));
        $url = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($url));
        $url = str_replace('_', '-', $url);
        $url = str_replace('---', '-', $url);
        $url = str_replace('--', '-', $url);
        $url = str_replace('?', '', $url);
        $url = str_replace('%', '', $url);
        $url = str_replace(')', '', $url);
        $url = str_replace('(', '', $url);
        $url = str_replace('.', '', $url);
        $url = str_replace('$', '', $url);
        $url = str_replace(',', '', $url);
        $url = str_replace(':', '', $url);
        $url = str_replace(';', '', $url);
        $url = str_replace('/', '', $url);

        return strtolower($url);
    }







    /*

    SELECT
        *
    FROM
                  scarlet_data
        LEFT JOIN scarlet_history       ON          scarlet_data.id         = scarlet_history.data_id
        LEFT JOIN scarlet_version       ON       scarlet_history.version_id = scarlet_version.id
        LEFT JOIN scarlet_field         ON         scarlet_field.version_id = scarlet_version.id
        LEFT JOIN scarlet_data_integer  ON  scarlet_data_integer.history_id = scarlet_history.id AND scarlet_field.`type` = 2
        LEFT JOIN scarlet_data_date     ON     scarlet_data_date.history_id = scarlet_history.id AND scarlet_field.`type` = 10
        LEFT JOIN scarlet_data_decimal  ON  scarlet_data_decimal.history_id = scarlet_history.id AND scarlet_field.`type` = 3
        LEFT JOIN scarlet_data_boolean  ON  scarlet_data_boolean.history_id = scarlet_history.id AND scarlet_field.`type` = 11
        LEFT JOIN scarlet_data_textarea ON scarlet_data_textarea.history_id = scarlet_history.id AND scarlet_field.`type` = 4
        LEFT JOIN scarlet_data_varchar  ON  scarlet_data_varchar.history_id = scarlet_history.id AND scarlet_field.`type` in ( 5,6,7,8,9 )
    WHERE
            deleted=0
        AND scarlet_data.area_id=2
        AND scarlet_history.current=1
        AND (
                scarlet_field.name like 'txt' AND (
                        scarlet_data_integer.value like '%teste%'
                    OR     scarlet_data_date.value like '%teste%'
                    OR  scarlet_data_decimal.value like '%teste%'
                    OR  scarlet_data_boolean.value like '%teste%'
                    OR scarlet_data_textarea.value like '%teste%'
                    OR  scarlet_data_varchar.value like '%teste%'
                )
          )
    GROUP BY
        scarlet_data.id

    */


    public function search( $area, $version, array $op )
    {

        $whrr = $this->genWhere( $op );

        if( $whrr != '' )
            $whrr = "AND ( {$whrr} )";


        $list = Yii::$app->db->createCommand( "

            SELECT
                *
            FROM
                          scarlet_data
                LEFT JOIN scarlet_history       ON          scarlet_data.id         = scarlet_history.data_id
                LEFT JOIN scarlet_version       ON       scarlet_history.version_id = scarlet_version.id
                LEFT JOIN scarlet_field         ON         scarlet_field.version_id = scarlet_version.id
                LEFT JOIN scarlet_data_integer  ON  scarlet_data_integer.history_id = scarlet_history.id AND scarlet_field.`type` = 2
                LEFT JOIN scarlet_data_date     ON     scarlet_data_date.history_id = scarlet_history.id AND scarlet_field.`type` = 10
                LEFT JOIN scarlet_data_decimal  ON  scarlet_data_decimal.history_id = scarlet_history.id AND scarlet_field.`type` = 3
                LEFT JOIN scarlet_data_boolean  ON  scarlet_data_boolean.history_id = scarlet_history.id AND scarlet_field.`type` = 11
                LEFT JOIN scarlet_data_textarea ON scarlet_data_textarea.history_id = scarlet_history.id AND scarlet_field.`type` = 4
                LEFT JOIN scarlet_data_varchar  ON  scarlet_data_varchar.history_id = scarlet_history.id AND scarlet_field.`type` in (1,5,6,7,8,9)
            WHERE
                    deleted=0
                AND scarlet_data.area_id={$area}
                AND scarlet_history.current=1
                {$whrr}
            GROUP BY
                scarlet_data.id

            " )->queryAll();

        return $list;

    }



    private function genWhere( array $op )
    {
        $str = '';

        foreach ($op as $key => $value)
        {
            if( is_array( $value[0] ) )
            {
                $str .= '(';

                foreach ($value as $k => $v)
                {
                    $str .= $this->genWhere( [ $v ] );
                }

                $str .= ')';
            }
            else
            {
                $str .= $this->genTxt( $value[0], $value[1], $value[2] );
            }
        }

        return $str;
    }



    private function genTxt( $field, $str, $op )
    {
        return "
            scarlet_field.name like '{$field}' AND (
                    scarlet_data_integer.value like '{$str}'
                OR     scarlet_data_date.value like '{$str}'
                OR  scarlet_data_decimal.value like '{$str}'
                OR  scarlet_data_boolean.value like '{$str}'
                OR scarlet_data_textarea.value like '{$str}'
                OR  scarlet_data_varchar.value like '{$str}'
            ) {$op}
        ";
    }













}
