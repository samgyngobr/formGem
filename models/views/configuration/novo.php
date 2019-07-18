<?php

$this->title = 'Configuração';

?>

<div class="row">
    <div class="col-sm-12">


        <?php

        if (isset($error))
            echo '<div class="alert alert-danger">' . $error . '</div>';

        if (isset($success))
            echo '<div class="alert alert-success">' . $success . '</div>';

        ?>


        <p><a class="btn btn-primary" href="<?php echo Yii::getAlias('@root') . '/admin/sca/configuration'; ?>">Voltar</a></p>


        <div class="panel panel-custom panel-border">

            <div class="panel-heading">
                <h3 class="panel-title">Novo</h3>
            </div>

            <div class="panel-body">


                <form id="formz" action="" method="post" class="form-horizontal" enctype="multipart/form-data">

                    <div class="form-group">
                        <label class="control-label col-md-2" for="general-text" for="name">Título</label>
                        <div class="col-md-6">
                            <input type="text" id="name" class="form-control" placeholder="Título" name="name" value="<?php echo isset($post['name'])?$post['name']:''; ?>" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2" for="general-text" for="area">Área Pai</label>
                        <div class="col-md-6">
                            <select id="area" class="form-control" name="area" >
                                <option value="">Selecione</option>
                                <?php

                                foreach ( $areas as $key => $value )
                                {
                                    ?><option value="<?= $value['id'] ?>" <?= ( $post['area'] == $value['id'] || $post['area_id'] == $value['id'] ) ? 'selected="selected"' : ''; ?> ><?= $value['label'] ?></option><?php
                                }

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2" for="general-text" >Galeria de Imagens</label>
                        <div class="col-md-6">

                            <div class="radio radio-inline">
                                <input id="gallery0" value="0" name="gallery" <?= ( $post['gallery'] == 0 or $post['gallery'] == null ) ? 'checked="checked"' : ''; ?> type="radio">
                                <label for="gallery0"> Não </label>
                            </div>

                            <div class="radio radio-inline">
                                <input id="gallery1" value="1" name="gallery" <?= ( $post['gallery'] == 1 ) ? 'checked="checked"' : ''; ?> type="radio">
                                <label for="gallery1"> Sim </label>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2" for="general-text" >Tipo</label>
                        <div class="col-md-6">

                            <div class="radio radio-inline">
                                <input id="multiple0" value="0" name="multiple" <?= ( $post['multiple'] == 0 or $post['multiple'] == null ) ? 'checked="checked"' : ''; ?> type="radio">
                                <label for="multiple0"> Item Único </label>
                            </div>

                            <div class="radio radio-inline">
                                <input id="multiple1" value="1" name="multiple" <?= ( $post['multiple'] == 1 ) ? 'checked="checked"' : ''; ?> type="radio">
                                <label for="multiple1"> Coleção </label>
                            </div>

                        </div>
                    </div>


                    <div class="form-group">


                        <label class="control-label col-md-2" for="general-text" >
                            Campos
                        </label>

                        <div class="col-md-6">

                            <p>
                                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#novo" href="#">Adicionar</a>
                            </p>

                            <hr>

                            <table class="table" id="fieldsTable" >

                                <thead>
                                    <tr>
                                        <th>Campo</th>
                                        <th>Tipo</th>
                                        <th>Indice</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody></tbody>

                            </table>

                            <hr>

                        </div> <!-- / .col-md-6 -->
                    </div> <!-- / .form-group -->



                    <!-- Form Buttons -->
                    <div class="form-group form-actions">
                        <div class="col-md-10 col-md-offset-2">
                            <button type="submit" class="btn btn-success"><i class="icon-ok"></i> Salvar</button>
                        </div>
                    </div>
                    <!-- END Form Buttons -->


                    <textarea class="hidden" type="text" name="json" id="jsonField" value="" readonly="" ><?php echo isset($post['json'])?$post['json']:''; ?></textarea>


                </form>



            </div>

        </div>


    </div> <!-- end col -->
</div> <!-- end row -->











