<?php
/*
Plugin Name: List Media Filenames
Description: A plugin to list the original file names of all images in the media gallery and provide options to download or copy the list.
Author: HKW
Author URI: https://hkw.io/
Version: 0.1.0
Tested up to: 6.5.3
PHP Version: 8.1.23
Text Domain: list-media-filenames
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * List_Media_Filenames class
 *
 * A class to handle listing media filenames.
 */
class List_Media_Filenames {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_post_download_media_filenames_csv', array( $this, 'download_csv' ) );
	}

	/**
	 * Add admin menu.
	 */
	public function add_admin_menu() {
		add_menu_page(
			__( 'Media Filenames', 'list-media-filenames' ),
			__( 'Media Filenames', 'list-media-filenames' ),
			'manage_options',
			'media-filenames',
			array( $this, 'display_media_filenames' ),
			'dashicons-media-document'
		);
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_script( 'list-media-filenames-js', plugin_dir_url( __FILE__ ) . 'js/list-media-filenames.js', array( 'clipboard' ), null, true );
		wp_enqueue_script( 'clipboard-js', 'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js', array(), null, true );
	}

	/**
	 * Display media filenames.
	 */
	public function display_media_filenames() {
		global $wpdb;
		$query    = "
			SELECT ID, post_title, post_name
			FROM $wpdb->posts
			WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%'
		";
		$results  = $wpdb->get_results( $query );
		$csv_data = array();
		?>
		<div class="wrap">
			<h1><?php _e( 'Media Filenames', 'list-media-filenames' ); ?></h1>
			<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
				<input type="hidden" name="action" value="download_media_filenames_csv">
				<?php submit_button( __( 'Download CSV', 'list-media-filenames' ) ); ?>
			</form>
			<button id="copy-filenames" class="button"><?php _e( 'Copy Filenames to Clipboard', 'list-media-filenames' ); ?></button>
			<table class="widefat fixed" cellspacing="0" id="media-filenames-table">
				<thead>
					<tr>
						<th><?php _e( 'ID', 'list-media-filenames' ); ?></th>
						<th><?php _e( 'Title', 'list-media-filenames' ); ?></th>
						<th><?php _e( 'Filename', 'list-media-filenames' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ( $results as $result ) :
						$filename   = get_post_meta( $result->ID, '_wp_attached_file', true );
						$csv_data[] = array( $result->ID, $result->post_title, $filename );
						?>
						<tr>
							<td><?php echo esc_html( $result->ID ); ?></td>
							<td><?php echo esc_html( $result->post_title ); ?></td>
							<td><?php echo esc_html( $filename ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<textarea id="filenames-textarea" style="opacity:0; position:absolute;"><?php echo implode( "\n", wp_list_pluck( $csv_data, 2 ) ); ?></textarea>
		</div>
		<?php
	}

	/**
	 * Download CSV.
	 */
	public function download_csv() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'list-media-filenames' ) );
		}

		global $wpdb;
		$query   = "
			SELECT ID, post_title, post_name
			FROM $wpdb->posts
			WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%'
		";
		$results = $wpdb->get_results( $query );

		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename=media_filenames.csv' );

		$output = fopen( 'php://output', 'w' );
		fputcsv( $output, array( 'ID', 'Title', 'Filename' ) );

		foreach ( $results as $result ) {
			$filename = get_post_meta( $result->ID, '_wp_attached_file', true );
			fputcsv( $output, array( $result->ID, $result->post_title, $filename ) );
		}

		fclose( $output );
		exit;
	}
}

new List_Media_Filenames();
?>
