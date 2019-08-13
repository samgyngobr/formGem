<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

use app\modules\scarlet\models\Scarlet;


class ScarletHelper extends Component
{


    /**
     * List content
     *
     * @param array $query
     * @return array
     */
    public function listar( $query = null )
    {
        return ( new Scarlet() )->listarAreas( 'WHERE status=1 ' );
    }



    /**
     * Generate Form
     *
     * @param array $fields
     * @param string $acao
     * @return string
     */
    public function formGen( $fields, $acao )
    {

        $str = '<form method="post" class="form-horizontal" enctype="multipart/form-data" >';

        foreach ($fields as $key => $value)
        {
            $required = ( $value['required'] ) ? 'required' : '';

            switch ( $value['type'] )
            {

                case '1': // text
                    $str .= "
                    <div class='form-group'>
                        <label class='control-label col-md-2' for='general-text' for='{$value['name']}' >{$value['label']}</label>
                        <div class='col-md-6'>
                            <input type='text' id='{$value['name']}' class='form-control' placeholder='{$value['label']}' name='{$value['name']}' value='{$value['value']}' {$required} >
                        </div>
                    </div>
                    ";
                    break;


                case '2': // integer
                    $str .= "
                    <div class='form-group'>
                        <label class='control-label col-md-2' for='general-text' for='{$value['name']}' >{$value['label']}</label>
                        <div class='col-md-6'>
                            <input type='text' id='{$value['name']}' class='form-control integer' placeholder='{$value['label']}' name='{$value['name']}' value='{$value['value']}' {$required} >
                        </div>
                    </div>
                    ";
                    break;


                case '3': // double
                    $str .= "
                    <div class='form-group'>
                        <label class='control-label col-md-2' for='general-text' for='{$value['name']}' >{$value['label']}</label>
                        <div class='col-md-6'>
                            <input type='text' id='{$value['name']}' class='form-control double' placeholder='{$value['label']}' name='{$value['name']}' value='{$value['value']}' {$required} data-parsley-pattern='^[0-9]+(\\.[0-9]+)?$' >
                        </div>
                    </div>
                    ";
                    break;


                case '4': // TextArea
                    $str .= "
                    <div class='form-group'>
                        <label class='control-label col-md-2' for='{$value['name']}' >{$value['label']}</label>
                        <div class='col-md-6'>
                            <textarea class='text-input textarea ckeditor' id='{$value['name']}' name='{$value['name']}' {$required} >{$value['value']}</textarea>
                        </div>
                    </div>
                    ";
                    break;


                case '5': // select
                    $str .= "
                    <div class='form-group'>
                        <label class='control-label col-md-2' for='general-text' for='{$value['name']}' >{$value['label']}</label>
                        <div class='col-md-6'>
                            <select id='{$value['name']}' class='form-control' name='{$value['name']}' {$required} >
                                <option value=''>Selecione uma opção</option>
                                ";

                    foreach ( $value['options'] as $k => $v )
                        $str .= "<option value='{$v['value']}' " . ( $value['value'] == $v['value'] ? ' selected="selected" ' : '' ) . " >{$v['name']}</option>";

                    $str .= "
                            </select>
                        </div>
                    </div>
                    ";
                    break;


                case '6': // radio
                    $str .= "
                    <div class='form-group'>
                        <label class='control-label col-md-2' for='general-text' for='{$value['name']}' >{$value['label']}</label>
                        <div class='col-md-6'>
                            ";

                    foreach ( $value['options'] as $k => $v )
                        $str .= "
                            <div class='radio radio-inline'>
                                <input  id='{$value['name']}-{$k}' value='{$v['value']}' name='{$value['name']}' type='radio' " . ( ( $v['value'] == $value['value'] ) ? 'checked="checked"' : '' ) . " >
                                <label for='{$value['name']}-{$k}' >{$v['name']}</label>
                            </div>
                        ";

                    $str .= '
                        </div>
                    </div>
                    ';
                    break;


                case '7': // checkbox
                    $str .= "
                    <div class='form-group'>
                        <label class='control-label col-md-2' for='general-text' for='{$value['name']}' >{$value['label']}</label>
                        <div class='col-md-6'>
                            ";

                            foreach ( $value['options'] as $k => $v )
                                $str .= "
                                    <div class='checkbox checkbox-inline'>
                                        <input  id='{$value['name']}-{$k}' value='{$v['value']}' name='{$value['name']}[]' type='checkbox' " . ( in_array( $v['value'], explode( ';', $value['value'] ) ) ? 'checked="checked"' : '' ) . " >
                                        <label for='{$value['name']}-{$k}' >{$v['name']}</label>
                                    </div>
                                ";

                    $str .= '
                        </div>
                    </div>
                    ';
                    break;


                case '8': // image
                    if( !isset( $value['value'] ) )
                        $btn = "<input type='file' class='form-control' name='{$value['name']}' " . ( ( $acao=='editar' ) ? '' : $required ) . " >";
                    else
                        $btn = "<div class='input-group'>
                                <input type='file' class='form-control' name='{$value['name']}' " . ( ( $acao=='editar' ) ? '' : $required ) . " >
                                <span class='input-group-btn'>
                                    <a class='btn btn-default' href='" . Yii::getAlias( '@web/uploads/files/' . $value['value'] ) . "' target='_blank' ><i class='fa fa-download'></i></a>
                                </span>
                            </div>";
                    $str .= "
                        <div class='form-group'>
                            <label class='control-label col-md-2' for='general-text' for='{$value['name']}' >{$value['label']}</label>
                            <div class='col-md-6'>
                                {$btn}
                            </div>
                        </div>
                        ";
                    break;


                case '9': // upload
                    if( !isset( $value['value'] ) )
                        $btn = "<input type='file' class='form-control' name='{$value['name']}' " . ( ( $acao=='editar' ) ? '' : $required ) . ' >';
                    else
                        $btn = "<div class='input-group'>
                                <input type='file' class='form-control' name='{$value['name']}' " . ( ( $acao=='editar' ) ? '' : $required ) . " >
                                <span class='input-group-btn'>
                                    <a class='btn btn-default' href='" . Yii::getAlias( '@web/uploads/files/' . $value['value'] ) . "' target='_blank' ><i class='fa fa-download'></i></a>
                                </span>
                            </div>";
                    $str .= "
                        <div class='form-group' >
                            <label class='control-label col-md-2' for='general-text' for='{$value['name']}' >{$value['label']}</label>
                            <div class='col-md-6'>
                                {$btn}
                            </div>
                        </div>
                        ";
                    break;


            } // switch ( $value['type'] )


        } // foreach ($fields as $key => $value)


        $str .= '<div class="form-group form-actions">
                        <div class="col-md-10 col-md-offset-2">
                            <button type="submit" class="btn btn-success"><i class="icon-ok"></i> Salvar</button>
                        </div>
                    </div>';

        $str .= '</form>';


        return $str;

    }





