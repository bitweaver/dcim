<?php
/**
 * @version $Header$
 * @package bitweaver
 */
require_once( 'kernel/includes/setup_inc.php' );

// $gBitSystem->loadLayout() needs an active package
if( !$gBitSystem->isDatabaseValid() ) {
	install_error();
} elseif( !$gBitSystem->getActivePackage() ) {
	$bit_index = $gBitSystem->getConfig( 'bit_index' );
	if( in_array( $bit_index, array_keys( $gBitSystem->mPackages )) && defined( strtoupper( $bit_index ).'_PKG_PATH' )) {
		$gBitSystem->setActivePackage( $bit_index );
	} else {
		unset( $bit_index );
	}
}

if( !empty( $_REQUEST['content_id'] )) {
	if( $obj = LibertyBase::getLibertyObject( $_REQUEST['content_id'] )) {
		$url = $obj->getDisplayUrl();
		if( !empty($_REQUEST['highlight'] )) {
			if( preg_match( '/\?/', $url )) {
				$url .= '&';
			} else {
				$url .= '?';
			}
			$url .= 'highlight='.$_REQUEST['highlight'];
		}
		bit_redirect( $url );
	} else {
		$gBitSystem->fatalError( tra( 'Page cannot be found' ), NULL, NULL, HttpStatusCodes::HTTP_GONE );
	}
} elseif( !empty( $_REQUEST['structure_id'] )) {
	bit_redirect( WIKI_PKG_URL.'?structure_id='.$_REQUEST['structure_id'] );
}

$gBitThemes->loadLayout();
// Redirectless home for packages
if( !empty( $bit_index )) {
	chdir( $gBitSystem->mPackages[$bit_index]['path'] );
	include_once( './index.php' );
die;
}

bit_redirect( $gBitSystem->getDefaultPage() );
