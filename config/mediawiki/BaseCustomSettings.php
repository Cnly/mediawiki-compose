<?php

# For debugging
# error_reporting( -1 );
# ini_set( 'display_errors', 1 );
# $wgShowExceptionDetails = true;
# $wgShowDBErrorBacktrace = true;
# $wgDebugToolbar = true;

$wgInternalServer = "http://localhost";

$wgArticlePath = "/wiki/$1";

$wgMaxUploadSize = 100 * 1024 * 1024;

$wgLogos = [ '1x' => "$wgResourceBasePath/resources/external/MediaWiki-2020-icon.svg" ];
$wgFavicon = "$wgResourceBasePath/resources/external/MediaWiki-2020-small-icon.svg";

$wgDefaultSkin = "citizen";

wfLoadSkin( 'Citizen' );

wfLoadExtension( 'SimpleMathJax' );
$wgSmjDisplayMath = [['$$','$$'],['\[','\]']];
$wgSmjExtraInlineMath = [['$', '$'], ['\(', '\)']];

wfLoadExtension( 'Elastica' );
# Fresh install may need manual bootstrapping: https://www.mediawiki.org/wiki/Extension:CirrusSearch#CirrusSearch
wfLoadExtension( 'CirrusSearch' );
$wgCirrusSearchServers = [ 'elasticsearch' ];
$wgSearchType = 'CirrusSearch';

wfLoadExtension( 'AdvancedSearch' );

wfLoadExtension( 'SyntaxHighlight_GeSHi' );

wfLoadExtension( 'intersection' );

wfLoadExtension( 'NewestPages' );

# TODO: Not supported by Citizen?
# wfLoadExtension( 'DynamicSidebar' );

wfLoadExtension( 'Poem' );

wfLoadExtension( 'Scribunto' );
$wgScribuntoDefaultEngine = 'luastandalone';

wfLoadExtension( 'InputBox' );

wfLoadExtension( 'VisualEditor' );
$wgVisualEditorEnableDiffPage = true;

wfLoadExtension( 'WikiEditor' );
$wgDefaultUserOptions['usebetatoolbar'] = 1;  # For CodeEditor

wfLoadExtension( 'CodeMirror' );
$wgDefaultUserOptions['usecodemirror'] = 1;

wfLoadExtension( 'CodeEditor' );

wfLoadExtension( 'CategoryTree' );

wfLoadExtension( 'Echo' );  # Requires database changes
wfLoadExtension( 'ParserFunctions' );
wfLoadExtension( 'Flow' );  # Requires database changes
$wgNamespaceContentModels[NS_TALK] = 'flow-board';
$wgNamespaceContentModels[NS_USER_TALK] = 'flow-board';

wfLoadExtension( 'Linter' );  # Requires database changes
wfLoadExtension( 'DiscussionTools' );  # Requires database changes
$wgFragmentMode = [ 'html5' ];
