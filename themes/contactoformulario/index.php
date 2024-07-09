<?php get_header(); ?>

<?php if(have_posts()) : while (have_posts()) : the_post(); ?>
<main>
    <div class="modal act">
        <div class="modal_main">
            <?php the_content(); ?>
        </div>
    </div>
</main>
<?php endwhile; else: ?>
    <h1>No se ingreso formulario de contacto.</h1>
<?php endif; ?>