<!-- sample modal content -->
<div id="novo" data-id="" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="n" aria-hidden="true">
    <div id="newPre"  class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="camposForm" class="form-horizontal" data-op="add" method="post" action="" >

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="n">Campos</h4>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label class="control-label col-md-2" for="general-text" for="field">Campo</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="Campo" id="field" name="field" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2" for="general-text" >Obrigatorio</label>
                        <div class="col-md-6">

                            <div class="radio radio-inline">
                                <input class="required" id="required0" value="0" name="required" checked="checked" type="radio">
                                <label for="required0"> Não </label>
                            </div>

                            <div class="radio radio-inline">
                                <input class="required" id="required1" value="1" name="required" type="radio">
                                <label for="required1"> Sim </label>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2" for="general-text" >Indice</label>
                        <div class="col-md-6">

                            <div class="radio radio-inline">
                                <input class="index" id="index0" value="0" name="index" checked="checked" type="radio">
                                <label for="index0"> Não </label>
                            </div>

                            <div class="radio radio-inline">
                                <input class="index" id="index1" value="1" name="index" type="radio">
                                <label for="index1"> Sim </label>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2" for="general-text" for="order">Ordem</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" placeholder="Ordem" id="order" name="order" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2" for="type">Tipo: </label>
                        <div class="col-md-8">
                            <select class="form-control type" id="type" name="type" >
                                <option value="">Selecione...</option>
                                <option value="1" <?= ( $post['type'][0] == 1 ) ? 'selected="selected"' : '' ?>>Texto</option>
                                <option value="2" <?= ( $post['type'][0] == 2 ) ? 'selected="selected"' : '' ?>>Inteiro</option>
                                <option value="3" <?= ( $post['type'][0] == 3 ) ? 'selected="selected"' : '' ?>>Double</option>
                                <option value="4" <?= ( $post['type'][0] == 4 ) ? 'selected="selected"' : '' ?>>Textarea</option>
                                <option value="5" <?= ( $post['type'][0] == 5 ) ? 'selected="selected"' : '' ?>>Select</option>
                                <option value="6" <?= ( $post['type'][0] == 6 ) ? 'selected="selected"' : '' ?>>Radio</option>
                                <option value="7" <?= ( $post['type'][0] == 7 ) ? 'selected="selected"' : '' ?>>Checkbox</option>
                                <option value="8" <?= ( $post['type'][0] == 8 ) ? 'selected="selected"' : '' ?>>Imagem</option>
                                <option value="9" <?= ( $post['type'][0] == 9 ) ? 'selected="selected"' : '' ?>>Upload</option>
                            </select>
                        </div>
                        <div class="add_options_button hidden col-md-2">
                            <button type="button" class="btn btn-primary add_field_button"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>

                    <div class='input_fields_wrap'></div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success" name="op" value="novo" >Adicionar</button>
                </div>

            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->














