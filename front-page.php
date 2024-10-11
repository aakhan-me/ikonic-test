<?php
/* Template Name: Home */
get_header();
?>

<main>
    <!-- Hero Section with Dynamic Slider -->
    <section class="hero-slider">
        <div id="heroCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php
                $args = array(
                    'post_type' => 'projects',
                    'posts_per_page' => 3,
                );
                $projects_query = new WP_Query($args);
                $is_first = true; // To keep track of the first slide
                if ($projects_query->have_posts()) :
                    while ($projects_query->have_posts()) : $projects_query->the_post();
                        $project_start_date = get_field('project_start_date');
                        $project_end_date = get_field('project_end_date');
                        ?>
                        <div class="carousel-item <?php echo $is_first ? 'active' : ''; ?>">
                            <div class="carousel-caption d-block">
                                <h5><?php the_title(); ?></h5>
                                <?php the_excerpt(); ?>
                                <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-primary">View Project</a>
                                <p class="text-muted">
                                    <strong>Start Date:</strong> <?php echo esc_html($project_start_date); ?> |
                                    <strong>End Date:</strong> <?php echo esc_html($project_end_date); ?>
                                </p>
                            </div>
                        </div>
                        <?php
                        $is_first = false;
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="carousel-item active">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>No Projects Available</h5>
                            <p>Please check back later for updates.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Carousel controls -->
            <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </section>
    <!-- Call to Action Section -->
    <section class="cta bg-light py-5">
        <div class="container text-center">
            <?php while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; ?>
        </div>
    </section>
    <!-- Call to Action Section -->
    <section class="cta bg-light py-5">
        <div class="container text-center">
            <h2>Ready to Start Your Project?</h2>
            <p class="lead">Get in touch with us today to discuss your ideas.</p>
            <a href="<?php echo site_url('/contact'); ?>" class="btn btn-primary btn-lg">Contact Us</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
