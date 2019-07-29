<?php
class rc_sweet_custom_menu {

    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/

    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
    function __construct() {
        add_filter( 'wp_setup_nav_menu_item', array( $this, 'rc_scm_add_custom_nav_fields' ) );
        add_action( 'wp_update_nav_menu_item', array( $this, 'rc_scm_update_custom_nav_fields'), 10, 3 );
        add_filter( 'wp_edit_nav_menu_walker', array( $this, 'rc_scm_edit_walker'), 10, 2 );

    } // end constructor

    /**
     * Add custom fields to $item nav object
     * in order to be used in custom Walker
     *
     * @access      public
     * @since       1.0
     * @return      void
     */
    function rc_scm_add_custom_nav_fields( $menu_item ) {

        $menu_item->subtitle = get_post_meta( $menu_item->ID, '_menu_item_subtitle', true );
        return $menu_item;

    }

    /**
     * Save menu custom fields
     *
     * @access      public
     * @since       1.0
     * @return      void
     */
    function rc_scm_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

        // Check if element is properly sent
        if ( is_array( $_REQUEST['menu-item-subtitle']) ) {
            $subtitle_value = $_REQUEST['menu-item-subtitle'][$menu_item_db_id];
            update_post_meta( $menu_item_db_id, '_menu_item_subtitle', $subtitle_value );
        }

    }

    /**
     * Define new Walker edit
     *
     * @access      public
     * @since       1.0
     * @return      void
     */
    function rc_scm_edit_walker($walker,$menu_id) {

        return 'Walker_Nav_Menu_Edit_Custom';

    }

}

function insert_html_to_admin_menu_form($item,$item_id){
    ?>
    <p class="field-custom description description-wide">
        <label for="edit-menu-item-subtitle-<?php echo $item_id; ?>">
            <?php _e( 'Subtitle' ); ?><br />
            <input type="text" id="edit-menu-item-subtitle-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom" name="menu-item-subtitle[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->subtitle ); ?>" />
        </label>
    </p>
<?PHP
}

// instantiate plugin's class
$GLOBALS['sweet_custom_menu'] = new rc_sweet_custom_menu();

include_once( 'edit_custom_walker.php' );
include_once( 'custom_walker.php' );