<?php
/**
 * Loads a data item from a Wikibase repository using the MediaWiki web API.
 *
 * @file
 * @ingroup Extensions
 */

class DataItemLoader {

	/**
	 * @var string
	 */
	protected $apiUrl;

	/**
	 * @param string $apiUrl
	 */
	public function __construct( $apiUrl ) {
		$this->apiUrl = $apiUrl;
	}

	/**
	 * @param string $id
	 *
	 * @throws DataItemLoaderException
	 * @return array A nested array structure representing the data item, in the form
	 *               returned by the Wikibase web API.
	 */
	public function loadItem( $id ) {
		// normalize the ID
		$id = strtoupper( trim( $id ) );

		// Compose the URL of the API call
		$url = $this->apiUrl . '?' . // the base URL was provided to the constructor
			wfArrayToCgi( array(
				'action' => 'wbgetentities',   // use wbgetentities to fetch the item data
				'props' => 'claims|sitelinks', // we want claims and sitelinks
				'format' => 'json', // we want this in JSON format
				'ids' => $id,  // this is the ID of the item we want
			) );

		// Execute the HTTP call
		// NOTE: we set a 5 second timeout, so we don't block for too long in case there is a problem.
		$json = Http::get( $url, 5 );

		if ( $json === false ) {
			//TODO: get more info about the error (use MWHttpRequest::execute)
			throw new DataItemLoaderException( 'HTTP request failed' );
		}

		// decode the JSON string
		$data = json_decode( $json, true );

		if ( !is_array( $data ) ) {
			throw new DataItemLoaderException( 'Failed to decode response' );
		}

		// check for an error message in the result
		if ( isset( $data['error'] ) ) {
			$error = $data['error'];
			throw new DataItemLoaderException( 'Request failed (' . $error['code'] . '): ' . $error['info'] );
		}

		// check that the item is there
		if ( !isset( $data['entities'][$id] ) ) {
			throw new DataItemLoaderException( 'Could not find entity ' . $id . ' in the result' );
		}

		return $data['entities'][$id];
	}

}