    /**
     * Generate Table
     *
     * @param array $data
     * @param array $fieldLabels
     * @param array $area
     * @return string
     */
    public function listGen( $data, $fieldLabels, $area )
    {
        $labels = [];

        $str = '<table id="datatable" class="table table-bordered table-hover">';

            $str .= '<thead><tr>';

            foreach ($fieldLabels as $key => $value)
            {
                $str      .= "<th>{$value['label']}</th>";
                $labels[]  = $value['name'];
            }

            $str .= '<th>Última Atualização</th>';

            if( $area['gallery'] )
                $str .= '<th>Galeria</th>';

            $str .= '<th>Publicado</th>';
            $str .= '<th>Ações</th>';

            $str .= '</tr></thead>';

            $str .= '<tbody>';

            foreach( $data as $k => $v )
            {
                $str .= '<tr>';

                foreach ($labels as $key => $value)
                    $str .= "<td>{$v['fields'][$value]}</td>";

                $str .= '<td style=" width: 15%; " >' . date( 'd/m/Y H:i:s', strtotime( $v['fields']['last_update'] ) ) . '</td>';

                if( $area['gallery'] )
                    $str .= '<td style=" width: 10%; " ><a class="text-info" href="' . Yii::getAlias('@web/admin/sca/' . $area['url'] . '/galeria/' . $v['id'] ) . '"><i class="fa fa-file-image-o"></i></a></td>';

                $str .= '<td style=" width: 10%; " >' . ( ( $v['published'] ) ?
                            ' <a class="text-success" href="' . Yii::getAlias('@web/admin/sca/' . $area['url'] . '/remover-publicacao/' . $v['id'] ) . '"><i class="fa fa-check"></i></a>' :
                            ' <a class="text-danger" href="'  . Yii::getAlias('@web/admin/sca/' . $area['url'] . '/publicar/'           . $v['id'] ) . '"><i class="fa fa-times"></i></a>'  ) .
                        '</td>';

                $str .= '<td style=" width: 10%; " >';
                    $str .= ' <a class="text-info" href="'          . Yii::getAlias('@web/admin/sca/' . $area['url'] . '/editar/'  . $v['id'] ) . '"><i class="fa fa-pencil"></i></a> ';
                    $str .= ' <a class="text-danger delete" href="' . Yii::getAlias('@web/admin/sca/' . $area['url'] . '/excluir/' . $v['id'] ) . '"><i class="fa fa-trash"></i></a> ';
                $str .= '</td>';

                $str .= '</tr>';

            } // foreach( $data as $k => $v )

            $str .= '</tbody>';

        $str .= '</table>';

        return $str;

    }



    /**
     * string2url
     *
     * @param string $string
     * @return string
     */
    public function urlAmigavel($string)
    {
        $url = str_replace(' ', '-', urldecode($string));
        $url = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($url));

        $url = str_replace('_', '-', $url);
        $url = str_replace('---', '-', $url);
        $url = str_replace('--', '-', $url);
        $url = str_replace(',', '', $url);

        return strtolower($url);
    }



}

