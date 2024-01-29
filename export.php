<?php
/*
Plugin Name: WP Booking Export
Plugin URI: https://innovisionlab.com
Description: WP Booking Export
Author: iLab
Version: 1.0.0
Author URI: https://innovisionlab.com
*/

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
            'post_status' => 'any',
            'posts_per_page' => $data['ppp'],
            'offset' => $data['pn'] * $data['ppp'],
            'orderby' => 'ID',
            'order' => 'ASC',
            'date_query' => [
                'after' => [
                    'year'  => 2018,
                    'month' => 12,
                    'day'   => 31,
                ]
            ]
        ]);

        $return = [];
        
        foreach ($arrPosts as $objPost)
        {
            $return[] = [
                //'title' => $objPost->post_title,
                'Last Name' => get_post_meta($objPost->ID, "quitenicebooking_guest_last_name", true),
                'First Name' => get_post_meta($objPost->ID, "quitenicebooking_guest_first_name", true),
                'Email' => get_post_meta($objPost->ID, "quitenicebooking_guest_email", true),

            ];
        }

        return $return;
    }
}

new WpCptExport();