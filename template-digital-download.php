<?php
/*
Template Name: Digital Download
*/

/*
* Basic Wordpress Template for use with this plugin; copy it to your Themes Folder and use it as Page Template.
* 
*/

if ( isset( $_POST['c'] )) {

   // process form data
  require_once( ABSPATH. 'wp-content/plugins/download-with-code/DownloadWithCode.class.php');
  $d = new DownloadWithCode;

  /* Edit Here: */
  $d->database = ( ABSPATH . 'db/sample-data-codes.sqlite' );
  $d->wav_file = ( ABSPATH . 'cool-musik.wav');
  $d->mp3_file = ( ABSPATH . 'cool-musik.mp3');


  $c = $_POST['c'];
  $f = $_POST['sel_format'];
  $d->code = $c;

  if ( $row = $d->query_code($d->code)) {
    // max count not reached
    if ( false === $d->reached_max_dl( $row['count'] ) )
        $d->dl_trigger($f);
  }
}


get_header();

?>

<section class="container clearfix">
  <!-- main -->
    <form method="post" id="code_form">
    <p>
      <label for="format">choose format :</label>
      <select name="format">
        <option value="mp3" selected > mp3 ( 116 MB )</option>
        <option value="wav" > wav ( 661 MB )</option>
      </select>
      <p><input width= "8" placeholder="Enter Code" required pattern="[A-Za-z0-9]{8}" maxlength=8 minlength=8 type="text" id="code" name="code"></input>
      <p><input type="submit" value="Submit" class ="btn middle"></input>
    </form>
    <div id="resp"></div>
</section>
  <!-- /main -->

<?php get_footer(); ?>
