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
$wgVisualEditorEnableWikitext = true;
$wgDefaultUserOptions['visualeditor-newwikitext'] = 1;

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

wfLoadExtension( 'HeadScript' );
$headScriptReferrerPolicy = <<< 'START_END_MARKER'
<meta name="referrer" content="same-origin">
START_END_MARKER;
$headScriptExternalFonts = <<< 'START_END_MARKER'
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
START_END_MARKER;
$headScriptFontStyles = <<< 'START_END_MARKER'
<style>
@font-face {
	font-family: Hiragino;
	font-weight: 400;
	src: local(Hiragino Sans W3), local(Hiragino Sans CNS), local(Hiragino Sans GB);
}
@font-face {
	font-family: Hiragino;
	font-weight: 700;
	src: local(Hiragino Sans W6), local(Hiragino Sans CNS), local(Hiragino Sans GB);
}
body {
	--font-family-base: Inter, Hiragino, sans-serif;
}
body.ios-fonts {
	--font-family-base: Inter, 'PingFang TC', 'PingFang SC', sans-serif;
}
</style>
<script>
// Since iOS lacks fonts like Hiragino CNS, GB, etc.
document.addEventListener("DOMContentLoaded", function(event) {
	let isIOS = /iPad|iPhone|iPod/.test(navigator.platform) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
	if (isIOS)
		document.body.className += ' ios-fonts';
});
</script>
START_END_MARKER;
$wgHeadScriptCode = $headScriptReferrerPolicy . $headScriptExternalFonts . $headScriptFontStyles;
