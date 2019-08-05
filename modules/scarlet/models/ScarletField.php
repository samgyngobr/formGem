<?php

namespace app\modules\scarlet\models;

use Exception;
use Yii;
use yii\base\Model;


class ScarletField extends Model
{




    /**
     * List Fields
     *
     * @param int $version
     * @return array
     */
    public function getFields( $version )
    {
        $fields = Yii::$app->db->createCommand( "SELECT * FROM scarlet_field WHERE version_id=:version_id ORDER BY `order` ASC" )
            ->bindValues([ ':version_id' => $version ])
            ->queryAll();

        foreach( $fields as &$v )
            if( $v['type']==5 or $v['type']==6 or $v['type']==7 )
                $v['options'] = ( new ScarletFieldOptions() )->listar( $v['id'] );

        return $fields;
    }





    /**
     * get main fields
     *
     * @param int $version
     * @return array
     */
    public function getMainFields( $version )
    {
        $fields = Yii::$app->db->createCommand( "SELECT * FROM scarlet_field WHERE `index`=1 AND version_id=:version_id ORDER BY `order` ASC" )
            ->bindValues([ ':version_id' => $version ])
            ->queryAll();

        foreach( $fields as &$v )
            if( $v['type']==5 or $v['type']==6 or $v['type']==7 )
                $v['options'] = ( new ScarletFieldOptions() )->listar( $v['id'] );

        return $fields;
    }



}
