<?php

/*function contactoformulario_styles(){
    wp_enqueue_style('style', get_stylesheet_uri());
}

add_action('wp_enqueue_scripts', 'contactoformulario_styles');
*/

/**Nueva Navegacion**/
register_nav_menus( array(
        'menu_principal' => 'Menu Principal',
));

/**sidebar**/
/*register_sidebar(array(
    'name' => 'Sidebar',
    'before_widget' => '<section class="widget">',
    'after_widget' => '</section>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
));*/

?>