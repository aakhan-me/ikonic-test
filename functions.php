<?php
/* 
 * Enqueue Theme CSS Files and Bootstrap 
 * This function enqueues the necessary CSS and JavaScript files for the theme,
 * including Bootstrap and the main theme stylesheet.
 */
function custom_theme_enqueue_assets() {
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_style('custom-theme-style', get_stylesheet_uri());
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_theme_enqueue_assets');

/* 
 * Custom Post Type Registration 
 * This function registers a new custom post type called "Projects".
 * It defines various labels and settings for the post type.
 */
if ( ! function_exists('ikonic_projects_function') ) {
    function ikonic_projects_function() {
        $labels = array(
            'name'                  => _x( 'Projects', 'Post Type General Name', 'ikonic' ),
            'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'ikonic' ),
            'menu_name'             => __( 'Projects', 'ikonic' ),
            'name_admin_bar'        => __( 'Projects', 'ikonic' ),
            'archives'              => __( 'Item Archives', 'ikonic' ),
            'attributes'            => __( 'Item Attributes', 'ikonic' ),
            'parent_item_colon'     => __( 'Parent Item:', 'ikonic' ),
            'all_items'             => __( 'All Items', 'ikonic' ),
            'add_new_item'          => __( 'Add New Item', 'ikonic' ),
            'add_new'               => __( 'Add New', 'ikonic' ),
            'new_item'              => __( 'New Item', 'ikonic' ),
            'edit_item'             => __( 'Edit Item', 'ikonic' ),
            'update_item'           => __( 'Update Item', 'ikonic' ),
            'view_item'             => __( 'View Item', 'ikonic' ),
            'view_items'            => __( 'View Items', 'ikonic' ),
            'search_items'          => __( 'Search Item', 'ikonic' ),
            'not_found'             => __( 'Not found', 'ikonic' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'ikonic' ),
            'featured_image'        => __( 'Featured Image', 'ikonic' ),
            'set_featured_image'    => __( 'Set featured image', 'ikonic' ),
            'remove_featured_image' => __( 'Remove featured image', 'ikonic' ),
            'use_featured_image'    => __( 'Use as featured image', 'ikonic' ),
            'insert_into_item'      => __( 'Insert into item', 'ikonic' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'ikonic' ),
            'items_list'            => __( 'Items list', 'ikonic' ),
            'items_list_navigation' => __( 'Items list navigation', 'ikonic' ),
            'filter_items_list'     => __( 'Filter items list', 'ikonic' ),
        );

        $rewrite = array(
            'slug'                  => 'project',
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => true,
        );

        $args = array(
            'label'                 => __( 'Project', 'ikonic' ),
            'description'           => __( 'Post Type Description', 'ikonic' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail' ),
            'taxonomies'            => array( 'category', 'post_tag' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-portfolio',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => $rewrite,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
        );
        
        register_post_type( 'projects', $args );
    }
    add_action( 'init', 'ikonic_projects_function', 0 );
}

/* 
 * Search Filter for Projects 
 * This function modifies the main query on the projects archive page to filter projects 
 * based on the provided start and end date query parameters.
 */
function filter_projects_by_date($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('projects')) {
        $meta_query = [];

        if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
            $meta_query[] = [
                'key' => 'project_start_date',
                'value' => sanitize_text_field($_GET['start_date']),
                'compare' => '>=',
                'type' => 'DATE',
            ];
        }

        if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
            $meta_query[] = [
                'key' => 'project_end_date',
                'value' => sanitize_text_field($_GET['end_date']),
                'compare' => '<=',
                'type' => 'DATE',
            ];
        }

        if (!empty($meta_query)) {
            $query->set('meta_query', $meta_query);
        }
    }
}
add_action('pre_get_posts', 'filter_projects_by_date');

/* 
 * Register a new route for the REST API 
 * This function registers a custom REST API route to fetch project data.
 */
function register_projects_api_route() {
    register_rest_route('custom-api/v1', '/projects', array(
        'methods'  => 'GET',
        'callback' => 'get_projects_data',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'register_projects_api_route');

/* 
 * API End point for Projects 
 * This function retrieves project data for the registered REST API endpoint.
 */
function get_projects_data(WP_REST_Request $request) {
    $args = array(
        'post_type'      => 'projects',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    );

    $projects = new WP_Query($args);
    $projects_data = [];

    if ($projects->have_posts()) {
        while ($projects->have_posts()) {
            $projects->the_post();
            $project_start_date = get_field('project_start_date');
            $project_end_date = get_field('project_end_date');

            $projects_data[] = array(
                'title' => get_the_title(),
                'url'   => esc_url(get_permalink()),
                'start_date' => esc_html($project_start_date),
                'end_date'   => esc_html($project_end_date),
            );
        }
        wp_reset_postdata();
    } else {
        return new WP_Error('no_projects', 'No projects found', array('status' => 404));
    }

    return new WP_REST_Response($projects_data, 200);
}
?>
