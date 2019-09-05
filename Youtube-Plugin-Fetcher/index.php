<?php
/**
 * Plugin Name
 *
 * @package     xwebExplore
 * @author      Ankit Mishra
 * @copyright   Ankit Mishra
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Youtube Video Fetcher
 * Plugin URI:  https://ankit-mishra.cf/yvf/
 * Description: This Plugin will be used to fetch video data from youtube and those data will be used for adding videos to your site.
 * Version:     1.0.0
 * Author:      Ankit Mishra
 * Author URI:  https://ankit-mishra.cf/
 * Text Domain: yvf
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
 class yvf{
     public $aParam;
     public function __construct(){
         global $wpdb;
         $this->aParam['videos_table']   = $wpdb->prefix . "yvf";
         $this->aParam['category_table']   = $wpdb->prefix . "ctb";
         $this->aParam['api']='AIzaSyAPpeo2qgG1YDdIOCzb1H3vJ5BnhRIGr58';
         add_action( 'admin_menu', array($this, 'add_yvf_admin_menu'));
         add_action( 'admin_footer', array(&$this,'admin_enqueue_scripts' ));
         add_action( 'wp_footer', array(&$this,'enqueue_scripts' ));
         register_activation_hook( __FILE__, array($this,'activation_yvf_plugin') );
         add_shortcode('yvf_category', array($this,'video_by_category_func' ));
         add_shortcode('yvf_home', array($this,'yvf_home_func' ));
         add_shortcode('yvf_watch',array($this,'yvf_watch_func'));
         add_shortcode('yvf_search',array($this,'yvf_search_func'));
         add_action('init',array($this,'create_new_post'));
         add_action( 'admin_init', array($this,'create_meta_box') );
         //add_filter( 'template_include', array($this,'include_template_function', 1) );
     }
     public function activation_yvf_plugin(){
         global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();				

		$sql = "CREATE TABLE {$this->aParam['videos_table']} (	
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		title varchar(200) NOT NULL,
        published varchar(200)  NOT NULL,
        description varchar(4000) NOT NULL,
        date varchar(200) NOT NULL,
        tolive DATE,
        pinned INT DEFAULT 0,
        views BIGINT DEFAULT 0,
        likes INT DEFAULT 0,
        dislikes INT DEFAULT 0,
        url varchar(200) NOT NULL,
        tags varchar(300) DEFAULT 'tag',
        category INT Default 0, 
        Thumbnail varchar(200) DEFAULT '',
		UNIQUE KEY id (id)
		) $charset_collate;";
        	$sql1 = "CREATE TABLE {$this->aParam['category_table']} (	
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		title varchar(200) NOT NULL,
        Thumbnail varchar(200) DEFAULT '',
		UNIQUE KEY id (id)
		) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        dbDelta( $sql1 );
     }
     function create_meta_box(){
         add_meta_box( 'movie_review_meta_box',
        'Movie Review Details',
        'display_movie_review_meta_box',
        'movie_reviews', 'normal', 'high'
    );
     }
   function create_new_post() {
    register_post_type( 'yvf',
        array(
            'labels' => array(
                'name' => 'Fetched Videos',
                'singular_name' => 'Fetched Video',
                'add_new' => 'Add New',
                'add_new_item' => 'Add Missing Video',
                'edit' => 'Edit',
                'edit_item' => 'Edit Fetched Video',
                'new_item' => 'New Video',
                'view' => 'View',
                'view_item' => 'View Movie Review',
                'search_items' => 'Search Videos',
                'not_found' => 'No Videos found',
                'not_found_in_trash' => 'No Videos found in Trash',
                'parent' => 'Parent Videos'
            ),
 
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title','custom-fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'images/favicon.ico', __FILE__ ),
            'has_archive' => true
        )
    );
}
public function include_template_function($template_path){

    if ( get_post_type() == 'yvf' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-yvf.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/template/single-yvf.php';
            }
        }
    }
    return $template_path;
}
     public function video_by_category_func(){
         include 'shortcode/category.php';
     }
     public function yvf_home_func(){
         include 'shortcode/home.php';
     }
     public function yvf_watch_func(){
         include 'shortcode/watch.php';
     }
     public function yvf_search_func(){
         include 'shortcode/search.php';
     }
     public function admin_enqueue_scripts(){
         wp_enqueue_style( 'yvf_style', plugins_url('src/css/admin-css/style.css', __FILE__), array(), '' );
		wp_enqueue_script( 'yvf_script', plugins_url('src/js/admin-js/script.js', __FILE__), array(), '' );
        wp_enqueue_style( 'yvf_bootsrap_style', plugins_url('shortcode/bootstrap/css/bootstrap.min.css', __FILE__), array(), '' );
		wp_enqueue_script( 'yvf_bootstrap_script', plugins_url('shortcode/bootstrap/js/bootstrap.min.js', __FILE__), array(), '' );
     }
     public function enqueue_scripts(){
         wp_enqueue_style( 'yvf_home_bootsrap_style', plugins_url('shortcode/bootstrap/css/bootstrap.min.css', __FILE__), array(), '' );
		wp_enqueue_script( 'yvf_home_bootstrap_script', plugins_url('shortcode/bootstrap/js/bootstrap.min.js', __FILE__), array(), '' );
     }
     public function add_yvf_admin_menu(){
         add_menu_page(
        'Youtube Video Fetcher',
        'Youtube Video Fetcher',
        'manage_options',
        plugin_dir_path(__FILE__) . 'admin/yvf.php',
        null,
        plugin_dir_url(__FILE__) . 'images/favicon.ico',
        20
    );
    add_submenu_page(
        plugin_dir_path(__FILE__) . 'admin/yvf.php',
        'Add Category',
        'Add New Category',
        'manage_options',
        plugin_dir_path(__FILE__) . 'admin/add_cat.php',
        null
    );
     add_submenu_page(
        plugin_dir_path(__FILE__) . 'admin/yvf.php',
        'View And Edit Fetched Videos',
        'Fetched Videos',
        'manage_options',
        plugin_dir_path(__FILE__) . 'admin/all_vid.php',
        null
    );
      add_submenu_page(
        plugin_dir_path(__FILE__) . 'admin/yvf.php',
        'Setting For Home Page',
        'Home Page Setting',
        'manage_options',
        plugin_dir_path(__FILE__) . 'admin/homepage.php',
        null
    );
     add_submenu_page(
        plugin_dir_path(__FILE__) . 'admin/yvf.php',
        'Plugin Setting',
        'Settings',
        'manage_options',
        plugin_dir_path(__FILE__) . 'admin/plugin_setting.php',
        null
    );
     }

 }
$fetcher=new yvf;