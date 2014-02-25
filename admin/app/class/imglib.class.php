<?php
/*
Library Coded By KnF (knifesk@gmail.com)
Feel free to modify, redistribute or even use :)

=======================================
Properties
=======================================
$width		= width of the output image
$height		= height of the output image
$maxWidth 	= this parameter is used to control Aspect ratio in the rezise of an image.
			  Ex: if you want to leave a margin between the old image and the background color
			  set this less than the width

$crop 		= crops the original image if the AS is different from the thumb
$fill		= if crop is false this will fill the background with a solid color with $fillCol
$fillCol 	= Default = '#FFFFFF';

Note: if you dont want to crop the original image or don't want fill the background -> Ex: rezise to fixed
width or height keeping the AS, just don specify the width or height


=======================================
Methods
=======================================


=======================================
Error Types
=======================================
error_unsupported_type
error_resource_create
error_fill
error_rezise
error_print_text
error_allocate_color
error_copy_res
error_missing_file
error_opening
error_opening_water
error_opening_logo
error_save



*/

class imglib {
	/*Variables Por defecto*/
	var $width		= 0; //ancho de salida por defecto
	var $height		= 0; //alto de salida por defecto
	var $maxWidth 	= 0; //ancho maximo de la imagen en la imagen destino

	var $crop 	= false; //recorta la imagen si excede? {true|false} -> def: false;
	var $fill	= true; //rellena el fondo con un color sólio? {true|false} -> def: true;
	var $fillCol = '#FFFFFF';

	var $filePath	= '';
	var $picData;
	var $ow = 0;
	var $oh = 0;
	/*Constructor, open the image and store*/
	function __construct($path) { //constructor
		ini_set('max_execution_time', '120');
		ini_set('memory_limit', '256M');

		if (!file_exists($path)) return $this->error('error_missing_file');

		$this->filePath = $path; //guardo la ruta del archivo original, nose todavia para que, pero por las dudas
		
    if ( ! function_exists( 'exif_imagetype' ) ) {
        function exif_imagetype ( $filename ) {
            if ( ( list($width, $height, $type, $attr) = getimagesize( $filename ) ) !== false ) {
                return $type;
            }
        return false;
        }
    }


    $this->type = exif_imagetype($path);

		switch($this->type) { //abro la imagen original y la almaceno en $picData
			case IMAGETYPE_GIF : $this->picData = @imagecreatefromgif($path); break;
			case IMAGETYPE_PNG : $this->picData = @imagecreatefrompng($path); break;
			case IMAGETYPE_JPEG: $this->picData = @imagecreatefromjpeg($path); break;
			case IMAGETYPE_BMP : $this->picData = $this->ImageCreateFromBmp($path); break;
			default: return $this->error('error_unsupported_type');
		}

		if ($this->picData === false) return $this->error('error_opening');

		list($this->ow, $this->oh) = getimagesize($path); //obtengo el tamaño original de la imagen
		$this->o_as = $this->ow / $this->oh;
	}

	function __destruct() {
		imagedestroy($this->picData); //release the image
		unset($this->picData);
	}

