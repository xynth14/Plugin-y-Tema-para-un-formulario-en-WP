<?php

class codigoCorto{

    public function obtenerFormulario($idForm){
        global $wpdb;
        $tablaForm = "{$wpdb->prefix}formularios";

        $query = "SELECT * FROM $tablaForm WHERE idForm = '$idForm'";
        $datos = $wpdb->get_results($query, ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos[0];
    }

    public function obtenerFormDetalle($idForm){
        global $wpdb;
        $tablaFormDetalle = "{$wpdb->prefix}detformularios";

        $query = "SELECT * FROM $tablaFormDetalle WHERE idForm = '$idForm'";
        $datos = $wpdb->get_results($query, ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos;
    }

    public function formOpen($titulo){
        $html = "
        <form method='POST' class='form'>
            <div class='form_main'>
                <div class='close-modal' id='close_modal'>
                    <a></a>
                </div>
                <div class='form_head'>
                    <h1>$titulo</h1>
                </div>
                
                <div class='form_content'>
        ";
        return $html;
    }

    public function formClose(){
        $html = "
                    <div class='box_btn'>
                        <input type='submit' id='btnGuardar' name='btnGuardar' class='page-title-action' value='ENVIAR'>
                    </div>
                </div>
            </div>
        </form>
        ";
        return $html;
    }

    function fromInput($idDetForm, $elementos, $tipo){

        if($tipo == 'textarea'){
            $html = "
                <div class='box'>
                    <i class='icon-comment-o'></i>
                    <textarea name='$idDetForm' id='' required placeholder='Ingresar $elementos'></textarea>
                </div>
            ";
        }elseif($tipo == 'text'){
            $html = "
                <div class='box'>
                    <i class='icon-user-o'></i>
                    <input type='text' name='$idDetForm' id='$idDetForm' required placeholder='Ingresa $elementos'>
                </div>  
            ";
        }elseif($tipo == 'email'){
            $html = "
                <div class='box'>
                    <i class='icon-mail_outline'></i>
                    <input type='email' name='$idDetForm' id='$idDetForm' required placeholder='ejemplo@email.com'>
                </div>  
            ";
        }elseif($tipo == 'number'){
            $html = "
                <div class='box'>
                    <i class='icon-phone_android'></i>
                    <input type='number' name='$idDetForm' id='$idDetForm' required placeholder='51999000999'>
                </div>  
            ";
        }elseif($tipo == 'tel'){
            $html = "
                <div class='box'>
                    <i class='icon-phone_android'></i>
                    <input type='tel' name='$idDetForm' id='$idDetForm' required placeholder='51999000999'>
                </div>  
            ";
        }

        
        return $html;
    }

    function armarFormularioContacto($idForm){
        $form = $this->obtenerFormulario($idForm);
        $nomForm = $form['nomForm'];

        //Obtener el Detalle del Formulario => elementos, tipo del elemento
        $detFormulario = "";
        $listarDetFormularios = $this->obtenerFormDetalle($idForm);
        foreach ($listarDetFormularios as $key => $value) {
            $idDetForm = $value['idDetForm'];
            $elementos = $value['elementos'];
            $tipo = $value['tipo'];
            $idFormulario = $value['idForm'];

            if($idFormulario == $idForm){
                $detFormulario .= $this->fromInput($idDetForm, $elementos, $tipo);
            }
        }

        $html = $this->formOpen($nomForm);
        $html .= $detFormulario;
        $html .= $this->formClose();

        return $html;

    }

    function guardarCliForm($datos){
        global $wpdb;
        $tabla = "{$wpdb->prefix}cliformularios";
        $rs = $wpdb->insert($tabla, $datos);
        return $rs;

    }

    function obtenerClientesContacto(){
        global $wpdb;
        $tabla = "{$wpdb->prefix}cliformularios";
        $rs = $wpdb->get_results( "SELECT * FROM $tabla" );
        return $rs;
    }

    function detalleClientesContacto($codigo){
        global $wpdb;
        $tabla = "{$wpdb->prefix}cliformularios";

        $query = "SELECT * FROM $tabla where codigo = '$codigo'";
        $datos = $wpdb->get_results( $query, ARRAY_A);
        if(empty($datos)){
            $datos = array();
        }
        return $datos;
    }


}

?>