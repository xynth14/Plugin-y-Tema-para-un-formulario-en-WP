<?php
    global $wpdb;
    

    if(isset($_GET['id'])){
        if(!empty($_GET['id'])){
            $query = "SELECT cf.idCliForm, cf.idDetForm, cf.idForm, f.nomForm, cf.label, cf.datos, cf.codigo, cf.fechaRegistro FROM {$wpdb->prefix}cliformularios as cf INNER JOIN {$wpdb->prefix}formularios f ON cf.idForm = f.idForm WHERE f.idForm=".$_GET['id']." GROUP BY cf.codigo ORDER BY cf.fechaRegistro DESC;";
            $lista_contactos = $wpdb->get_results($query, ARRAY_A);
        }
    }else{
        $query = "SELECT cf.idCliForm, cf.idDetForm, cf.idForm, f.nomForm, cf.label, cf.datos, cf.codigo, cf.fechaRegistro FROM {$wpdb->prefix}cliformularios as cf INNER JOIN {$wpdb->prefix}formularios f ON cf.idForm = f.idForm GROUP by codigo ORDER BY cf.fechaRegistro, f.nomForm DESC;";
        $lista_contactos = $wpdb->get_results($query, ARRAY_A);
    }

    if(empty($lista_contactos)){
        $lista_contactos = array();
    }
?>
<div class="wrap">
    <?php
    echo '<h1 class="wp-heading-inline">'.get_admin_page_title().'</h1>';
    ?>
    <br><br><br>

    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <th>Codigo</th>
            <th>Nombre del Formulario</th>
            <th>Info</th>
            <th>Fecha Registro</th>
        </thead>
        <tbody id="the-list">
            <?php
                foreach($lista_contactos as  $key => $value){
                    $idCliForm = $value['idCliForm'];
                    $codigo = $value['codigo'];
                    $nomForm = $value['nomForm'];
                    $datos = $value['datos'];
                    $fechaRegistro = $value['fechaRegistro'];

                    echo "<tr>
                            <td><a href='' id='$idCliForm' data-form='$nomForm' class='detCodigo'>$codigo</a></td>
                            <td>$nomForm</td>
                            <td>$datos</td>
                            <td>
                                $fechaRegistro
                            </td>
                        </tr>";
                }
            ?>
        </tbody>
    </table>
</div>


<!-- Modal -->
<div class="modal fade" id="modalDetalleContacto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="nomFomullario"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="cerrarModal">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row" id="datosInfo">
                          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger cerrarModal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>