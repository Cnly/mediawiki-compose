FROM mediawiki:1.36.1

WORKDIR /var/www/html

RUN { \
		echo 'upload_max_filesize = 100M'; \
		echo 'post_max_size = 100M'; \
	} > $PHP_INI_DIR/conf.d/mediawiki.ini

ARG MWEXTBRANCH=REL1_36

RUN git clone --depth 1 \
	https://github.com/StarCitizenTools/mediawiki-skins-Citizen \
	skins/Citizen

RUN git clone --depth 1 \
	https://github.com/jmnote/SimpleMathJax \
	extensions/SimpleMathJax

# TODO: Installing Elastica from git requires additional actions (composer)
# RUN git clone --depth 1 --single-branch --branch $MWEXTBRANCH \
# 	https://github.com/wikimedia/mediawiki-extensions-Elastica \
# 	extensions/Elastica

RUN curl -o exttmp.tar.gz -L \
		https://extdist.wmflabs.org/dist/extensions/Elastica-REL1_36-9f6e66a.tar.gz && \
	tar -xzf exttmp.tar.gz -C extensions && \
	rm exttmp.tar.gz

RUN env MWEXTNAME=CirrusSearch \
	sh -c 'git clone --depth 1 --single-branch --branch $MWEXTBRANCH \
		https://github.com/wikimedia/mediawiki-extensions-$MWEXTNAME \
		extensions/$MWEXTNAME'

RUN env MWEXTNAME=AdvancedSearch \
	sh -c 'git clone --depth 1 --single-branch --branch $MWEXTBRANCH \
		https://github.com/wikimedia/mediawiki-extensions-$MWEXTNAME \
		extensions/$MWEXTNAME'

RUN env MWEXTNAME=intersection \
	sh -c 'git clone --depth 1 --single-branch --branch $MWEXTBRANCH \
		https://github.com/wikimedia/mediawiki-extensions-$MWEXTNAME \
		extensions/$MWEXTNAME'

RUN env MWEXTNAME=NewestPages \
	sh -c 'git clone --depth 1 --single-branch --branch $MWEXTBRANCH \
		https://github.com/wikimedia/mediawiki-extensions-$MWEXTNAME \
		extensions/$MWEXTNAME'

RUN env MWEXTNAME=CodeMirror \
	sh -c 'git clone --depth 1 --single-branch --branch $MWEXTBRANCH \
		https://github.com/wikimedia/mediawiki-extensions-$MWEXTNAME \
		extensions/$MWEXTNAME'

# TODO: Not supported by Citizen?
# RUN env MWEXTNAME=DynamicSidebar \
# 	sh -c 'git clone --depth 1 --single-branch --branch $MWEXTBRANCH \
# 		https://github.com/wikimedia/mediawiki-extensions-$MWEXTNAME \
# 		extensions/$MWEXTNAME'