	function reSize() {
		/*si no se definio alto o ancho calculo en base al otro*/
		if ($this->height == 0) {
				$this->height = ceil($this->width / $this->o_as);
		} else if ($this->width == 0) {
				$this->width = ceil($this->height * $this->o_as);
		}

		/*calculo el AspectRatio*/
		$as   = $this->width / $this->height;

		/*creo la imagen nueva*/
		$im = @imagecreatetruecolor($this->width, $this->height);

		if ($im === false) return $this->error('error_resource_create');

		/*si se tiene que rellenar el fondo lo hago*/
		if ($this->fill) {
			$bgcolor = $this->parseColor($this->fillCol);
			$bgc = @imagecolorallocate($im, $bgcolor['r'], $bgcolor['g'], $bgcolor['b']);

			if ($bgc === false) return $this->error('error_allocate_color');
			if (!@imagefill($im, 0, 0, $bgc)) return $this->error('error_fill');
		}

		/* calculamos los nuevos tamaños, relaciones, lalalas y demases */
		if (($this->ow > $this->width) || ($this->oh > $this->height)) { //See if we actually need to do anything

			if ($this->o_as <= $as) {							//If the aspect ratio of the uploaded image is less than or equal to the target size...
				if (!$this->crop) {
					$newWidth = $this->height * $this->o_as; 	//Resize the image based on the height
					$newHeight = $this->height;
				} else {
					$newWidth = $this->width; 					//Resize based on width
					$newHeight = $this->width / $this->o_as;
				}
			} else { 											//If the ratio is greater...
				if (!$this->crop) {
					$newWidth = $this->width; 					//Resize based on width
					$newHeight = $this->width / $this->o_as;
				} else {
					$newWidth = $this->height * $this->o_as; 	//Resize the image based on the height
					$newHeight = $this->height;
				}
			}

		} else {
			$newWidth = $this->ow;
			$newHeight = $this->oh;
		}

		if ($this->maxWidth != 0 && $newWidth > $this->maxWidth) {
			$newWidth = $this->maxWidth;
			$newHeight = $this->maxWidth / $this->o_as;
		}


		/*ahora calculo el centro de la imagen*/
		$centro_x = ceil($this->width / 2);
		$centro_y = ceil($this->height / 2);

		$pos_y = $centro_y - ($newHeight / 2);
		$pos_x = $centro_x - ($newWidth / 2);

		if (($newWidth < $newHeight) && ($this->crop)) $pos_y = 0;

		//le calzo la imagen reziseada al fondo
		$rz = @imagecopyresampled($im, $this->picData, $pos_x, $pos_y, 0, 0, $newWidth, $newHeight, $this->ow, $this->oh);

		if (!$rz) return $this->error('error_rezise');

		//y para terminar guardo la imagen nueva en la imagen principal
		@imagedestroy($this->picData);


		$this->picData = imagecreatetruecolor($this->width, $this->height);

		if ($this->picData === false) return $this->error('error_resource_create');
		@imagecopy($this->picData, $im, 0, 0, 0, 0, $this->width, $this->height);
		if ($this->picData === false) return $this->error('error_copy_res');
		//y destruyo el temporal
		@imagedestroy($im);
		return true;
	}
	function printText($text, $pos_x, $pos_y, $fontColor = '#FFFFFF', $fontSize = 3) {
		$fcolor = $this->parseColor($fontColor);
		$fcolor = @imagecolorallocate($this->picData, $fcolor['r'], $fcolor['g'], $fcolor['b']);
		if ($fcolor === false) return $this->error('error_allocate_color');
		if (!imagestring($this->picData, $fontSize, $pos_x, $pos_y, $text, $fcolor)) return $this->error('error_print_text');
	}
	function printLogo($logoFile, $pos_x, $pos_y) {
		if (preg_match('/([Gg][Ii][Ff]|[Jj][Pp][Ee]?[Gg]|[Pp][Nn][Gg])$/m', $logoFile, $ext)) {
			$extension = strtoupper($ext[1]);//averiguo la ext
		}

		switch($extension) { //abro la imagen original y la almaceno en $picData
		case 'GIF':	$lupa = @imagecreatefromgif($logoFile); break;
		case 'PNG': $lupa = @imagecreatefrompng($logoFile); break;
		default	  : $lupa = @imagecreatefromjpeg($logoFile); break; //por defecto jpeg
		}

		if ($lupa === false) return $this->error('error_opening_logo');

		list($lw, $lh) = @getimagesize($logoFile);

		if (!@imagecopy($this->picData, $lupa, $pos_x, $pos_y, 0, 0, $lw, $lh)) return $this->error('error_copy_res');;
	}
	function waterMark($pngFile, $pos_x, $pos_y, $transp = 30) {
		$wi = @imagecreatefrompng($pngFile);
		if ($wi === false) return $this->error('error_opening_water');

		list($ww, $wh) = @getimagesize($pngFile); //obtengo el tamaño original de la imagen

		if (!@imagecopyresampled($this->picData, $wi, $pos_x, $pos_y, 0, 0, $ww, $wh, $ww, $wh)) return $this->error('error_copy_res');
		//$ww, $wh, ,
		@imagedestroy($wi);
	}
	function save($ext, $outFile = 'thumb.jpg', $quality = 75){
		switch($ext){
		case 'JPEG': $res = @imagejpeg($this->picData, $outFile, $quality); break;
		case 'PNG': $res = @imagepng($this->picData, $outFile); break;
		case 'GIF': $res = @imagegif($this->picData, $outFile); break;
		}

		if (!$res) return $this->error('error_save');
		return true;
		//imagedestroy($this->picData); //release the image
	}

