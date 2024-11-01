<?php

/**
 * Post list table setting fields
 */


if ( !class_exists('wpb_plt_setting_fields' ) ):
class wpb_plt_setting_fields {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WPB_PLT_WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( esc_html__( 'Post list table settings', WPB_PLT_TEXTDOMAIN ), esc_html__( 'Post list settings', WPB_PLT_TEXTDOMAIN ), 'delete_posts', 'wpb_post_list_table', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'wpb_plt_general',
                'title' => esc_html__( 'General Settings', WPB_PLT_TEXTDOMAIN )
            )
        );
        return $sections;
    }

    /**
     * Table content checkbox content
     */

    function wpb_plt_table_content(){
        $contents = array(
            'no'        => esc_html__( 'Row Number', WPB_PLT_TEXTDOMAIN ),
            'id'        => esc_html__( 'Post ID', WPB_PLT_TEXTDOMAIN ),
            'title'     => esc_html__( 'Post Title', WPB_PLT_TEXTDOMAIN ),
            'author'    => esc_html__( 'Post Author', WPB_PLT_TEXTDOMAIN ),
            'date'      => esc_html__( 'Post Date', WPB_PLT_TEXTDOMAIN ),
            'category'  => esc_html__( 'Post Category', WPB_PLT_TEXTDOMAIN ),
            'tag'       => esc_html__( 'Post Tags', WPB_PLT_TEXTDOMAIN ),
            'comment'   => esc_html__( 'Post Comment', WPB_PLT_TEXTDOMAIN ),
            'edit_link' => esc_html__( 'Post Edit Link', WPB_PLT_TEXTDOMAIN ),
        );

        /* WooCommerce Content */

        if ( class_exists( 'WooCommerce' ) ) { 
            $contents_woo = array( 
                'price'     => esc_html__( 'WooCommerce Price', WPB_PLT_TEXTDOMAIN ),
                'sku'       => esc_html__( 'WooCommerce SKU', WPB_PLT_TEXTDOMAIN ),
                'stock'     => esc_html__( 'WooCommerce Stock', WPB_PLT_TEXTDOMAIN ),
                'review'    => esc_html__( 'WooCommerce Review', WPB_PLT_TEXTDOMAIN ),
                'cart'      => esc_html__( 'WooCommerce Add to cart', WPB_PLT_TEXTDOMAIN ),
            );
            $contents = array_merge( $contents, $contents_woo );
        }

        /* Woo LightBox */

        if ( class_exists( 'WooCommerce' ) && function_exists( 'get_wpb_woocommerce_lightbox' ) ) { 
            $contents_woo_light_box = array( 
                'wpb_woo_lightbox'     => esc_html__( 'WPB WooCommerce LightBox', WPB_PLT_TEXTDOMAIN ),
            );
            $contents = array_merge( $contents, $contents_woo_light_box );
        }

        /* YIT Quickview */

        if( class_exists( 'YITH_WCQV_Frontend' ) ){
            $contents_yith_quickview = array( 
                'yith_quickview'     => esc_html__( 'YITH WooCommerce QuickView', WPB_PLT_TEXTDOMAIN ),
            );
            $contents = array_merge( $contents, $contents_yith_quickview );
        }

        /* YIT Wish List */

        if ( class_exists('YITH_WCWL') ) {
            $contents_yith_wishlist = array( 
                'yith_wishlist'     => esc_html__( 'YITH WooCommerce Wish List', WPB_PLT_TEXTDOMAIN ),
            );
            $contents = array_merge( $contents, $contents_yith_wishlist );
        }

        /* YIT Compare */

        if ( class_exists('YITH_Woocompare') ) {
            $contents_yith_compare = array( 
                'yith_compare'     => esc_html__( 'YITH WooCommerce Compare', WPB_PLT_TEXTDOMAIN ),
            );
            $contents = array_merge( $contents, $contents_yith_compare );
        }


        return $contents;
    }


    /**
     * Get all custom post types for select option
     */
    
    function wpb_plt_post_type_select(){

        $args = array(
            'public'   => true,
            '_builtin' => false
        );

        $rerutn_object = get_post_types( $args );
        $rerutn_object['post'] = esc_html__( 'Post', WPB_PLT_TEXTDOMAIN );

        return $rerutn_object;
    }

    /**
     * Get all custom taxonomy for select option
     */
    
    function wpb_plt_taxonomy_select(){

        $args = array(
            'public'   => true,
            '_builtin' => false
        );

        $rerutn_object = get_taxonomies( $args );
        $rerutn_object['category']  = esc_html__( 'Post Category', WPB_PLT_TEXTDOMAIN );
        $rerutn_object['post_tag']  = esc_html__( 'Post Tag', WPB_PLT_TEXTDOMAIN );

        return $rerutn_object;
    }
    

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'wpb_plt_general' => array(
                array(
                    'name'      => 'wpb_plt_post_type_select',
                    'label'     => esc_html__( 'Select Post Type', WPB_PLT_TEXTDOMAIN ),
                    'desc'      => esc_html__( 'You can select your own custom post type. Default: post.', WPB_PLT_TEXTDOMAIN ),
                    'type'      => 'select',
                    'default'   => 'post',
                    'options'   => $this->wpb_plt_post_type_select(),
                ),
                array(
                    'name'      => 'wpb_plt_category_taxonomy',
                    'label'     => esc_html__( 'Select Taxonomy for Category', WPB_PLT_TEXTDOMAIN ),
                    'desc'      => esc_html__( 'You can select your own custom taxonomy for category table. Default: category.', WPB_PLT_TEXTDOMAIN ),
                    'type'      => 'select',
                    'default'   => 'category',
                    'options'   => $this->wpb_plt_taxonomy_select(),
                ),
                array(
                    'name'      => 'wpb_plt_tag_taxonomy',
                    'label'     => esc_html__( 'Select Taxonomy for Tags', WPB_PLT_TEXTDOMAIN ),
                    'desc'      => esc_html__( 'You can select your own custom taxonomy for tags table. Default: Post Tag.', WPB_PLT_TEXTDOMAIN ),
                    'type'      => 'select',
                    'default'   => 'post_tag',
                    'options'   => $this->wpb_plt_taxonomy_select(),
                ),
                array(
                    'name'    => 'table_content',
                    'label'   => esc_html__( 'Table content', WPB_PLT_TEXTDOMAIN ),
                    'desc'    => esc_html__( 'Select table content.', WPB_PLT_TEXTDOMAIN ),
                    'type'    => 'multicheck',
                    'default' => array('no' => 'no', 'title' => 'title', 'author' => 'author', 'date' => 'date', 'category' => 'category', 'comment' => 'comment', 'edit_link' => 'edit_link'),
                    'options' => $this->wpb_plt_table_content(),
                ),
                array(
                    'name'    => 'table_style',
                    'label'   => esc_html__( 'Table Style', WPB_PLT_TEXTDOMAIN ),
                    'desc'    => esc_html__( 'Select a table style', WPB_PLT_TEXTDOMAIN ),
                    'type'    => 'select',
                    'default' => 'bordered',
                    'options' => array(
                        'default'   => esc_html__( 'Default', WPB_PLT_TEXTDOMAIN ),
                        'striped'   => esc_html__( 'Striped', WPB_PLT_TEXTDOMAIN ),
                        'bordered'  => esc_html__( 'Bordered', WPB_PLT_TEXTDOMAIN ),
                        'hover'     => esc_html__( 'Hover', WPB_PLT_TEXTDOMAIN ),
                    )
                )
            )
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

new wpb_plt_setting_fields();