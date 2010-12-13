#! /bin/bash

GIT_PATH=git@github.com:Godefroy/iseplive.git
DIR_EXPORT=app-export
DIR_REPO=app-repo
DIR_BACKUP=app-backup
DIR_PROD=app
DATE=`date +%Y-%m-%d-%s`
PREVDIR=`pwd`

cd $(dirname $0)/../..
DIR_ROOT=`pwd`

rm -rf $DIR_EXPORT

if [ ! -d $DIR_REPO ]; then
	git clone $GIT_PATH $DIR_REPO
fi

echo "Updating repository..."

cd $DIR_REPO
git pull origin master
cd $DIR_ROOT

echo "Export..."

cp -R $DIR_REPO $DIR_EXPORT

rm -r $DIR_EXPORT/.git*
ls $DIR_EXPORT | grep [A-Z] | xargs -I {} rm -rf $DIR_EXPORT/{}

echo "JS and CSS compression..."

/bin/bash $DIR_EXPORT/cli/minify.sh $DIR_EXPORT $DIR_PROD static/js js
/bin/bash $DIR_EXPORT/cli/minify.sh $DIR_EXPORT $DIR_PROD static/css css
ls $DIR_EXPORT/static/js/[0-9]-*.js | grep -v _src | xargs cat > $DIR_EXPORT/static/js/script.js
ls $DIR_EXPORT/static/css/[0-9]-*.css | grep -v _src | xargs cat > $DIR_EXPORT/static/css/style.css

chown -R www-data:www-data $DIR_EXPORT
chmod -R 770 $DIR_EXPORT

echo "Deployment..."

# Backup
mv $DIR_PROD $DIR_BACKUP-$DATE

# Deployment
mv $DIR_EXPORT $DIR_PROD

# Delete old backups
ls | grep backup | head -n -5 | xargs rm -r

echo "Deployment OK !"

cd $PREVDIR
