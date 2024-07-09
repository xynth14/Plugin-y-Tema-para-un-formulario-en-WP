<?php
    global $wpdb;
    date_default_timezone_set('America/Lima');

    $tablaForm = "{$wpdb->prefix}formularios";
    $tablaFormDetalle = "{$wpdb->prefix}detformularios";

    if(isset($_POST['btnGuardar'])){
        
        $nomForm = $_POST['txtNombre'];

        $query = "SELECT idForm FROM $tablaForm ORDER BY idForm DESC limit 1";
        $rs = $wpdb->get_results($query, ARRAY_A);

        if(empty($rs)){
            $proximoId = 1;
        }else{
            $proximoId = $rs[0]['idForm']+1;
        }
        
        $shortCod = "[Form id='$proximoId']";

        $datos = [
            'idForm' => null,
            'nomForm' => $nomForm,
            'shortCode' => $shortCod,
            'fechaRegistro' => date("Y-m-d H:i:s")
        ];

        $respuesta = $wpdb->insert($tablaForm, $datos);

        if($respuesta){
            $listaLabel = $_POST['name'];
            $i = 0;

            foreach($listaLabel as $key => $value){
                $tipo = $_POST['type'][$i];
                $datos2 = [
                    'idDetForm' => null,
                    'idForm' => $proximoId,
                    'elementos' => $value,
                    'tipo' => $tipo,
                    'fechaRegistro' => date("Y-m-d H:i:s")
                ];

                $wpdb->insert($tablaFormDetalle, $datos2);
                $i++;
            }
        }
    }

    $query = "SELECT * FROM {$wpdb->prefix}formularios";
    $lista_formularios = $wpdb->get_results($query, ARRAY_A);
    if(empty($lista_formularios)){
        $lista_formularios = array();
    }
?>

<div class="wrap">
    <?php
    echo '<h1 class="wp-heading-inline">'.get_admin_page_title().'</h1>';
    ?>
    <a id="btnNuevo" class="page-title-action">Añadir nueva</a>
    <br><br><br>

    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <th>Nombre Formulario</th>
            <th>Shortcode</th>
            <th>Acciones</th>
        </thead>
        <tbody id="the-list">
            <?php
                foreach($lista_formularios as  $key => $value){
                    $id = $value['idForm'];
                    $nomForm = $value['nomForm'];
                    $shortCode = $value['shortCode'];
                    echo "<tr>
                            <td>$nomForm</td>
                            <td>$shortCode</td>
                            <td>
                                <a href='?page=sp_menu_list_contact&id=".$id."' class='page-title-action'>Ver detalle</a>
                                <a data-id='$id' class='page-title-action btnBorrar'>Borrar</a>
                            </td>
                        </tr>";
                }
            ?>
        </tbody>
    </table>
</div>


<!-- Modal -->
<div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Nuevo Formulario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post">   
        <div class="modal-body">
            <div class="form-group row">
               
                <label for="txtNombre" class="col-sm-5 col-form-label">Nombre del Formulario:</label>
               
                <div class="col-sm-7">
                    <input type="text" id="txtNombre" name="txtNombre" style="width: 100%" >
                </div>                
            </div>
            <hr>            
            <h5>Agregar elementos al Formulario</h5>
            <hr>
            <br>
            <table id="camposDinamicos">
                <tr>
                    <td>
                        <label for="txtLabel" class="col-form-label" style="margin-right: 7px;">Label1: </label>
                    </td>
                    <td>
                        <input type="text" name="name[]" id="name" class="form-control name_list">
                    </td>
                    <td>
                        <select name="type[]" id="type"class="form-control type_list" style="margin-left: 7px;">
                            <option value="text">[text]</option>
                            <option value="textarea">[textarea]</option>
                            <option value="email">[email]</option>
                            <option value="tel">[tel]</option>
                            <option value="number">[number]</option>
                        </select>
                    </td>
                    <td>
                        <button name="add" id="add" class="btn btn-success" style="margin-left: 15px;">Agregar más</button>
                    </td>
                </tr>
            </table>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" name="btnGuardar" id="btnGuardar">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--modal: pregunta si desea eliminar un registro-->
<div class="modal fade" id="confirm-delete" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar formulario</h4>
            </div>
            <div class="modal-body">
                <label>¿Estás seguro de eliminar el formulario?</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btnBorrar" data-dismiss="modal">Sí</button>
                <button class="btn btn-danger" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>