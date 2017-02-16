<?php
class DownloadWithCode {

  /* Edit these :: */
	const MAXDOWNLOADS =  3;
	const TABLE = 'codes';

	// new name of local download file - this is what the visitor's file will actually be called when he/she saves it
	private $local_name_for_wav = 'file.wav';
	private $local_name_for_mp3 = 'file.mp3';

	public $database, $file, $code;

	public function __construct() {
		@set_time_limit(0);
		ini_set('memory_limit', '512M');
	}

	private function db_connect(){
		$db = new SQLite3( $this->database );
		if (!$db) die ($error);
		return $db;
	}

	public function query_code($code) {
		$db = $this->db_connect();

		$c = SQLite3::escapeString ( $code ) ;
		$q = "SELECT * FROM '" .  self::TABLE . "' WHERE code='" . $c . "'" ;

		$result = @$db->query( $q );
		$row = @$result->fetchArray();

		$db->close();

		// row == false when no row was found
		return $row;
	}


	private function increase_count_in_db() {
		$db = @$this->db_connect();
		$db->exec( "UPDATE '" .  self::TABLE . "' SET count = count + 1 WHERE code='" . $this->code . "'" );
		$db->close();
	}


	public function reached_max_dl( $count ) {
		$max = false;
		$count += 1;

		if ( $count > self::MAXDOWNLOADS ) $max = true;

		return $max;
	}


	public function gen_response( $c , $f ){

		$this->code = $c;
		$content = "Code not vaild.";

		// row == false when no row was found
		if ( $row = $this->query_code($this->code) ) {
			// max count not reached
			if ( false === $this->reached_max_dl( $row['count'] ) ) {
				$content ='

				<div id="1">
					<form method=POST id="dl-trigger" action="/?page_id=3230"><p>
						<input type="hidden" value="'. $this->code . '" name="c"</input>
						<input type="hidden" value="'. $f . '" name="sel_format"</input>
						<input type="submit" class ="btn middle" value="Start"></input>
					</form>
				</div>
				<script>
						jQuery("#dl-trigger").submit(function(e) {
								jQuery("#1").html( "<p><strong>Download has started.</strong> You still have  <strong>' .  ( self::MAXDOWNLOADS - $row['count'] - 1 ) .'</strong> Downloads." );
						});
				</script>

				';
			}
		}

		return $content;
	}


	public function dl_trigger($format) {
		// Start Download
		switch ($format) {
			case 'mp3':
				$file = $this->mp3_file;
				$filename = $local_name_for_mp3;
				break;
			case 'wav':
				$file = $this->wav_file;
				$filename = $local_name_for_wav;
				break;
		}

		$this->downloader( $file, $filename );
		$this->increase_count_in_db();
	}

	private function downloader( $file, $filename ){
		// Force the browser to start the download automatically

		//	Variables:
		//		$file = real name of actual download file on the server
		//		$filename = new name of local download file - this is what the visitor's file will actually be called when he/she saves it

		ob_start();

		$mm_type='application/zip';

		header("Content-Type: ".$mm_type);
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header("Content-Description: File Transfer");
		header("Content-Length: " .(string)(filesize($file)) );
		header("Cache-Control: public, must-revalidate");
		header("Pragma: public");
		header("Content-Transfer-Encoding: binary\n");

		ob_end_clean();
		readfile($file);
	}
}


?>
