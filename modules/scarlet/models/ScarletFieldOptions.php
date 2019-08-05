<?php

namespace app\modules\scarlet\models;

use Exception;
use Yii;
use yii\base\Model;



class ScarletFieldOptions extends Model
{



    /**
     * List Fields
     *
     * @param int $id
     * @return array
     */
    public function listar( $id )
    {
        return Yii::$app->db->createCommand( "SELECT * FROM scarlet_field_options WHERE field_id=:field_id" )
            ->bindValues([ ':field_id' => $id ])
            ->queryAll();
    }



}
