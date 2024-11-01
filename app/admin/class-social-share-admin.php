<?php
/**
 * Class for Custom SubMenu.
 *
 * @package Social_Share_Image
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If class is exist, then don't execute this.
if ( ! class_exists( 'Social_Share_Image_Admin' ) ) {

	/**
	 * Calls for admin methods.
	 */
	class Social_Share_Image_Admin {

		/**
		 * Constructor for class.
		 */
		public function __construct() {
			// Create Admin Menu.
			add_action( 'admin_menu', array( $this, 'ssi_admin_menu' ) );

			// Submit Data Of Admin Menu Form.
			add_action( 'admin_init', array( $this, 'ssi_submit_data' ) );

			add_action( 'add_meta_boxes', array( $this, 'ssi_meta_box' ) );

			// Image Genarator AJAX.
			add_action( 'admin_footer', array( $this, 'ssi_image_generator' ) );

			add_action( 'wp_ajax_ssi_image_generator_ajax', array( $this, 'ssi_image_generator_ajax' ) );

			// Custom CSS.
			add_action( 'admin_enqueue_scripts', array( $this, 'ssi_custom_script_loader' ) );

			add_action( 'save_post', array( $this, 'ssi_submit_data' ) );

		}

		/**
		 * Custom Admin Menu For AC Newsletter Subscription.
		 */
		public function ssi_admin_menu() {
			add_menu_page(
				esc_html__( 'Social Share Image', 'social-share-image' ),
				esc_html__( 'Social Share Image', 'social-share-image' ),
				'manage_options',
				'social-share-image',
				array( $this, 'ssi_menu_callback' ),
				'dashicons-cover-image',
				100
			);
		}

		/**
		 * Callback Function Of Custom Admin Menu.
		 */
		public function ssi_menu_callback() {
			echo sprintf(
				'<div class="warp"><h1>%s</h1></div>',
				esc_html__( 'Social Share Image', 'social-share-image' )
			);
			?>
			<form method="post" action="" id="ssi_form">
				<table class="form-table">
					<tbody>
						<tr>
							<th>
								<label for="color1"><?php esc_html_e( 'Pick 1st background color', 'social-share-image' ); ?></label>
							</th>
							<td>
								<?php $ssi_color1 = ! empty( get_option( 'ssi_color1' ) ) ? get_option( 'ssi_color1' ) : '#43cea2'; ?>
								<input type="color" class="color1" name="color1" value="<?php echo esc_attr( $ssi_color1 ); ?>">
								<p><?php esc_html_e( 'This will Generate gredient background.', 'social-share-image' ); ?></p>
							</td>
							<td rowspan="3">
								<svg viewBox="0 0 1200 630" xmlns="http://www.w3.org/2000/svg" class="social-image">
									<foreignObject x="0" y="0" width="1200" height="630">
										<div class="display-preview" id="ssi_preview">
											<div class="ssi-box">
												<div class="ssi-header">
													<h1 class="ssi-header-text"><?php esc_html_e( 'Post Title', 'social-share-image' ); ?></h1>
												</div>
												<div class="ssi-footer">
													<img class="ssi-avatar-img"
														src="<?php echo esc_attr( get_avatar_url( get_the_ID() ) ); ?>" onerror='this.style.display = "none"'/>
													<span><?php esc_html_e( 'Author · June 05, 2022', 'social-share-image' ); ?></span>
													<img class="ssi-site-logo"
														src="<?php echo esc_attr( get_site_icon_url() ); ?>" onerror='this.style.display = "none"'/>
												</div>
											</div>
										</div>
									</foreignObject>
								</svg>
							</td>
						</tr>
						<tr>
							<th>
								<label for="color2"><?php esc_html_e( 'Pick 2nd background color', 'social-share-image' ); ?></label>
							</th>
							<td>
								<?php $ssi_color2 = ! empty( get_option( 'ssi_color2' ) ) ? get_option( 'ssi_color2' ) : '#185a9d'; ?>
								<input type="color" class="color2" name="color2" value="<?php echo esc_attr( $ssi_color2 ); ?>">
								<p><?php esc_html_e( 'This will Generate gredient background.', 'social-share-image' ); ?></p>
							</td>
							<td></td>
						</tr>
						<tr>
							<th>
								<label for="toDirection"><?php esc_html_e( 'Select Direction', 'social-share-image' ); ?></label>
							</th>
							<td>
								<?php $ssi_gredient_direction = ! empty( get_option( 'ssi_gredient_direction' ) ) ? get_option( 'ssi_gredient_direction' ) : ''; ?>
								<select class="regular-text" name="toDirection">
									<option <?php selected( $ssi_gredient_direction, 'to right' ); ?> value="to right"><?php esc_html_e( 'To Right', 'social-share-image' ); ?></option>
									<option <?php selected( $ssi_gredient_direction, 'to left' ); ?> value="to left"><?php esc_html_e( 'To Left', 'social-share-image' ); ?></option>
									<option <?php selected( $ssi_gredient_direction, 'to bottom' ); ?> value="to bottom"><?php esc_html_e( 'To Bottom', 'social-share-image' ); ?></option>
									<option <?php selected( $ssi_gredient_direction, 'to top' ); ?> value="to top"><?php esc_html_e( 'To Top', 'social-share-image' ); ?></option>
								</select>
								<p><?php esc_html_e( 'Select Gredient Direction', 'social-share-image' ); ?></p>
							</td>
						</tr>
						<tr>
							<th>
								<legend><?php esc_html_e( 'Feautred Image', 'social-share-image' ); ?></legend>
							</th>
							<td>
								<label for="ssi_featImg_override">
									<?php $ssi_featimg_override = ! empty( get_option( 'ssi_featImg_override' ) ) ? 'checked' : ''; ?>
									<input type="checkbox" name="ssi_featImg_override" id="ssi_featImg_override" <?php echo esc_attr( $ssi_featimg_override ); ?>/>
									<?php esc_html_e( 'Override Featured Image?', 'social-share-image' ); ?>
								</label>
							</td>
						</tr>
						<input type="hidden" name="ssi_save" value="global">
						<?php wp_nonce_field( 'ssi_nonce_action', 'ssi_nonce' ); ?>
					</tbody>
				</table>
				<p>
					<input id="ssi_submitbtn" class="button button-primary" type="submit" />
				</p>
			</form>
			<?php
		}

		/**
		 * Submit Form Data to Databse.
		 *
		 * @param int $post_id Post ID.
		 */
		public function ssi_submit_data( $post_id = 0 ) {

			$save = filter_input( INPUT_POST, 'ssi_save', FILTER_DEFAULT );

			if ( empty( $save ) ) {
				return;
			}

			$ssi_color1             = filter_input( INPUT_POST, 'color1' );
			$ssi_color2             = filter_input( INPUT_POST, 'color2' );
			$ssi_gredient_direction = filter_input( INPUT_POST, 'toDirection' );
			$ssi_featimg_override   = filter_input( INPUT_POST, 'ssi_featImg_override' );
			// Nonce Verification.
			if (
				! isset( $_POST['ssi_nonce'] )
				|| ! wp_verify_nonce( $_POST['ssi_nonce'], 'ssi_nonce_action' )
			) {
				echo esc_html__( 'Invalid Submission', 'social-share-image' );
				die;
			}

			if ( ! empty( $post_id ) ) {
				update_post_meta( $post_id, 'ssi_color1', $ssi_color1 );
				update_post_meta( $post_id, 'ssi_color2', $ssi_color2 );
				update_post_meta( $post_id, 'ssi_gredient_direction', $ssi_gredient_direction );
			} if ( 'global' === $save ) {
				update_option( 'ssi_color1', $ssi_color1 );
				update_option( 'ssi_color2', $ssi_color2 );
				update_option( 'ssi_gredient_direction', $ssi_gredient_direction );
				update_option( 'ssi_featImg_override', $ssi_featimg_override );
			}
			// Display Admin Notice.
			add_action( 'admin_notices', array( $this, 'ssi_success_notice' ) );
		}

		/**
		 * Custom MetaBox.
		 */
		public function ssi_meta_box() {

			add_meta_box(
				'ssi_settings',
				__( 'Social Share Image', 'social-share-image' ),
				array( $this, 'ssi_meta_box_callback' ),
				'post'
			);
		}

		/**
		 * Custom MetaBox Callback funtion.
		 *
		 * @param object $post Current Post.
		 */
		public function ssi_meta_box_callback( $post ) {
			$ssi_post_id            = get_the_ID();
			$ssi_post_title         = get_the_title();
			$ssi_author             = get_the_author();
			$ssi_publish_date       = get_the_date();
			$ssi_avatar             = get_avatar_url( get_the_author_meta( 'ID' ) );
			$ssi_site_icon_url      = ! empty( get_site_icon_url() ) ? get_site_icon_url() : '';
			$ssi_color1             = ! empty( get_post_meta( $ssi_post_id, 'ssi_color1', true ) ) ? get_post_meta( $ssi_post_id, 'ssi_color1', true ) : ( ! empty( get_option( 'ssi_color1' ) ) ? get_option( 'ssi_color1' ) : '' );
			$ssi_color2             = ! empty( get_post_meta( $ssi_post_id, 'ssi_color2', true ) ) ? get_post_meta( $ssi_post_id, 'ssi_color2', true ) : ( ! empty( get_option( 'ssi_color2' ) ) ? get_option( 'ssi_color2' ) : '' );
			$ssi_gredient_direction = ! empty( get_post_meta( $ssi_post_id, 'ssi_gredient_direction', true ) ) ? get_post_meta( $ssi_post_id, 'ssi_gredient_direction', true ) : ( ! empty( get_option( 'ssi_gredient_direction' ) ) ? get_option( 'ssi_gredient_direction' ) : '' );
			?>
					<form method="post" action="" id="ssi_post_image">
						<div class="codess" hidden></div>
						<table class="form-table">
							<tbody>
								<tr>
									<th>
										<label for="color1"><?php esc_html_e( 'Pick 1st background color', 'social-share-image' ); ?></label>
									</th>
									<td>
										<input type="color" class="color1" name="color1" value="<?php echo esc_attr( $ssi_color1 ); ?>">
										<p><?php esc_html_e( 'This will Generate gredient background.', 'social-share-image' ); ?></p>
									</td>
									<td rowspan="3">
									<div class="wrapper">
										<svg viewBox="0 0 1200 630" xmlns="http://www.w3.org/2000/svg" class="social-image" id="image-svg">
											<foreignObject x="0" y="0" width="1200" height="630">
												<div class="display-preview" id="ssi_preview">
													<div class="ssi-box">
														<div class="ssi-header">
															<h1 class="ssi-header-text"><?php echo esc_html( $ssi_post_title ); ?></h1>
														</div>
														<div class="ssi-footer">
															<img class="ssi-avatar-img"
																src="<?php echo esc_attr( $ssi_avatar ); ?>" onerror='this.style.display = "none"'/>
															<span><?php echo esc_html( $ssi_author ) . ' · ' . esc_html( $ssi_publish_date ); ?></span>
															<?php if ( ! empty( $ssi_site_icon_url ) ) { ?>
															<img class="ssi-site-logo"
																src="<?php echo esc_attr( $ssi_site_icon_url ); ?>" onerror='this.style.display = "none"'/>
															<?php } ?>
														</div>
													</div>
												</div>
											</foreignObject>
										</svg>
									</div>
									</td>
								</tr>
								<tr>
									<th>
										<label for="color2"><?php esc_html_e( 'Pick 2nd background color', 'social-share-image' ); ?></label>
									</th>
									<td>
										<input type="color" class="color2" name="color2" value="<?php echo esc_attr( $ssi_color2 ); ?>">
										<p><?php esc_html_e( 'This will Generate gredient background.', 'social-share-image' ); ?></p>
									</td>
									<td></td>
								</tr>
								<tr>
									<th>
										<label for="toDirection"><?php esc_html_e( 'Select Gredient Direction', 'social-share-image' ); ?></label>
									</th>
									<td>
										<select class="regular-text" name="toDirection">
											<option <?php selected( $ssi_gredient_direction, 'to right' ); ?> value="to right"><?php esc_html_e( 'To Right', 'social-share-image' ); ?></option>
											<option <?php selected( $ssi_gredient_direction, 'to left' ); ?> value="to left"><?php esc_html_e( 'To Left', 'social-share-image' ); ?></option>
											<option <?php selected( $ssi_gredient_direction, 'to bottom' ); ?> value="to bottom"><?php esc_html_e( 'To Bottom', 'social-share-image' ); ?></option>
											<option <?php selected( $ssi_gredient_direction, 'to top' ); ?> value="to top"><?php esc_html_e( 'To Top', 'social-share-image' ); ?></option>
										</select>
										<p><?php esc_html_e( 'Select Gredient Direction', 'social-share-image' ); ?></p>
									</td>
								</tr>
								<input type="hidden" id="ssi_postid" value="<?php echo esc_attr( $ssi_post_id ); ?>"/>
								<input type="hidden" name="ssi_save" value="1">
								<?php wp_nonce_field( 'ssi_nonce_action', 'ssi_nonce' ); ?>
							</tbody>
						</table>
					</form>
					<?php
		}

		/**
		 * Image generator AJAX.
		 */
		public function ssi_image_generator() {
			?>
			<script type="text/javascript">
				jQuery(document).ready(function ($) {

					$(document).on('click', '.editor-post-publish-button', function () {

						var post_title = $('.wp-block-post-title').text();
						$('.ssi-header-text').text(post_title);

						domtoimage.toPng(ssi_preview).then(function (dataUrl) {
							var data = {
								'action': 'ssi_image_generator_ajax',
								'ssi_image': dataUrl,
								'ssi_post_id': $('#ssi_postid').val()
							};
							if (typeof ajaxurl === 'undefined') {
								var ajaxurl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
							}
							$.post(ajaxurl, data, function(response) {
								if( response.success ) {
									return true;
								}
								else {
									return false;
								}
							});
						})
						.catch(function (error) {
							console.error('error found', error);
						});
					});
				});
			</script>
			<?php
		}

		/**
		 * Image Generator AJAX callback function.
		 */
		public function ssi_image_generator_ajax() {
			$ssi_image   = filter_input( INPUT_POST, 'ssi_image', FILTER_DEFAULT );
			$ssi_post_id = filter_input( INPUT_POST, 'ssi_post_id', FILTER_VALIDATE_INT );
			update_post_meta( $ssi_post_id, 'ssi_image', $ssi_image );
			wp_send_json_success();
		}

		/**
		 * Display Success Notice.
		 */
		public function ssi_success_notice() {
			echo sprintf(
				'<div class="%1$s"><p>%2$s</p></div>',
				'notice notice-success is-dismissible',
				esc_html__( 'Settings Saved', 'social-share-image' )
			);
		}

		/**
		 * Custom CSS/Js loader.
		 *
		 * @param string $hook Current page hook.
		 */
		public function ssi_custom_script_loader( $hook ) {

			global $post;
			wp_enqueue_style(
				'ssi-image-generator-css',
				SSI_URL . 'assets/css/image-preview.min.css',
				array(),
				SSI_VERSION
			);

			if ( ( ! empty( $_GET['page'] ) && 'social-share-image' === $_GET['page'] ) || 'post-new.php' === $hook || 'post.php' === $hook ) {
				wp_enqueue_script(
					'ssi-image-generator-js',
					SSI_URL . 'assets/js/image-generator.min.js',
					array(),
					SSI_VERSION,
					true
				);
				wp_enqueue_script(
					'dom-to-image-js',
					SSI_URL . 'assets/js/dom-to-image.min.js',
					array(),
					SSI_VERSION,
					true
				);
			}
		}
	}
	new Social_Share_Image_Admin();
}
