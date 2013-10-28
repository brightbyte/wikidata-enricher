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
	 * @var DataItemLoader
	 */
	protected $loader;

	/**
	 * @var DataItemFormatter
	 */
	protected $formatter;

	public function __construct( DataItemLoader $loader, DataItemFormatter $formatter ) {
		$this->loader = $loader;
		$this->formatter = $formatter;
	}

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

		$parser->getOutput()->addModules( array( 'ext.Enricher.box' ) );

		if ( isset( $args[0]) ) {
			$id = $args[0];

			try {
				//TODO: apply caching
				$item = $this->loader->loadItem( $id );

				$wikitext = $this->formatter->formatItem( $item );
			} catch ( DataItemLoaderException $ex ) {
				$wikitext = HTML::element( 'div', array( 'class' => 'error' ), $ex->getMessage() );
			}
		} else {
			$wikitext = 'Missing item ID!'; //TODO: i18n
		}

		return array(
			$wikitext,
			'noparse' => false
		);
	}

}
