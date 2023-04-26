<?php /*
 * Plugin Name: Ostap Search Slug
 */

function search_by_slug($where, $query)
{
    global $pagenow, $wpdb;
    // var_dump($query);
    if (is_admin()  && isset($_GET['s']) && strpos($_GET['s'], 'slug:') !== false ) {

        $slug = str_replace('slug:', '', $_GET['s']);

        $where = $wpdb->prepare(" AND post_name LIKE %s", '%' . $wpdb->esc_like($slug) . '%');
 
    }
    
    return $where;
}

add_filter('posts_where', 'search_by_slug', 10, 2);

function search_by_slug2($query)
{
  
    global $pagenow, $wpdb;

    if (is_admin()  && isset($_GET['s']) && strpos($_GET['s'], 'slug:') !== false ) {

        $slug = str_replace('slug:', '', $_GET['s']);

        $where = $wpdb->prepare(" AND post_name LIKE %s", '%' . $wpdb->esc_like($slug) . '%');
        $query->set('where', $where);
        // return $where;
            var_dump($query);
    }
    
    return $where;
}

// add_action( 'pre_get_posts', 'search_by_slug2' );
