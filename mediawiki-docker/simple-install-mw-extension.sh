#! /bin/sh

MWEXTNAME=$1

git clone --depth 1 --single-branch --branch $MWEXTBRANCH \
	https://github.com/wikimedia/mediawiki-extensions-$MWEXTNAME \
	extensions/$MWEXTNAME
