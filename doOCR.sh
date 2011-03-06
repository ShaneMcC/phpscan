#!/bin/sh

BASEDIR=${0%/*}
if [ "${BASEDIR}" = "${0}" ]; then
	BASEDIR=`which $0`
	BASEDIR=${BASEDIR%/*}
fi
if [ "${BASEDIR:0:1}" != "/" ]; then
	BASEDIR=${PWD}/${BASEDIR}
fi;

cd ${BASEDIR}

for FILE in `ls *.tif`; do
	if [ ! -e "${FILE}.tesseract.txt" ]; then
		tesseract ${FILE} ${FILE}
	fi;
	if [ ! -e "${FILE}.gocr.txt" ]; then
		convert ${FILE} ${FILE}.temp.jpeg
		gocr -o ${FILE}.gocr.txt ${FILE}.temp.jpeg
		rm ${FILE}.temp.jpeg
	fi;
done;
