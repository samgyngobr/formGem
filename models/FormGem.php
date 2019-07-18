<?php

namespace app\modules\FormGem\models;

use Exception;
use Yii;

use app\modules\FormGem\models\FormGemArea;
use app\modules\FormGem\models\FormGemField;
use app\modules\FormGem\models\FormGemFieldOptions;
use app\modules\FormGem\models\FormGemData;
use app\modules\FormGem\models\FormGemVersion;
use app\modules\FormGem\models\FormGemHistory;


class FormGem
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



    public function listarAreas( $query = null )
    {
        return ( new FormGemArea() )->listar( $query );
    }



    public function setArea( $area )
    {
        $this->area  = $area;
        $this->oArea = new FormGemArea( $area );
    }



    public function getArea()
    {
        return $this->oArea;
    }



}