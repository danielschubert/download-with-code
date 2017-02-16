<?php
/*
Plugin Name: Dan’s File Downloader
Plugin URI: https://github.com/danielschubert/
Description: Download Files upon Entering Valid Code
Author: Daniel Schubert <mail@schubertdaniel.de>
Version: 0.1
Author URI: http://www.schubertdaniel.de
*/

include_once(ABSPATH.'wp-admin/includes/plugin.php');
require_once(__DIR__ . '/DownloadWithCode.class.php');

/* include settings page for admin */
if (is_admin())
    require_once(__DIR__ . '/includes/options.php');

  /*
   * ajax callback
   */

function submit_code() {
  $d = new DownloadWithCode;

  // TODO
  //$d->database = $this->options['downloader_sqlite_database']
  //$d->file = $this->options['downloader_zip_file']

  if (!empty($_POST)) {

    /* TODO : validation */
    $code = $_POST['code'];
    $format = $_POST['format'];

    $d->database = ( ABSPATH . 'db/sample-data-codes.sqlite' );

    $resp = $d->gen_response($code, $format);

  } else {
    $code = null;
    $resp = 'POST leer';
  }

  echo json_encode(array('code' => $code, 'resp' => $resp ));
  wp_die();
}

add_action('wp_ajax_nopriv_submit_code', 'submit_code');
add_action('wp_ajax_submit_code', 'submit_code');
wp_enqueue_script( 'ajax_submit_code',
              plugins_url('includes/js/ajax.js', __FILE__ ),
              array( 'jquery' )
            );
wp_localize_script( 'ajax_submit_code',
                  'frontendajax',
                  array('ajaxurl' => admin_url( 'admin-ajax.php'))
          );
