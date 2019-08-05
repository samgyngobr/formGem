<?php

namespace app\modules\scarlet\models;

use Exception;
use Yii;
use yii\base\Model;


class ScarletVersion extends Model
{



    public function getAtual( $area )
    {
        $x = Yii::$app->db->createCommand( "SELECT * FROM scarlet_version WHERE area_id=:area_id AND active=:active" )
            ->bindValues([
                ':area_id' => $area,
                ':active'  => 1
                ])
            ->queryOne();

        if(!$x)
            throw new Exception("Versão não encontrada!", 002);

        return $x['id'];
    }



}
