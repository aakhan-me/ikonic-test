<?php get_header(); ?>

<main class="container py-5">
    <h1>Projects</h1>

    <!-- Filter Form -->
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo isset($_GET['start_date']) ? esc_attr($_GET['start_date']) : ''; ?>">
            </div>
            <div class="col-md-4">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo isset($_GET['end_date']) ? esc_attr($_GET['end_date']) : ''; ?>">
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="<?php echo get_post_type_archive_link('projects'); ?>" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <?php if (have_posts()) : ?>
        <div class="row">
            <?php while (have_posts()) : the_post(); ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (has_post_thumbnail()) : ?>
                            <img class="card-img-top" src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php the_title(); ?></h5>
                            <p class="card-text">
                                <?php
                                $start_date = get_field('project_start_date');
                                $end_date = get_field('project_end_date');
                                if ($start_date && $end_date) {
                                    echo '<strong>Start Date:</strong> ' . esc_html($start_date) . '<br>';
                                    echo '<strong>End Date:</strong> ' . esc_html($end_date) . '<br>';
                                }
                                ?>
                            </p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">View Project</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="pagination">
            <?php the_posts_pagination(); ?>
        </div>
    <?php else : ?>
        <p>No projects found for the selected date range.</p>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
