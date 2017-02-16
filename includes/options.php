<?php
class DltDownloaderSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Downloader Settings',
            'Downloader Settings',
            'manage_options',
            'downloader-setting-admin',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'dlt_downloader' );
        ?>
        <div class="wrap">
            <h2>My Settings</h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'dlt_downloader_group' );
                do_settings_sections( 'dlt_downloader_admin' );
                submit_button();
            ?>
            </form>
            Coded by Daniel Schubert <a href="http://www.schubertdaniel.de">www.schubertdaniel.de</a>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'dlt_downloader_group', // Option group
            'dlt_downloader', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'downloader_settings', // ID
            'Downloader Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'dlt_downloader_admin' // Page
        );

        add_settings_field(
            'downloader_downloader_zip_file', // ID
            'Zip Datei', // Title
            array( $this, 'downloader_zip_file_callback' ), // Callback
            'dlt_downloader_admin', // Page
            'downloader_settings' // Section
        );


        add_settings_field(
            'downloader_sqlite_database',
            'SQLite3 Datenbank',
            array( $this, 'downloader_sqlite_database_callback' ),
            'dlt_downloader_admin',
            'downloader_settings'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['downloader_zip_file'] ) )
            $new_input['downloader_zip_file'] = sanitize_text_field( $input['downloader_zip_file'] );

        if( isset( $input['downloader_sqlite_database'] ) )
            $new_input['downloader_sqlite_database'] = sanitize_text_field( $input['downloader_sqlite_database'] );

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /**
     * Get the settings option array and print one of its values
     */

    public function downloader_zip_file_callback()
    {
        printf(
            '<input type="text" id="downloader_zip_file" size="80" required name="dlt_downloader[downloader_zip_file]" value="%s" />',
            isset( $this->options['downloader_zip_file'] ) ? esc_attr( $this->options['downloader_zip_file']) : ''
        );
    }


    public function downloader_sqlite_database_callback()
    {
        printf(
            '<input type="text" id="downloader_sqlite_database" size="60" required name="dlt_downloader[downloader_sqlite_database]" value="%s" />',
            isset( $this->options['downloader_sqlite_database'] ) ? esc_attr( $this->options['downloader_sqlite_database']) : ''
        );
    }
}

if( is_admin() )
    $dlt_downloader_settings_page = new DltDownloaderSettingsPage();
