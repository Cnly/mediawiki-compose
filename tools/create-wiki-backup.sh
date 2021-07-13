#! /bin/bash
# https://www.mediawiki.org/wiki/Manual:Backing_up_a_wiki

set -euo pipefail

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
TIMESTAMP=$(date +'%Y%m%d-%H%M%S')

DB_USER=${DB_USER:-wikiuser}
DB_PASSWORD=${DB_PASSWORD:-wikipass}
OUTPUT_FILE=${OUTPUT_FILE:-$SCRIPT_DIR/../backup/wiki-backup-$TIMESTAMP.tar}
BACKUP_TMP_DIR=${BACKUP_TMP_DIR:-$SCRIPT_DIR/../backup/$TIMESTAMP-tmp}

cd $SCRIPT_DIR/..
mkdir -p $(dirname $OUTPUT_FILE)
mkdir -p $BACKUP_TMP_DIR

# Backup database
docker-compose exec -T database \
	nice -n 10 mysqldump -u $DB_USER --password=$DB_PASSWORD --all-databases --single-transaction | \
	nice -n 10 gzip > "$BACKUP_TMP_DIR"/wiki.sql.gz

# Backup files
nice -n 10 tar --preserve-permissions -acf "$BACKUP_TMP_DIR"/fs.tar.xz data/mediawiki

# Backup config
cp config/mediawiki/LocalSettings.php "$BACKUP_TMP_DIR"

cd "$BACKUP_TMP_DIR"
tar -acf $OUTPUT_FILE *
rm -rf "$BACKUP_TMP_DIR"

echo "$(realpath $OUTPUT_FILE)"
