<?php

$jsonfilecontents = file_get_contents("sources.json");
$lists = json_decode($jsonfilecontents, true);

foreach ( $lists as $name => $list ) {
	echo "Converting {$name}...\n";
	// Fetch filter list and explode into an array.
	/* $lines = file_get_contents( preg_replace("/\r|\n/", "", $list)); */
	$lines = file_get_contents( $list);
	$lines = explode( "\n", $lines );

	// HOSTS header.
	$hosts  = "# {$name}\n";
	$hosts .= "#\n";
	$hosts .= "# Converted from - {$list}\n";
	$hosts .= "# Last converted - " . date( 'r' ) . "\n";
	$hosts .= "#\n\n";

	$exceptions = array();

	// Loop through each ad filter.
	foreach ( $lines as $filter ) {
		// Skip filter if matches the following:
		if ( false === strpos( $filter, '.' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '*' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '/' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '=' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '#' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, ' ' ) ) {
			continue;
		}

		// Replace filter syntax with HOSTS syntax.
		// @todo Perhaps skip $third-party, $image and $popup?
		$filter = str_replace( array( '||', '^', '$third-party', ',third-party', '$image', ',image', ',important', '$script', ',script', ',object', '$popup', '$empty', '$object-subrequest', '$subdocument', '$ping' ), '', $filter );

		// Skip rules matching 'xmlhttprequest' for now.
		if ( false !== strpos( $filter, 'xmlhttprequest' ) ) {
			continue;
		}

		// Skip exclusion rules.
		if ( false !== strpos( $filter, '~' ) ) {
			continue;
		}

		// Trim whitespace.
		$filter = trim( $filter );

		// If starting or ending with '.', skip.
		if ( '.' === substr( $filter, 0, 1 ) || '.' === substr( $filter, -1 ) ) {
			continue;
		}

		// If starting with '-', skip.
		// https://github.com/r-a-y/mobile-hosts/issues/5
		if ( '-' === substr( $filter, 0, 1 ) || '_' === substr( $filter, 0, 1 ) ) {
			continue;
		}

		// Strip trailing |.
		if ( '|' === substr( $filter, -1 ) ) {
			$filter = str_replace( '|', '', $filter );
		}

		// Skip file extensions
		if ( '.jpg' === substr( $filter, -4 ) || '.gif' === substr( $filter, -4 ) ) {
			continue;
		}

		// Save exception to parse later.
		if ( 0 === strpos( $filter, '@@' ) ) {
			$exceptions[] = str_replace( '@@', '', $filter );
			continue;
		}


		$hosts .= "0.0.0.0 {$filter}\n";
	}

	// Remove exceptions.
	if ( ! empty( $exceptions ) ) {
		foreach( $exceptions as $ex ) {
			$find = "0.0.0.0 {$ex}\n";
			if ( false !== strpos( $hosts, $find ) ) {
				$hosts = str_replace( $find, '', $hosts );
			}
		}
	}

	// Output the file.
	file_put_contents( "../{$name}.txt", $hosts );

	echo "{$name} -> {$name}.txt\n";
}
