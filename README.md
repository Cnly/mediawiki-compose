# Introduction

This docker-compose application is a MediaWiki instance with a reasonable set of default settings, extensions, and services intended for wiki sites for individuals or small teams. It has the following components (services):

* MediaWiki, with:
  * Default skin: Citizen
  * Extensions: SimpleMathJax, Elastica, CirrusSearch, AdvancedSearch, SyntaxHighlight_GeSHi, intersection, NewestPages, Poem, Scribunto, InputBox, VisualEditor, WikiEditor, CodeMirror, CodeEditor, CategoryTree, and [more](config/mediawiki/BaseCustomSettings.php)
  * Short URL configured as `/wiki`
  * Default maximum upload size of 100M
* MariaDB
* ElasticSearch (provides enhanced search experience)
* Cloudflared (so the site can use Cloudflare Access and other services)



# Deployment

The deployment process is briefly divided into a few steps:

1. Run the services for the first time and configure MediaWiki.
2. Adjust some configuration so services run as expected.
3. Initialise the database and search index.
4. Configure Cloudflared (Optional, but useful if you’re deploying on a NATed machine and wants public access, or if you’d like to utilise services like Cloudflare Access).



## *1.* Initialising Services

To begin deploying, clone this repo first:

```sh
git clone https://github.com/Cnly/mediawiki-compose [<wiki name>]
```

You may want to customise `<wiki name>` as docker-compose will later name the containers, etc. with this as the prefix.

Now open `docker-compose.yml` with your favourite editor. **Comment** out the volume definition line that mounts `LocalSettings.php` because we need to let MediaWiki generate that file first:

```yaml
services:
  mediawiki:
    volumes:
      # ...
      # Comment out the following line:
      # - ./config/mediawiki/LocalSettings.php:/var/www/html/LocalSettings.php:ro
```

Also, we need to set up a **port mapping** so we can access our MediaWiki container before it gets accessibility on the Internet with Cloudflared:

```yaml
services:
  mediawiki:
    ports:
      - 8080:80
```

Fire up the services:

```sh
docker-compose up
```

We start all of them instead of just MediaWiki since this action will also create mountpoints automatically which we need to adjust later. Now **navigate** to http://localhost:8080 to complete the first-time configuration of MediaWiki. Refer to `docker-compose.yml` for database credentials. **Download** the generated `LocalSettings.php` afterwards and put it in `./config/mediawiki`.



## *2.* Adjusting Configuration

**Open** `LocalSettings.php` and do the following:

* Edit the value of [`$wgServer`](https://www.mediawiki.org/wiki/Manual:$wgServer) so it’s protocol-relative (should then look something like `//your.domain.tld` where `your.domain.tld` is the domain of your wiki instance). This makes it easier for an HTTPS reverse proxy (e.g. Cloudflared) to work.
* Add a line to define [`$wgLocaltimezone`](https://www.mediawiki.org/wiki/Manual:$wgLocaltimezone). This is required by the [DiscussionTools](https://www.mediawiki.org/wiki/Extension:DiscussionTools) extension.
* Also, append this line at the end so config options provided by this repo are loaded:

```php
include 'BaseCustomSettings.php';
```

**Fix** the permissions of a few mountpoints:

```sh
sudo chmod 777 config/cloudflared
sudo chmod 777 -R data
```

**Create** a file named `docker-compose.override.yml` that contains:

```yaml
version: '3'
services:
  mediawiki:
    extra_hosts:
      your.domain.tld: 127.0.0.1
```

where `your.domain.tld` is the domain for your wiki. This is needed so things don’t go wrong when extensions like VisualEditor try to access the MediaWiki APIs via the domain.

Finally **uncomment** the line mentioned in the first step (and **remove** the port mapping if you don’t need it) and redeploy the services with `docker-compose down && docker-compose up`.



## *3.* Initialising the Database and Search Index

**Drop** into a shell in the MediaWiki container:

```sh
docker exec -it <wiki name>_mediawiki_1 bash
```

Then **execute** the following commands (you may also want to take a look at the extension):

```sh
# Update database schemes as needed by some extensions (may take a while)
# Note: This may also be needed after certain future updates
php maintenance/update.php --quick

# Populate the Elasticsearch index as required by CirrusSearch
# See: https://gerrit.wikimedia.org/g/mediawiki/extensions/CirrusSearch/%2B/HEAD/README
php extensions/CirrusSearch/maintenance/UpdateSearchIndexConfig.php
php extensions/CirrusSearch/maintenance/ForceSearchIndex.php --skipLinks --indexOnSkip
php extensions/CirrusSearch/maintenance/ForceSearchIndex.php --skipParse
```



## *4.* Configuring Cloudflared (Optional)

You can use Cloudflared to:

* Provide public accessibility for the wiki.
* Provide HTTPS connection.
* Make the wiki reachable on the Internet even if it’s hosted on a NATed machine.
* (With Cloudflare Access) Provide authentication.

However, this document won’t talk too much about this service. Read the [Cloudflare Docs](https://developers.cloudflare.com/cloudflare-one/connections/connect-apps/install-and-setup) to learn more. In short, you need to **write** a [configuration file](https://developers.cloudflare.com/cloudflare-one/connections/connect-apps/configuration/config) at `./config/cloudflared/config.yml`, and **configure** the tunnel with the Cloudflared docker image (or alternatively you can install Cloudflared manually):

```sh
docker run -it --rm -v `pwd`/config/cloudflared:/home/nonroot/.cloudflared cloudflare/cloudflared tunnel login
docker run -it --rm -v `pwd`/config/cloudflared:/home/nonroot/.cloudflared cloudflare/cloudflared tunnel create <tunnel name>
docker run -it --rm -v `pwd`/config/cloudflared:/home/nonroot/.cloudflared cloudflare/cloudflared tunnel route dns <tunnel name> <domain>
```



## Done!

Now you should have a working MediaWiki instance.



# Appendices

## Configuring the Amount of RAM used by Elasticsearch

This can be done in `docker-compose.override.yml`:

```yaml
services:
  elasticsearch:
    environment:
      ES_JAVA_OPTS: "-Xmx512m -Xms512m"
```



## Running Cloudflared without `cert.pem`

This is possible according to the [docs](https://blog.cloudflare.com/argo-tunnels-that-live-forever/#3-configure-tunnel-details). Just delete that file and specify the tunnel to run using UUID in `docker-compose.override.yml`:

```yaml
services:
  cloudflared:
    command:
      - tunnel
      - run
      - <UUID>
```
