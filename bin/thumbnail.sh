#!/bin/sh
# thumbnail.sh
# 2/2016.0415, BY daimaqiao.
#
# 使用imagemagick生成缩略图片
# 缩略图分类：
# 	normal(300x300，保持比例)，质量20
# 	avastar(60x60，等比裁减)，质量40

TYPE_NORMAL="normal"
TYPE_AVASTAR="avastar"
THUMBNAIL_SUBFIX="-thumbnail"
THUMBNAIL_NORMAL="-thumbnail 200x200"
THUMBNAIL_AVASTAR="-thumbnail 60x60"
QUALITY_NORMAL="-quality 30"
QUALITY_AVASTAR="-quality 50"

# 用法
INPUT=$1
OUTPUT=$2
TYPE=$3
CROP=""
if [ -z "$INPUT" ]; then
	echo "USAGE:" >&2
	echo " " $0 "<original file>" "[<thumbnail file>]" "[normal | avastar]" >&2
	exit 1
fi


# 检查文件名
if [ "$OUTPUT" = "$TYPE_NORMAL" ] || [ "$OUTPUT" = "$TYPE_AVASTAR" ]; then
	TYPE=$OUTPUT
	OUTPUT=""
fi
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
# 检查缩略图类型
if [ "$TYPE" = "$TYPE_AVASTAR" ]; then
	TYPE=$TYPE_AVASTAR
else
	TYPE=$TYPE_NORMAL
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
COMMAND=""
if [ "$TYPE" = "$TYPE_AVASTAR" ]; then
	# 头像图片要求宽高长度一致
	CROP=""
	if [ $WIDTH -gt $HEIGHT ]; then
		CROP="-crop $HEIGHT"x"$HEIGHT+$((($WIDTH-$HEIGHT)/2))+0"
	elif [ $WIDTH -lt $HEIGHT ]; then
		CROP="-crop $WIDTH"x"$WIDTH+0+$((($HEIGHT-$WIDTH)/2))"
	fi
	COMMAND="convert $CROP $INPUT $THUMBNAIL_AVASTAR $QUALITY_AVASTAR $OUTPUT"
elif [ "$TYPE" = "$TYPE_NORMAL" ]; then
	COMMAND="convert $INPUT $THUMBNAIL_NORMAL $QUALITY_NORMAL $OUTPUT"
else
	echo "ERROR: bad type($TYPE)! [$TYPE_NORMAL | $TYPE_AVASTAR]" >&2
	exit 1
fi
echo "CMD: $COMMAND" >&2
$COMMAND
if [ $? -eq 0 ]; then
	echo "INFO: Done!" >&2
	echo $OUTPUT
else
	echo "ERROR: Failed to run the 'convert' command!" >&2
fi


