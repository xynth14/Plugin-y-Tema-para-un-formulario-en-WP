<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Contacto</title>
	<link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();  ?>">
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();  ?>/css/main.css">
	<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
	<script src="<?php echo get_stylesheet_directory_uri();  ?>/js/main.js"></script>
</head>
<body>
    <header>
		<div class="header_main">
        
            <ul>
                <li>
                    <?php
                        wp_nav_menu(array(
                            'container' => false,
                            'items_wrap' => '<a id="open_modal">%3$s</a>',
                            'theme_location' => 'menu_principal'
                        ));
                    ?>
                </li>
            </ul>
     
                
            
		</div>
	</header>