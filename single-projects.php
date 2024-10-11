<?php get_header(); ?>

<main class="container py-5 single-projects">
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-4"><?php the_title(); ?></h1>
            <div class="card mb-4">
                <div class="card-body">
                    <?php while (have_posts()) : the_post(); ?>
                        <article class="project-details">
                            <?php
                            // Get custom fields
                            $project_name = get_field('project_name');
                            $project_description = get_field('project_description');
                            $project_start_date = get_field('project_start_date');
                            $project_end_date = get_field('project_end_date');
                            $project_url = get_field('project_url');
                            ?>

                            <div class="project-meta mb-3">
                                <?php if ($project_name) : ?>
                                    <h2 class="project-title">Project: <?php echo esc_html($project_name); ?></h2>
                                <?php endif; ?>
                                <ul class="list-unstyled">
                                    <?php if ($project_start_date && $project_end_date) : ?>
                                        <li><strong>Start Date:</strong> <?php echo esc_html($project_start_date); ?></li>
                                        <li><strong>End Date:</strong> <?php echo esc_html($project_end_date); ?></li>
                                    <?php endif; ?>
                                    <?php if ($project_url) : ?>
                                        <li><strong>Project URL:</strong> <a href="<?php echo esc_url($project_url); ?>" target="_blank"><?php echo esc_html($project_url); ?></a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <?php if ($project_description) : ?>
                                <div class="project-description mb-4">
                                    <h4>Description</h4>
                                    <p><?php echo wp_kses_post($project_description); ?></p>
                                </div>
                            <?php endif; ?>

                        </article>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <?php if (is_active_sidebar('sidebar-1')) : ?>
                <aside class="project-sidebar">
                    <?php dynamic_sidebar('sidebar-1'); ?>
                </aside>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
