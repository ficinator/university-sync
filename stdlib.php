<?php
    function __autoload($class) {
        include "$class.php";
    }

    function initialise_site(csite $site) {
        $site->addHeader("header.php");
        //$site->addWrapper("wrapper.php");
        $site->addFooter("footer.php");
    }
    
    function make_thumb($src, $dest, $thumb_width, $thumb_height) {	
    	$source_image = imagecreatefromjpeg($src);
    	$width = imagesx($source_image);
    	$height = imagesy($source_image);
    	$dest_ratio = $thumb_width / $thumb_height;
    	$source_ratio = $width / $height;
    	$tmp_width = $source_ratio > $dest_ratio ? floor(($thumb_height / $height) * $width) : $thumb_width;
    	$tmp_height = $source_ratio > $dest_ratio ? $thumb_height : floor(($thumb_width / $width) * $height);
    	$scaled_image = imagecreatetruecolor($tmp_width, $tmp_height);
    	imagecopyresampled($scaled_image, $source_image, 0, 0, 0, 0, $tmp_width, $tmp_height, $width, $height);
    	$cropped_image = imagecreatetruecolor($thumb_width, $thumb_height);
    	$src_x = floor(($tmp_width - $thumb_width) / 2);
    	$src_y = floor(($tmp_height - $thumb_height) / 2);
    	imagecopyresized($cropped_image, $scaled_image, 0, 0, $src_x, $src_y, $thumb_width, $thumb_height, $thumb_width, $thumb_height);
    	imagejpeg($cropped_image, $dest);
    }
    
    function rrmdir($dir) { 
        if (is_dir($dir)) { 
            $objects = scandir($dir); 
            foreach ($objects as $object) { 
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
                } 
            } 
            reset($objects); 
            rmdir($dir); 
        } 
    } 
    
    $table = array(
        'Š'=>'S', 'š'=>'s', 'Š'=>'s', 'Ď'=>'D', 'Ď'=>'d', 'ď'=>'d', 'Ž'=>'Z', 'Ž'=>'z', 'ž'=>'z', 'Č'=>'C', 'Č'=>'c', 'č'=>'c', 'C'=>'C', 'c'=>'c',
        'À'=>'A', 'Á'=>'A', 'Á'=>'a', 'ä'=>'a', 'Ä'=>'A', 'Ä'=>'a', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'Ç'=>'c', 'È'=>'E', 'É'=>'E', 'É'=>'e',
        'Ê'=>'E', 'Ë'=>'E', 'Ĺ'=>'L', 'Ľ'=>'L', 'Ľ'=>'l', 'ľ'=>'l', 'ĺ'=>'l', 'Ň'=>'N', 'Ň'=>'n', 'ň'=>'n', 'Ì'=>'I', 'Í'=>'I', 'Í'=>'i', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ó'=>'o', 'Ô'=>'O', 'Ô'=>'o',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Ú'=>'u', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Ý'=>'y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'Ť'=>'t', 'ť'=>'t', 'Ž'=>'Z', 'Ž'=>'z', 'ž'=>'z', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'R'=>'R', 'r'=>'r', "'"=>'-', '"'=>'-'
    );
   
?>