<script type="text/javascript">

    $(document).ready(function () {


        $("input[name='multiple']").on( 'change', function(){

            var thisval = $(this).val();

            if( thisval != '' )
            {
                $("select[name='area']").val('');
            }
        });

        $("input[name='area']").on( 'change', function(){

            var thisval = $(this).val();

            if( thisval != '' )
            {
                $("#multiple0").prop('checked', true);
            }
        });


        var max_fields = 20; //maximum input boxes allowed
        var wrapper    = $(".input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID
        var x          = 1; //initlal text box count

        $(add_button).click(function (e) { //on add input button click

            e.preventDefault();

            if (x < max_fields) //max input box allowed
            {
                x++; //text box increment

                $(wrapper).append('<div class="form-group" ><label class="control-label col-md-2" for="espec">Label: </label><div class="col-md-3"><input type="text" class="form-control" placeholder="Texto Exibido" name="label[' + x + ']" value="" ></div><label class="control-label col-md-2" for="desc">Value: </label><div class="col-md-3"><input type="text" class="form-control" placeholder="Valor Interno" name="value[' + x + ']" value="" ></div><div class="col-md-1" ><a class="btn btn-primary remove_field" href="#"><i class="fa fa-minus"></i></a></div></div>'); //add input box
            }
        });

        $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').parent('div').remove();
            x--;
        })


        if( $('#jsonField').val() !== '' )
        {
            updateTable();
        }


        $('.type').on('change', function(){

            var val = $(this).val();

            if( val == 5 || val == 6 || val == 7 )
            {
                $('.add_options_button').removeClass('hidden');
            }
            else
            {
                $('.add_options_button').addClass('hidden');
                $('.input_fields_wrap').html('');
            }
        });



        $('#camposForm').submit(function(e){

            e.preventDefault();

            if( $(this).data('op') == 'add' )
            {
                add();
            }
            else if( $(this).data('op') == 'edt' )
            {
                edt();
            }

            updateTable();
            clearForm();
        });


        $(document.body).on('click', '.btnrem', function(event) {

            var id         = $(this).data('val');
            var currentVal = JSON.parse( $('#jsonField').val() );

            currentVal.splice(id, 1);

            $('#jsonField').val( JSON.stringify( currentVal ) );

            updateTable();
            clearForm();

        });


        $(document.body).on('click', '.btnedt', function(event) {

            var id         = $(this).data('val');
            var currentVal = JSON.parse( $('#jsonField').val() );
            var item       = currentVal.splice(id, 1);

            $('#novo').modal();
            $('#novo').attr('data-id', id);
            $('#camposForm').data('op','edt');
            $('#field').val( item[0][0]['value'] );
            $('input.required[value="' + item[0][1]['value'] + '"]').attr('checked','checked');
            $('input.index[value="' + item[0][2]['value'] + '"]').attr('checked','checked');
            $('#order').val( item[0][3]['value'] );
            $('#type').val( item[0][4]['value'] );

            if( item[0][4]['value'] == 5 || item[0][4]['value'] == 6 || item[0][4]['value'] == 7 )
            {
                $('.add_options_button').removeClass('hidden');
            }
            else
            {
                $('.add_options_button').addClass('hidden');
            }

            item[0].forEach(function(value, key){

                var posA = strpos( value.name, 'label[' );
                var posB = strpos( value.name, ']' );

                if( posA !== false && posB !== false )
                {
                    var val = value.name.substr( posA+6, posB-(posA+6) );
                }
                else
                {
                    var val = '--';
                }

                if(val.match(/^-{0,1}\d+$/))
                {
                    var v = '';

                    item[0].forEach(function(vl, k){

                        if( vl.name == 'value[' + val + ']' )
                        {
                            v = vl.value;
                        }
                    });

                    $('.input_fields_wrap').append('<div class="form-group" ><label class="control-label col-md-2" for="espec">Label: </label><div class="col-md-3"><input type="text" class="form-control" placeholder="Texto Exibido" name="label[' + val + ']" value="' + value.value +'" ></div><label class="control-label col-md-2" for="desc">Value: </label><div class="col-md-3"><input type="text" class="form-control" placeholder="Valor Interno" name="value[' + val + ']" value="' + v + '" ></div><div class="col-md-1" ><a class="btn btn-primary remove_field" href="#"><i class="fa fa-minus"></i></a></div></div>');
                }
            });

        });


        $('#novo').on('hidden.bs.modal', function (e) {

            $('#novo').attr('data-id', '');

            if( $('#camposForm').data('op') == 'edt' )
            {
                clearForm()
            }
        });


    });


    function add()
    {
        if( $('#jsonField').val() == '' )
        {
            var newCurrent = [];

            newCurrent.push( $('#camposForm').serializeArray() );

            $('#jsonField').val( JSON.stringify( newCurrent ) );
        }
        else
        {
            var currentVal = JSON.parse( $('#jsonField').val() );

            currentVal.push( $('#camposForm').serializeArray() );

            $('#jsonField').val( JSON.stringify( currentVal ) );
        }
    }



    function edt()
    {
        var currentVal = JSON.parse( $('#jsonField').val() );
        var id         = $('#novo').attr('data-id');

        currentVal[ id ] = $('#camposForm').serializeArray();

        $('#jsonField').val( JSON.stringify( currentVal ) );

        $('#novo').modal('hide');
    }



    function clearForm()
    {
        $('#camposForm')[0].reset();
        $('.input_fields_wrap').html('');
        $('.add_options_button').addClass('hidden');
        $('#camposForm').data('op', 'add');
        $('input.required[value="0"]').attr('checked','checked');
        $('input.index[value="0"]').attr('checked','checked');
    }



    function updateTable()
    {
        $('#fieldsTable tbody').html('');

        var currentVal = JSON.parse( $('#jsonField').val() );

        currentVal.forEach(function(a, b){

            var str = '<tr>';

            str += '<td>' + a[0]['value'] + '</td>';

            str += '<td>';

            switch( a[4]['value'] )
            {
                case '1' : str += 'Texto';    break;
                case '2' : str += 'Inteiro';  break;
                case '3' : str += 'Double';   break;
                case '4' : str += 'Textarea'; break;
                case '5' : str += 'Select';   break;
                case '6' : str += 'Radio';    break;
                case '7' : str += 'Checkbox'; break;
                case '8' : str += 'Imagem';   break;
                case '9' : str += 'Upload';   break;
            }

            str += '</td>';

            str += '<td>' + ( ( a[2]['value'] == '1' ) ? '<span class="text-success"><i class="fa fa-circle"></i></span>' : '<span class="text-danger"><i class="fa fa-circle-o"></i></span>' ) + '</td>';

            str += '<td>';
            str += '<a class="btn btn-info btn-xs btnedt" data-val="' + b + '" href="#"><i class="fa fa-pencil"></i></a> ';
            str += '<a class="btn btn-danger btn-xs btnrem" data-val="' + b + '" href="#"><i class="fa fa-remove"></i></a>';
            str += '</td>';

            str += '</tr>';

            $('#fieldsTable tbody').append( str );
        });
    }


    function strpos( haystack, needle, offset )
    {
        var i = (haystack+'').indexOf(needle, (offset || 0));
        return i === -1 ? false : i;
    }


</script>

