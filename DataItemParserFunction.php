<?php
/**
 * Parser function implementation for fetching a data item and rendering it.
 *
 * @file
 * @ingroup Extensions
 *
 * @author Daniel Kinzler
 * @license GNU General Public Licence 3.0 or later
 */

class DataItemParserFunction {

	/**
	 * Handles the #dataitem parser function.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Parser_functions
	 *
	 * @param Parser $parser
	 *
	 * @return array
	 */
	public function evaluate( Parser $parser ) {
		$args = func_get_args();
		array_shift( $args );

		if ( isset( $args[0]) ) {
			$id = $args[0];

			//TODO: load the data item and render the desired information
			$wikitext = "Enricher ($id)";
		} else {
			$wikitext = 'Missing item ID!'; //TODO: i18n
		}

		return array(
			$wikitext,
			'noparse' => false
		);
	}

}
