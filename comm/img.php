<?php
$img = $_GET ['img'];
$height = $_GET ['h'];
$width = $_GET ['w'];
$smimg=ResizeImage('../'.$img,$width,$height,'../upload/'.microtime());
header("content-type:image/png");
echo file_get_contents($smimg);

function ResizeImage($imgPath, $maxwidth, $maxheight, $name) {
	$ary = explode ( ".", $imgPath );
	$ext = $ary [count ( $ary ) - 1];

	if ($ext == "jpg" || $ext == "jepg") {
		$im = imagecreatefromjpeg ( $imgPath );
	} elseif ($ext == "png") {
		$im = imagecreatefrompng ( $imgPath );
	} elseif ($ext == "gif") {
		$im = imagecreatefromgif ( $imgPath );
	}
	if ($im) {
		if (file_exists ( "$name.jpg" )) {
			unlink ( "$name.jpg" );
		}
		$width = imagesx ( $im );
		$height = imagesy ( $im );
		if (($maxwidth && $width > $maxwidth) || ($maxheight && $height > $maxheight)) {
			if ($maxwidth && $width > $maxwidth) {
				$widthratio = $maxwidth / $width;
				$RESIZEWIDTH = true;
			}
			if ($maxheight && $height > $maxheight) {
				$heightratio = $maxheight / $height;
				$RESIZEHEIGHT = true;
			}
			if ($RESIZEWIDTH && $RESIZEHEIGHT) {
				if ($widthratio < $heightratio) {
					$ratio = $widthratio;
				} else {
					$ratio = $heightratio;
				}
			} elseif ($RESIZEWIDTH) {
				$ratio = $widthratio;
			} elseif ($RESIZEHEIGHT) {
				$ratio = $heightratio;
			}
			$newwidth = $width * $ratio;
			$newheight = $height * $ratio;
			if (function_exists ( "imagecopyresampled" )) {
				$newim = imagecreatetruecolor ( $newwidth, $newheight );
				imagecopyresampled ( $newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height );
			} else {
				$newim = imagecreate ( $newwidth, $newheight );
				imagecopyresized ( $newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height );
			}
			ImageJpeg ( $newim, $name . ".jpg" );
			ImageDestroy ( $newim );
		} else {
			ImageJpeg ( $im, $name . ".jpg" );
		}
		ImageDestroy ( $im );
	}
	return $name . ".jpg";
}
?>