#!/bin/bash

BASEDIR=${0%/*}
if [ "${BASEDIR}" = "${0}" ]; then
	BASEDIR=`which $0`
	BASEDIR=${BASEDIR%/*}
fi
if [ "${BASEDIR:0:1}" != "/" ]; then
	BASEDIR=${PWD}/${BASEDIR}
fi;

cd ${BASEDIR}
if [ -e "${BASEDIR}/.doOCR-lock" ]; then
	echo "doOCR is already running..."
	exit 1;
else
	touch "${BASEDIR}/.doOCR-lock";
fi;

if [ "${1}" != "" -a -d "${1}" ]; then
	cd ${1}
	for FILE in `ls *.jpg`; do
		convert ${FILE} ${FILE}.tif
		rm -Rf ${FILE}
	done;
	for FILE in `ls *.tif`; do
		tiffsplit ${FILE} ${BASEDIR}/import-`date "+%Y-%m-%d-%H-%M-%S.%N"`-
		rm -Rf ${FILE}
	done;
elif [ "${1}" != "" ]; then
	echo "Unknown Directory."
	exit 1;
fi;

cd ${BASEDIR}

for FILE in `ls *.tif`; do
	if [ ! -e "${FILE}.tesseract.txt" ]; then
		tesseract ${FILE} ${FILE}.tesseract
	fi;
	if [ ! -e "${FILE}.gocr.txt" ]; then
		convert ${FILE} ${FILE}.temp.jpeg
		gocr -o ${FILE}.gocr.txt ${FILE}.temp.jpeg
		rm ${FILE}.temp.jpeg
	fi;
done;

rm -Rf "${BASEDIR}/.doOCR-lock"
