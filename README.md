Wikidata Enricher
=================

A MediaWiki extension for enriching pages with information from Wikidata.

This extension was created for the Wikidata API workshop at the SMWCon 2013
in Berlin. It demonstrates using the Wikibase API for including information
from wikidata.org on pages of third party wikis.

To activate the extension in your wiki, first rename the directory containing
the Enricher extension to "Enricher", and place it in MediaWiki's "extension"
directory. Then, put the following into your LocalSettings.php:

    require_once( "$IP/extensions/Enricher/Enricher.php" );

You should also enable the InstantCommons feature, so media files from Wikimedia
Commons can easily be used:

    $wgUseInstantCommons = true;

To associate a wiki page with a data item on wikidata.org, use the dataitem
parser function like so:

    {{#dataitem:Q64}}

This will generate an info box for the given data item using information from
wikidata.org. The appropriate Q-number needs to be looked up on Wikidata.
