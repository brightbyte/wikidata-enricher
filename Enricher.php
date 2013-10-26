<?php
/**
 * Enricher extension - the thing that needs you.
 *
 * For more info see http://mediawiki.org/wiki/Extension:Enricher
 *
 * @file
 * @ingroup Extensions
 *
 * @author Daniel Kinzler
 * @license GNU General Public Licence 3.0 or later
 */

$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'Enricher',
	'author' => array(
		'John Doe',
	),
	'version'  => '0.1.0',
	'url' => 'https://www.mediawiki.org/wiki/Extension:Enricher',
	'descriptionmsg' => 'enricher-desc',
);


/* Setup */

$dir = __DIR__;

// Register files
$wgAutoloadClasses['DataItemParserFunction'] = $dir . '/DataItemParserFunction.php';
$wgAutoloadClasses['DataItemLoader'] = $dir . '/DataItemLoader.php';
$wgAutoloadClasses['DataItemFormatter'] = $dir . '/DataItemFormatter.php';
$wgAutoloadClasses['DataItemLoaderException'] = $dir . '/DataItemLoaderException.php';

$wgExtensionMessagesFiles['Enricher'] = $dir . '/Enricher.i18n.php';

// Register resource loader modules
$wgResourceModules['ext.Enricher.box'] = array(
	'scripts' => array(
		//'modules/ext.Enricher.box.js',
	),
	'styles' => array(
		'modules/ext.Enricher.box.css',
	),
	'messages' => array(
	),
	'dependencies' => array(
	),

	'localBasePath' => $dir,
	'remoteExtPath' => basename( $dir ),
);

// Register a hook to register a parser hook
$wgHooks['ParserFirstCallInit'][] = function ( Parser &$parser ) {
	global $wgEnricherRepoAPI;

	$handler = new DataItemParserFunction(
		new DataItemLoader( $wgEnricherRepoAPI ),
		new DataItemFormatter()
	);

	$parser->setFunctionHook( 'dataitem', array( $handler, 'evaluate' ) );
	return true;
};

/* Configuration */

// Set the URL of the Wikibase API
$wgEnricherRepoAPI = 'http://www.wikidata.org/w/api.php';
