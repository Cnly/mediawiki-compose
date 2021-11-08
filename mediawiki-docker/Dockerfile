FROM mediawiki:1.36.2

WORKDIR /var/www/html

RUN { \
		echo 'upload_max_filesize = 100M'; \
		echo 'post_max_size = 100M'; \
	} > $PHP_INI_DIR/conf.d/mediawiki.ini

# https://www.mediawiki.org/wiki/Manual:Short_URL/Apache
# Note that the default $wgScriptPath seems to be ''
RUN sed -i -E 's|(</VirtualHost>)|	RewriteEngine On\n\1|' "$APACHE_CONFDIR/sites-available/000-default.conf"
RUN sed -i -E 's|(</VirtualHost>)|	RewriteRule ^/?wiki(/.*)?$ %{DOCUMENT_ROOT}/index.php [L]\n\1|' "$APACHE_CONFDIR/sites-available/000-default.conf"
RUN sed -i -E 's|(</VirtualHost>)|	RewriteRule ^/*$ %{DOCUMENT_ROOT}/index.php [L]\n\1|' "$APACHE_CONFDIR/sites-available/000-default.conf"

ARG MWEXTBRANCH=REL1_36

COPY *.sh /

RUN apt update && apt install -y unzip
RUN /composer-install.sh
RUN mv composer.phar /usr/local/bin/composer

RUN git clone --depth 1 \
	https://github.com/StarCitizenTools/mediawiki-skins-Citizen \
	skins/Citizen

RUN git clone --depth 1 \
	https://github.com/jmnote/SimpleMathJax \
	extensions/SimpleMathJax

RUN /simple-install-mw-extension.sh Elastica
RUN cd extensions/Elastica && composer install --no-dev

RUN /simple-install-mw-extension.sh CirrusSearch

RUN /simple-install-mw-extension.sh AdvancedSearch

RUN /simple-install-mw-extension.sh intersection

RUN /simple-install-mw-extension.sh NewestPages

RUN /simple-install-mw-extension.sh CodeMirror

# TODO: DynamicSidebar: Not supported by Citizen?

RUN /simple-install-mw-extension.sh Echo

RUN /simple-install-mw-extension.sh Flow
RUN cd extensions/Flow && composer update --no-dev

RUN /simple-install-mw-extension.sh Linter
RUN /simple-install-mw-extension.sh DiscussionTools

RUN /simple-install-mw-extension.sh HeadScript
