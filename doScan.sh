#!/bin/sh
SCANNER=`scanimage -L | awk -F "\\\`" '{print $2}' | awk -F "'" '{print $1}'`

echo "Scanner: ${SCANNER}"

scanimage --device "${SCANNER}" --mode Gray --source "ADF Front" --format=tiff --batch=`date +%Y-%m-%d-%H-%M-%S-`%d.tif --resolution 300 -p -x 210 -y 297 --quality-cal=yes --quality-scan=yes

for FILE in `ls *.tif`; do
	if [ ! -e "${FILE%.*}.txt" ]; then
		tesseract ${FILE} ${FILE%.*}
	fi;
done;
