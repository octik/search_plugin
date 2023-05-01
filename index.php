<?php /*
 * Plugin Name: Ostap Search Slug
 */

function search_by_slug($where, $query)
{
    global $pagenow, $wpdb;
    if (is_admin()  && isset($_GET['s']) && strpos($_GET['s'], 'slug:') !== false  && $query->is_search()) {
        $slug = str_replace('slug:', '', $_GET['s']);
     
        if ($_GET['post_status'] === 'draft') {
            $where = $wpdb->prepare("AND post_title LIKE %s  AND post_status LIKE %s ", '%' . $wpdb->esc_like($slug) . '%', $_GET['post_status']);
        } elseif ($_GET['post_status'] === 'all') {
            $where = $wpdb->prepare("AND post_title = %s ",  $wpdb->esc_like($slug));
        } else {
            $where = $wpdb->prepare("AND post_name LIKE %s  AND post_status = %s AND language_code LIKE %s " , '%' . $wpdb->esc_like($slug) . '%', $_GET['post_status'], $_GET['lang']);

        }
     var_dump($query->is_search());
       
    }
    return $where;

}
add_filter('posts_where', 'search_by_slug', 10, 2);

add_action('pre_get_posts', 'menufilter', 10, 2);
function menufilter($q)
{

    if (isset($_POST['action']) && $_POST['action'] == "menu-quick-search" && isset($_POST['menu-settings-column-nonce'])) {
        if (is_a($q->query_vars['walker'], 'Walker_Nav_Menu_Checklist')) {

            $slug = str_replace('slug:', '', $q->query_vars['s']);
            $q->set('s', $slug);
        }
    }

    return $q;
}
add_filter('acf/fields/post_object/query', 'acf_fields_post_object_query', 10, 3);
function acf_fields_post_object_query($args, $field, $post_id)
{
    $slug = str_replace('slug:', '', $args['s']);
    $args['s'] =  $slug;
    return $args;
}

function custom_search_join($join, $query){

	global $wpdb;
    if (is_admin() && $query->is_search()) {
	  $join .= "LEFT JOIN wp_icl_translations ON wp_posts.ID = wp_icl_translations.element_id";
    }
	return $join;

  }
  add_filter('posts_join', 'custom_search_join', 10,2);
 
 