<?php

namespace app\modules\scarlet\models;

use Exception;
use Yii;
use yii\base\Model;

use app\modules\scarlet\models\ScarletField;


class ScarletHistory extends Model
{





    private $table  = 'scarlet_history';
    private $tInput = array(
            '1' => 'scarlet_data_varchar',
            '2' => 'scarlet_data_integer',
            '3' => 'scarlet_data_decimal',
            '4' => 'scarlet_data_textarea',
            '5' => 'scarlet_data_varchar',
            '6' => 'scarlet_data_varchar',
            '7' => 'scarlet_data_varchar',
            '8' => 'scarlet_data_varchar',
            '9' => 'scarlet_data_varchar',
        );







    /**
     * get Data
     *
     * @param array $config
     * @return array
     */
    public function getData( $config )
    {

        $data = Yii::$app->db->createCommand( "
                SELECT
                    *
                FROM
                    scarlet_history
                WHERE
                        version_id=:version_id
                    AND  published=:published
                ORDER BY
                    date_creation DESC
                " )
            ->bindValues([
                ':version_id' => $config['version_id'],
                ':published'  => $config['published'],
                ])
            ->queryOne();


        if(count($data)==0)
            return false;


        return $data;
    }




    /**
     * get form data
     *
     * @param array $fields
     * @param array $config
     * @return array
     */
    public function getDataForm( $fields, $config )
    {
        $data = $this->getData( $config );

        if( $data )
        {
            foreach ($fields as &$value)
            {

                $aux = Yii::$app->db->createCommand( "SELECT * FROM :type WHERE history_id=:history_id AND field_id=:field_id" )
                    ->bindValues([
                        ':type'       => $this->tInput[ $value['type'] ],
                        ':history_id' => $data['id'],
                        ':field_id'   => $value['id'],
                        ])
                    ->queryOne();


                if(count($aux)>0)
                    $value['value'] = $aux['value'];
                else
                    $value['value'] = '';


            } // foreach ($fields as &$value)

        } // if( $data )


        return $fields;
    }






    /**
     * Insert
     *
     * @param Array $arr
     * @param Object $db
     * @param Object $transaction
     * @return void
     */
    public function insert( $arr, &$db = null, &$transaction = null )
    {
        if( !$db )
            $db = Yii::$app->db;

        $ti = false;

        if( !$transaction )
            $transaction = $db->beginTransaction();
        else
            $ti = true;


        try
        {

            Yii::$app->db->createCommand()
                ->update( $this->table, ['current' => 0], [ 'data_id' => $arr['data_id'] ] )
                ->execute();

            // cria nova versao
            Yii::$app->db->createCommand()->insert( $this->table, [
                'version_id' => $arr['version'],
                'data_id'    => $arr['data_id'],
                'user_id'    => $arr['creator'],
                'current'    => 1,
            ])->execute();

            $idHistory = Yii::$app->db->getLastInsertID();


            // insere dados dos campos
            foreach ( $arr['fields'] as $key => $value )
            {

                if( $value['required'] and ( $arr['post'][ $value['name'] ] == '' or !isset( $arr['post'][ $value['name'] ] ) ) )
                    throw new Exception('Campo "' . $value['label'] . '" obrigatorio!', 1);


                if( is_array( $arr['post'][ $value['name'] ] ) )
                {

                    foreach ( $arr['post'][ $value['name'] ] as $k => $v)
                    {
                        Yii::$app->db->createCommand()->insert( $this->tInput[ $value['type'] ], [
                            'history_id' => $idHistory,
                            'field_id'   => $value['id'],
                            'value'      => $v,
                        ])->execute();
                    }

                }
                else
                    Yii::$app->db->createCommand()->insert( $this->tInput[ $value['type'] ], [
                        'history_id' => $idHistory,
                        'field_id'   => $value['id'],
                        'value'      => $arr['post'][ $value['name'] ],
                    ])->execute();

            } // foreach ( $arr['fields'] as $key => $value )


            if( !$ti )
                $transaction->commit();

        }
        catch(\Exception $e)
        {
            if( !$ti )
                $transaction->rollBack();

            throw $e;
        }


    }







    /**
     * get id using id
     *
     * @param int $data_id
     * @return array
     */
    public function getById( $data_id )
    {

        $hist = Yii::$app->db->createCommand( "
                SELECT
                    *
                FROM
                    scarlet_history
                WHERE
                    data_id=:data_id
                ORDER BY
                    date_creation DESC
            " )
            ->bindValues([ ':data_id' => $data_id ])
            ->queryOne();

        if(count($hist)==0)
            return false;


        $historico = $this->getList( $hist );


        if(!$historico)
            return false;


        return $historico;
    }





    /**
     * List
     *
     * @param array $hist
     * @return array
     */
    public function getList( $hist )
    {

        /*
        select * from scarlet_area;
        select * from scarlet_field where area_id=1;
        select * from scarlet_data where area_id=1;

        select
            * , id as d,
            ( select value from scarlet_data_varchar where data_id=d and field_id=1 ) as titulo,
            ( select value from scarlet_data_textarea where data_id=d and field_id=2 ) as descricao
        from
            scarlet_data
        where
            area_id=1
            ;
        */

        $fields = ( new ScarletField() )->getFields( $hist['version_id'] );

        $ss = '';

        foreach ($fields as $k => $v)
        {
            // contatena "," em toda recursão menos na ultima vez
            if($k!=count($fields))
                $ss .=', ';

            // checkbox pode possuir mais de um valor
            if( $v['type'] == 7 )
                $ss .= " (
                            SELECT
                                GROUP_CONCAT( value separator ';' )
                            FROM
                                {$this->tInput[$v['type']]}
                            WHERE
                                history_id=d and field_id={$v['id']}
                        ) AS '{$v['name']}'
                    ";
            else
                $ss .= " (
                            SELECT
                                value
                            FROM
                                {$this->tInput[$v['type']]}
                            WHERE
                                history_id=d and field_id={$v['id']}
                        ) AS '{$v['name']}'
                    ";
        }

        return Yii::$app->db->createCommand( "
                    SELECT
                        * , id AS d
                        {$ss}
                    FROM
                        {$this->table}
                    WHERE
                        data_id={$hist['data_id']}
                    ORDER BY
                        id DESC
                    ;
                " )->queryOne();

    }




    /**
     * basic list
     *
     * @param id $data_id
     * @param int $version
     * @return array
     */
    public function listarBasic( $data_id, $version )
    {

        $fields = ( new ScarletField() )->getMainFields( $version );
        $ss     = '';

        foreach ($fields as $k => $v)
        {
            if($k!=count($fields))
                $ss .=', ';

            $ss .= " (
                        SELECT
                            value
                        FROM
                            {$this->tInput[$v['type']]}
                        WHERE
                                history_id={$this->table}.id
                            AND   field_id={$v['id']}
                    ) as {$v['name']} ";
        }

        return Yii::$app->db->createCommand( "
                    SELECT
                        date_creation as last_update
                        {$ss}
                    FROM
                        {$this->table}
                    WHERE
                        data_id={$data_id}
                    ORDER BY
                        id desc
                    ;
                " )->queryOne();

    }




}