	/*utiles*/
	function parseColor($strColor) {
		if (preg_match('/#([0-9A-Fa-f]{2})([0-9A-Fa-f]{2})([0-9A-Fa-f]{2})/', $strColor, $cols)) {
				$rgb = array(
					'r' => base_convert($cols[1], 16, 10),
					'g' => base_convert($cols[2], 16, 10),
					'b' => base_convert($cols[3], 16, 10)
				);
				return $rgb;
		} else return false;
	}
	function error($errorType) {
		echo '//Se produjo un error al realizar una operacion, el codigo de error es: '.$errorType."\n";
		return false;
	}

//////////////////////////
/////////////////////////
/**
 *
 * @convert BMP to GD
 *
 * @param string $src
 *
 * @param string|bool $dest
 *
 * @return bool
 *
 */
function bmp2gd($src, $dest = false)
{
    /*** try to open the file for reading ***/
    if(!($src_f = fopen($src, "rb")))
    {
        return false;
    }

/*** try to open the destination file for writing ***/
if(!($dest_f = fopen($dest, "wb")))
    {
        return false;
    }

/*** grab the header ***/
$header = unpack("vtype/Vsize/v2reserved/Voffset", fread( $src_f, 14));

/*** grab the rest of the image ***/
$info = unpack("Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vimportant",
fread($src_f, 40));

/*** extract the header and info into varibles ***/
extract($info);
extract($header);

/*** check for BMP signature ***/
if($type != 0x4D42)
{
    return false;
}

/*** set the pallete ***/
$palette_size = $offset - 54;
$ncolor = $palette_size / 4;
$gd_header = "";

/*** true-color vs. palette ***/
$gd_header .= ($palette_size == 0) ? "\xFF\xFE" : "\xFF\xFF";
$gd_header .= pack("n2", $width, $height);
$gd_header .= ($palette_size == 0) ? "\x01" : "\x00";
if($palette_size) {
$gd_header .= pack("n", $ncolor);
}
/*** we do not allow transparency ***/
$gd_header .= "\xFF\xFF\xFF\xFF";

/*** write the destination headers ***/
fwrite($dest_f, $gd_header);

/*** if we have a valid palette ***/
if($palette_size)
{
    /*** read the palette ***/
    $palette = fread($src_f, $palette_size);
    /*** begin the gd palette ***/
    $gd_palette = "";
    $j = 0;
    /*** loop of the palette ***/
    while($j < $palette_size)
    {
        $b = $palette{$j++};
        $g = $palette{$j++};
        $r = $palette{$j++};
        $a = $palette{$j++};
        /*** assemble the gd palette ***/
        $gd_palette .= "$r$g$b$a";
    }
    /*** finish the palette ***/
    $gd_palette .= str_repeat("\x00\x00\x00\x00", 256 - $ncolor);
    /*** write the gd palette ***/
    fwrite($dest_f, $gd_palette);
}

/*** scan line size and alignment ***/
$scan_line_size = (($bits * $width) + 7) >> 3;
$scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size & 0x03) : 0;

/*** this is where the work is done ***/
for($i = 0, $l = $height - 1; $i < $height; $i++, $l--)
{
    /*** create scan lines starting from bottom ***/
    fseek($src_f, $offset + (($scan_line_size + $scan_line_align) * $l));
    $scan_line = fread($src_f, $scan_line_size);
    if($bits == 24)
    {
        $gd_scan_line = "";
        $j = 0;
        while($j < $scan_line_size)
        {
            $b = $scan_line{$j++};
            $g = $scan_line{$j++};
            $r = $scan_line{$j++};
            $gd_scan_line .= "\x00$r$g$b";
        }
    }
    elseif($bits == 8)
    {
        $gd_scan_line = $scan_line;
    }
    elseif($bits == 4)
    {
        $gd_scan_line = "";
        $j = 0;
        while($j < $scan_line_size)
        {
            $byte = ord($scan_line{$j++});
            $p1 = chr($byte >> 4);
            $p2 = chr($byte & 0x0F);
            $gd_scan_line .= "$p1$p2";
        }
        $gd_scan_line = substr($gd_scan_line, 0, $width);
    }
    elseif($bits == 1)
    {
        $gd_scan_line = "";
        $j = 0;
        while($j < $scan_line_size)
        {
            $byte = ord($scan_line{$j++});
            $p1 = chr((int) (($byte & 0x80) != 0));
            $p2 = chr((int) (($byte & 0x40) != 0));
            $p3 = chr((int) (($byte & 0x20) != 0));
            $p4 = chr((int) (($byte & 0x10) != 0));
            $p5 = chr((int) (($byte & 0x08) != 0));
            $p6 = chr((int) (($byte & 0x04) != 0));
            $p7 = chr((int) (($byte & 0x02) != 0));
            $p8 = chr((int) (($byte & 0x01) != 0));
            $gd_scan_line .= "$p1$p2$p3$p4$p5$p6$p7$p8";
        }
    /*** put the gd scan lines together ***/
    $gd_scan_line = substr($gd_scan_line, 0, $width);
    }
    /*** write the gd scan lines ***/
    fwrite($dest_f, $gd_scan_line);
}
/*** close the source file ***/
fclose($src_f);
/*** close the destination file ***/
fclose($dest_f);

return true;
}

	/**
	 *
	 * @ceate a BMP image
	 *
	 * @param string $filename
	 *
	 * @return bin string on success
	 *
	 * @return bool false on failure
	 *
	 */
	function ImageCreateFromBmp($filename)
	{
		/*** create a temp file ***/
		$tmp_name = tempnam("/tmp", "GD");
		/*** convert to gd ***/
		if($this->bmp2gd($filename, $tmp_name))
		{
			/*** create new image ***/
			$img = imagecreatefromgd($tmp_name);
			/*** remove temp file ***/
			unlink($tmp_name);
			/*** return the image ***/
			return $img;
		}
		return false;
	}

 }
?>