<?php
/**
 * Formats a data item for use in wikitext
 *
 * @file
 * @ingroup Extensions
 */

class DataItemFormatter {


	/**
	 * Formats a data item as wikitext.
	 * Only values for well known properties will be included in the output.
	 *
	 * @param array $item A nested array structure representing a data item, as returned by
	 *        the Wikibase web API (e.g. on wikidata.org).
	 *
	 * @return string wikitext (including limited HTML).
	 */
	public function formatItem( $item ) {
		//TODO: generate wikitext from item
		$wikitext = '(a data item)';

		// if it's not empty...
		if ( $wikitext !== '' ) {
			// ...wrap it in a div
			$wikitext = Html::rawElement( 'div', array( 'class' => 'mw-enricher-box' ), $wikitext );
		}

		return $wikitext;
	}
}
