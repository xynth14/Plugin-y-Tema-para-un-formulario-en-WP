<?php
/**
* Plugin Name: My Custom Form
* Plugin URI: https://cuevacorporacion.com/
* Description: Plugin de Formulario de Contacto simple pero eficaz donde almacenamos los datos obteniendo Leads.
* Version: 0.0.1
* Author: Cueva Corp - xynth14
* Author URI: https://cynthiaquispemarin.web.app/
**/
require_once dirname(__FILE__) . '/clases/codigoCorto.class.php';
function Activar(){    
    global $wpdb;
    //Creamos tabla para Formularios
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}formularios(
        `idForm` INT NOT NULL AUTO_INCREMENT,
        `nomForm` varChar(100) NULL, 
        `shortCode` varchar(50) NULL,
        `fechaRegistro` DATETIME, 
        PRIMARY KEY (`idForm`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1;";
    $wpdb->query($sql);

    //Creamos tabla para el detalle del Formulario
    $sql2 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}detFormularios(
        `idDetForm` INT NOT NULL AUTO_INCREMENT,
        `idForm` INT NOT NULL,
        `elementos` varChar(100) NULL, 
        `tipo` varchar(100) NULL,
        `fechaRegistro` DATETIME, 
        PRIMARY KEY (`idDetForm`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1;";
    $wpdb->query($sql2);

    //Creamos tabla para los clientes de Contacto
    $sql3 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cliFormularios(
        `idCliForm` INT NOT NULL AUTO_INCREMENT,
        `idDetForm` INT NOT NULL,
        `idForm` INT NOT NULL,
        `label` varChar(150) NULL, 
        `datos` varchar(150) NULL,
        `codigo` varchar(50) NULL,
        `fechaRegistro` DATETIME, 
        PRIMARY KEY (`idCliForm`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1;";
    $wpdb->query($sql3);
}

function Desactivar(){
    flush_rewrite_rules();
}

register_activation_hook(__FILE__,'Activar');
register_deactivation_hook(__FILE__,'Desactivar');

define( 'MYCUSTOMFORM_PLUGIN_DIR', plugin_dir_path(__FILE__) );
define( 'MYCUSTOMFORM_PLUGIN_URL', plugin_dir_url(__FILE__) );

add_action('admin_menu','crearMenu');

function crearMenu(){
    add_menu_page(
        'My Custom Form', //Titulo de la pagina.
        'My Custon Form',//Titulo del menu
        'manage_options', //Permisos al usuario admin
        'sp_menu', //slug
        //plugin_dir_path(__FILE__).'admin/listarLeads.php', //slug
        'mostrarContenido',
        MYCUSTOMFORM_PLUGIN_URL .'admin/img/icon.png',
        '2'
    );

    add_submenu_page(
        'sp_menu', //parent slug
        'List Contacts', //Titulo de la pagina
        'List Contacts', //Titulo del Sub Menu
        'manage_options',
        'sp_menu_list_contact',
        'listarContactos'
    );
}

function mostrarContenido(){
    require_once( MYCUSTOMFORM_PLUGIN_DIR . 'admin/lista_formularios.php' );
}

function listarContactos(){
    require_once( MYCUSTOMFORM_PLUGIN_DIR . 'admin/listarClientesContacto.php' );
}

//encolar bootstrap
function encolarBootstrapJS($hook){
    echo "<script>console.log('$hook')</script>";
    if($hook != "toplevel_page_sp_menu" && $hook != "my-custon-form_page_sp_menu_list_contact"){
        return;
    }
    wp_enqueue_script('bootstrapJs', MYCUSTOMFORM_PLUGIN_URL . 'admin/bootstrap/js/bootstrap.min.js', array('jquery'), '4.0.0', true);
}
add_action('admin_enqueue_scripts', 'encolarBootstrapJS');

function encolarBootstrapCSS($hook){    
    if($hook != "toplevel_page_sp_menu" && $hook != "my-custon-form_page_sp_menu_list_contact"){
        return;
    }
    wp_enqueue_style('bootstrapCSS', MYCUSTOMFORM_PLUGIN_URL . 'admin/bootstrap/css/bootstrap.min.css');
}
add_action('admin_enqueue_scripts', 'encolarBootstrapCSS');

//encolar JS propio
function encolarJS($hook){
    if($hook != "toplevel_page_sp_menu" && $hook != "my-custon-form_page_sp_menu_list_contact"){
        return;
    }
    wp_enqueue_script('jsExterno', MYCUSTOMFORM_PLUGIN_URL . 'admin/js/lista_formularios.js', array('jquery'), '0.0.1', true);
    wp_localize_script('jsExterno', 'solicitudAjax', [
        'url' => admin_url('admin-ajax.php'),
        'seguridad' => wp_create_nonce('seg')
    ]);
}
add_action('admin_enqueue_scripts', 'encolarJS');

//eliminar formulario - ajax
function eliminarFormulario(){
    $nonce = $_POST['nonce'];
    if(!wp_verify_nonce($nonce, 'seg')){
        die('no tiene permisos para ejecutar el ajax');
    }

    $id = $_POST['id'];
    global $wpdb;
    $tablaForm = "{$wpdb->prefix}formularios";
    $tablaFormDetalle = "{$wpdb->prefix}detformularios";

    $wpdb->delete($tablaFormDetalle, array('idForm' => $id));
    $wpdb->delete($tablaForm, array('idForm' => $id));

    return true;
    
}
add_action('wp_ajax_eliminapeticion', 'eliminarFormulario');

//Login WP 
add_action( 'rest_api_init', 'autenticacion');

function autenticacion() {
    register_rest_route(
        'myCustomForm/v1',
        'login', 
        array(
            'methods' => 'POST',
            'callback' => 'login',
            'permission_callback' => 'wp_learn_check_permissions'
        )
    );
}

//http://localhost/wordpress/wp-json/api/login

function login( WP_REST_Request $request ){
    $arr_request = json_decode( $request->get_body() );
    if( ! empty( $arr_request->email ) && ! empty( $arr_request->password) ){
        //retornamoss el idUsuario y el email
        $user = get_user_by( 'email', $arr_request->email );

        if( ! $user ){
            return [
                'status' => 'error',
                'message' => 'Email incorrecto',
            ];
        }

        //
        if( ! wp_check_password( $arr_request->password, $user->user_pass, $user->ID) ){
            return [
                'status' => 'error',
                'message' => 'Password incorrecto',
            ];

        }

        return [
            'status' => 'success',
            'message' => 'Credenciales del usuario es correcto',
        ];
    }else{
        return [
            'status' => 'error',
            'message' => 'Ingrese email y password',
        ];
    }
}

//shortcode
function imprimirShortCode($atts){
    ob_start();
    $_short = new codigoCorto;
    //obtener el id por parametro
    $idForm = $atts['id'];
    
    //programar el guardado
    if(isset($_POST['btnGuardar'])){
        $listaElementos = $_short->obtenerFormDetalle($idForm);
        $codigo = uniqid();
        
        foreach ($listaElementos as $key => $value) {
            $idDetaForm = $value['idDetForm'];

            if(isset($_POST[$idDetaForm])){
                $valortxt = $_POST[$idDetaForm];
                $labeltxt = $value['elementos'];
                $datos = [
                    'idDetForm' => $idDetaForm,
                    'idForm' => $idForm,
                    'label' => $labeltxt,
                    'datos' => $valortxt,
                    'codigo' => $codigo,
                    'fechaRegistro' => date("Y-m-d H:i:s")
                ];
                //var_dump($datos);
                $_short->guardarCliForm($datos);
            }
        }

        $html = "<h2 class='msmEnviado'>Datos enviado con exito!</h2>";
        return $html;        
    }
    //imprime el formulario
    $html = $_short->armarFormularioContacto($idForm);

    ob_get_clean();
    return $html;
}
add_shortcode("Form","imprimirShortCode");

//listar formulario API
add_action( 'rest_api_init', 'registro_clientes_contacto' );
function registro_clientes_contacto(){
    register_rest_route(
        'myCustomForm/v1',
        'formclientes',
        array(
            'methods' => 'GET',
            'callback' => 'obtener_clientes_contacto',
            'permission_callback' => 'wp_learn_check_permissions'
        )
    );

    register_rest_route(
        'myCustomForm/v1',
        'formclientes',
        array(
            'methods' => 'POST',
            'callback' => 'crear_clientes_contacto',
            'permission_callback' => 'wp_learn_check_permissions'
        )
    );

    register_rest_route(
        'myCustomForm/v1',
        'formcliente/(?P<id>\d+)',
        array(
            'methods' => 'GET',
            'callback' => 'detalle_clientes_contacto',
            'permission_callback' => '__return_true'
        )
    );
}

function obtener_clientes_contacto(){
    $_short = new codigoCorto;
    $result = $_short->obtenerClientesContacto();
    return $result;
}

function wp_learn_check_permissions(){
    return current_user_can( 'edit_posts' );
}

function crear_clientes_contacto($request){
    $_short = new codigoCorto;
    $datos = [
        'idDetForm' => $request['idDetForm'],
        'idForm'    => $request['idForm'],
        'label'     => $request['label'],
        'datos'     => $request['datos'],
        'codigo'    => $request['codigo'],
        'fechaRegistro' => date("Y-m-d H:i:s")
    ];
    $result = $_short->guardarCliForm($datos);
    return $result;
}

function detalle_clientes_contacto($request){
    
    $id = $request['id'];

    global $wpdb;
    $tabla = "{$wpdb->prefix}cliformularios";

    $query = "SELECT * FROM $tabla WHERE codigo = ( SELECT codigo FROM $tabla where idCliForm = $id)";
    $datos = $wpdb->get_results( $query, ARRAY_A);
    if(empty($datos)){
        $datos = array();
    }
    return $datos;
}
