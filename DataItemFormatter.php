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
		$wikitext = '';

		//TODO: process known properties in a well-defined order
		foreach ( $item['claims'] as $propertyId => $claims ) {
			$wikitext .= $this->formatClaims( $propertyId, $claims );
		}

		//TODO: add a link to wikidata
		//TODO: look at $item['sitelinks'] and generate a link to Wikipedia

		// if it's not empty...
		if ( $wikitext !== '' ) {
			// ...wrap it in a div
			$wikitext = Html::rawElement( 'div', array( 'class' => 'mw-enricher-box' ), $wikitext );
		}

		return $wikitext;
	}

	/**
	 * Formats a list of claims for the given property.
	 * Only values for well known properties will be included in the output.
	 *
	 * @param string $propertyId The ID of the property the claims are about.
	 *               See https://www.wikidata.org/wiki/Wikidata:List_of_properties for
	 *               a list of properties used on wikidata.org.
	 *
	 * @param array $claims A list of claims, as returned by
	 *        the Wikibase web API (e.g. on wikidata.org).
	 *
	 * @return string wikitext (including limited HTML).
	 */
	public function formatClaims( $propertyId, $claims ) {
		$wikitext = '';

		foreach ( $claims as $claim ) {
			// TODO: activate this once ranks are properly supported by wikidata.org
			//if ( $claim['rank'] !== 'preferred' ) {
			//	continue;
			//}

			//TODO: validate the structure to make sure all the fields we are expecting are actually set.
			$mainSnak = $claim['mainsnak'];

			if ( $mainSnak['snaktype'] !== 'value' ) {
				continue;
			}

			$dataValue = $mainSnak['datavalue'];

			$wikitext .= $this->formatPropertyValue( $propertyId, $dataValue );
		}

		//TODO: add a heading for the property, if $wikitext isn't empty.

		return $wikitext;
	}

	/**
	 * Formats property value (if the property is known).
	 * This is implemented by dispatching the call based on the property ID;
	 * If this class contains a method called "format$propertyId" it will be
	 * called with $dataValue as the first parameter. Otherwise, an empty string
	 * is returned. That is, when trying to format a value for property P108 ("employer"),
	 * the method 'formatP108' would be used, if it exists.
	 *
	 * @param string $propertyId The ID of the property the claims are about.
	 *               See https://www.wikidata.org/wiki/Wikidata:List_of_properties for
	 *               a list of properties used on wikidata.org.
	 *
	 * @param array $dataValue A data value, as returned as part of a claim by
	 *        the Wikibase web API (e.g. on wikidata.org).
	 *
	 * @return string wikitext (including limited HTML).
	 */
	public function formatPropertyValue( $propertyId, $dataValue ) {

		// TODO: Make separate formatter classes/objects for each property
		//       instead of dispatching based on method name.

		$method = 'format' . $propertyId;

		if ( !method_exists( $this, $method ) ) {
			return '';
		}

		$wikitext = call_user_func( array( $this, $method ), $dataValue );

		// if it's not empty...
		if ( $wikitext !== '' ) {
			// ...wrap it in a div
			$class = 'mw-enricher-value mw-enricher-value-' . $propertyId;
			$wikitext = Html::rawElement( 'div', array( 'class' => $class ), $wikitext );
		}

		return $wikitext;
	}

	/**
	 * This formats a property value for wikidata property P18 ("image")
	 * be generating an appropriate wikitext image reference.
	 *
	 * @see https://www.wikidata.org/wiki/Property:P18
	 *
	 * @note: you need to have $wgUseInstantCommons = true; for this to work.
	 * @see https://www.mediawiki.org/wiki/InstantCommons
	 *
	 * @param array $dataValue A data value of the type "commons media",
	 *        as returned as part of a claim by the Wikibase web API (e.g. on wikidata.org).
	 *
	 * @return string wikitext for including the image
	 */
	public function formatP18( $dataValue ) {
		$imageName = $dataValue['value'];

		return "[[File:$imageName|120px]]";
	}
}
