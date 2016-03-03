#!/bin/sh
# thumbnail.sh
# 1/2016.0303, BY shb.
#
# 使用imagemagick生成缩略图片
# 缩略图片的尺寸不超过160x160，质量40，保持宽高比

THUMBNAIL_SUBFIX="-thumbnail"
THUMBNAIL="-thumbnail 160x160"
QUALITY="-quality 40"

# 用法
INPUT=$1
OUTPUT=$2
if [ -z "$INPUT" ]; then
	echo "USAGE:" >&2
	echo " " $0 "<original file>" "[<thumbnail file>]" >&2
	exit 1
fi

# 检查文件名
if [ -z "$OUTPUT" ]; then
	FILE_MAIN=${INPUT%.*}
	FILE_EXT=${INPUT##*.}
	if [ "$FILE_EXT" = "$FILE_MAIN" ]; then
		OUTPUT=$INPUT$THUMBNAIL_SUBFIX
	else
		FILE_EXT="."$FILE_EXT
		OUTPUT=$FILE_MAIN$THUMBNAIL_SUBFIX$FILE_EXT
	fi
fi

# 检查imagemagick工具
if [ -z $(which identify) ] || [ -z $(which convert) ]; then
	echo "Please install the 'imagemagick' first!" >&2
	exit 1
fi

# 获取原始图片尺寸
SIZE=$(identify $INPUT 2>&- | awk '{print $3}')
echo "INFO: Size=" $SIZE >&2
if [ -z "$SIZE" ]; then
	echo "ERROR: Bad image file!" >&2
	exit 2
fi
WIDTH=$(echo $SIZE | cut -d x -f 1)
HEIGHT=$(echo $SIZE | cut -d x -f 2)
echo "INFO: Width/Height=" $WIDTH"/"$HEIGHT >&2

if [ -z "$WIDTH" ] || [ -z "HEIGHT" ]; then
	echo "ERROR: Bad image size("$SIZE")!" >&2
	exit 2
fi

# 生成缩略图片
COMMAND="convert $INPUT $THUMBNAIL $QUALITY $OUTPUT"
echo "CMD: $COMMAND" >&2
$COMMAND
if [ $? -eq 0 ]; then
	echo "INFO: Done!" >&2
	echo $OUTPUT
else
	echo "ERROR: Failed to run the 'convert' command!" >&2
fi

