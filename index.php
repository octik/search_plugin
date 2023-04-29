<?php /*
 * Plugin Name: Ostap Search Slug
 */

function search_by_slug($where, $query)
{
    global $pagenow, $wpdb;
    if (is_admin()  && isset($_GET['s']) && strpos($_GET['s'], 'slug:') !== false ) {

        $slug = str_replace('slug:', '', $_GET['s']);

        $where = $wpdb->prepare(" AND post_name LIKE %s", '' . $wpdb->esc_like($slug) . '');
 
    }
    
    return $where;
}
add_filter('posts_where', 'search_by_slug', 10, 2);
add_action( 'pre_get_posts', 'menufilter', 10, 2 );
function menufilter( $q ) {

    if(isset($_POST['action']) && $_POST['action']=="menu-quick-search" && isset($_POST['menu-settings-column-nonce'])){    
        if( is_a($q->query_vars['walker'], 'Walker_Nav_Menu_Checklist') ){
           
            $slug = str_replace('slug:', '', $q->query_vars['s']);
            $q->set( 's', $slug );


        }
    }
    return $q;
}
add_filter('acf/fields/post_object/query', 'acf_fields_post_object_query', 10, 3);
function acf_fields_post_object_query( $args, $field, $post_id ) {
    $slug = str_replace('slug:', '', $args['s']);
    $args['s'] =  $slug ;
    return $args;
}

