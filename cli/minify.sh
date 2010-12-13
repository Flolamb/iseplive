#! /bin/bash
#
# Minify JS or CSS files in a dir, without re-minifying files already minified in previous version
# 
# @example ./minify.sh source_path comparison_path dir_path type
# @example ./minify.sh export www static/js js

if test -z "$1" ; then
	echo "Missing parameter: Path of the source root"
	exit;
fi

if test -z "$2" ; then
	echo "Missing parameter: Path of the comparison version root"
	exit;
fi

if test -z "$3" ; then
	echo "Missing paramter: Relative path of the dir"
	exit;
fi

if test -z "$4" ; then
	echo "Missing parameter: Files type (js or css)"
	exit;
fi

BASE_DIR=$(dirname $0)/..
DIR_SRC=$1/$3
DIR_DST=$2/$3
TYPE=$4
EXTENSION=$TYPE

# Searching files
for FILE in `find $DIR_SRC -name "*.$EXTENSION" -type f`
do
	# If the file has already a minified version => skip
	if test `echo $FILE | grep _src\.$EXTENSION` ; then
		continue
	fi
	
	FILE_SRC=`echo $FILE | sed "s/\.$EXTENSION$/_src.$EXTENSION/"`
	FILE_CMP=$DIR_DST/`echo $FILE | cut -c$((${#DIR_SRC}+1))-`
	FILE_CMP_SRC=$DIR_DST/`echo $FILE_SRC | cut -c$((${#DIR_SRC}+1))-`
	
	# If the file has a corresponding source file, it's minified => skip
	if test -f $FILE_SRC ; then
		continue
	fi
	
	# If the source and minified files exist in the comparison dir
	if test -f $FILE_CMP_SRC -a -f $FILE_CMP ; then
		# If the two source files a identical, copy the minified file
		if test -z "`cmp $FILE $FILE_CMP_SRC`" ; then
			mv $FILE $FILE_SRC
			cp $FILE_CMP $FILE
			continue
		fi
	fi
	
	echo "Optimization $FILE"
	RENAMED_FILE=`echo $FILE | sed -e "s/\.$TYPE$/_src.$TYPE/"`
	cp $FILE $RENAMED_FILE
	java -jar $BASE_DIR/cli/yuicompressor.jar --type $TYPE $FILE -o $FILE
done
