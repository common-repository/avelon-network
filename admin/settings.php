<?php
add_action('admin_head', 'avelon_settings_style');

function avelon_settings_style() {
  echo '<style>
	#toplevel_page_avelon-settings .wp-menu-image {
		background: url(' . esc_url(plugin_dir_url( __DIR__ ) . "assets/avelon-logo.svg" ) . ') no-repeat center 11px / 22px auto !important;
	}
  </style>';
}

function avelon_settings_page() {
add_menu_page(
    __( 'Avelon', 'my-textdomain' ),
    __( 'Avelon', 'my-textdomain' ),
    'manage_options',
    'avelon-settings',
    'avelon_settings_page_content',
    'none',
    3
);
}

add_action( 'admin_menu', 'avelon_settings_page' );


function avelon_settings_page_content() {
?>
    <form method="POST" action="options.php">
    <?php
        settings_fields( 'avelon-settings' );
        do_settings_sections( 'avelon-settings' );
        submit_button();
    ?>
    </form>
    <?php
}

add_action( 'admin_init', 'avelon_settings_init' );


function avelon_settings_init() {

    add_settings_section(
        'avelon_settings_page_settings_section',
        __( 'Avelon settings', 'my-textdomain' ),
        'avelon_settings_section_callback_function',
        'avelon-settings'
    );

    add_settings_field(
        'avelon_account_id',
        __( 'Avelon Account ID', 'my-textdomain' ),
        'avelon_account_id_markup',
        'avelon-settings',
        'avelon_settings_page_settings_section'
     );

     register_setting( 'avelon-settings', 'avelon_account_id' );

     add_settings_field(
        'avelon_api_token',
        __( 'Avelon API Token', 'my-textdomain' ),
        'avelon_api_token_markup',
        'avelon-settings',
        'avelon_settings_page_settings_section'
     );

     register_setting( 'avelon-settings', 'avelon_api_token' );
}

function avelon_settings_section_callback_function() {
?>

    <img src="<?php echo esc_url(plugin_dir_url( __DIR__ ) . 'assets/avelon-logo.svg'); ?>" width="150px"/>
    <p>You can find the required settings in your Avelon Network Account under <a href="https://app.avelonetwork.com/provider/settings" target="_blank">Settings</a></p>

<?php
}

function avelon_account_id_markup() {
?>
    <input type="text" id="avelon_account_id" name="avelon_account_id" value="<?php esc_attr_e( get_option( 'avelon_account_id' ) ); ?>">
    <?php
}

function avelon_api_token_markup() {
?>
    <input type="text" id="avelon_api_token" name="avelon_api_token" value="<?php esc_attr_e( get_option( 'avelon_api_token' ) ); ?>">
    <?php
}