<?php get_header(); ?>

<main class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h1><?php echo esc_html(get_the_title()); ?></h1>
            <div>
                <?php while (have_posts()) : the_post(); ?>
                    <article><?php the_content(); ?></article>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
