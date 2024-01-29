<?php
class WpCptExport
{
    function __construct() {
        add_action('rest_api_init', [$this, 'func_init_endpoints']);
    }

    function func_init_endpoints(){

        register_rest_route( 'v1', '/cptexport', array(
            'methods' => 'GET',
            'callback' => [$this, 'func_fetch_cptdata'], 
        ) );
    
    }

    function func_fetch_cptdata($data) {

        $arrPosts = get_posts([
            'post_type' => $data['cpt'],
            'post_status' => 'publish',
            'posts_per_page' => $data['ppp'],
            'offset' => $data['pn'] * $data['ppp'],
            'orderby' => 'ID',
        ]);

        $return = [];
        
        foreach ($arrPosts as $objPost)
        {
            $pm = get_post_meta($objPost->ID);

            $gallery = [];

            $arrPost = (array)$objPost;


            $return[] = array_merge($arrPost, $pm);
        }

        return [
            $return
        ];
    }
}

new WpCptExport();