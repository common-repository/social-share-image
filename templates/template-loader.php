<?php
$p_id      = get_query_var( 'ssi-image' );
$ssi_image = get_post_meta( $p_id, 'ssi_image', true );
$ssi_image = str_replace( 'data:image/png;base64,', '', $ssi_image );
$ssi_image = str_replace( ' ', '+', $ssi_image );
$data      = base64_decode( $ssi_image );

$img = imagecreatefromstring( $data );
if ( false !== $img ) {
	header( 'Content-Type: image/png' );
	imagepng( $img );
	imagedestroy( $img );
} else {
	echo 'An error occurred.';
}
