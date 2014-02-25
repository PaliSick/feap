<?
function echomsg($msg, $type = 'success') {

	return '<div class="msg-'.$type.'">
				<div class="icon">
					<div></div>
				</div>
				<span class="vertical-trick"></span>
				<div class="txt">'.urldecode($msg).'</div>
			</div>';

}

function seo_string($string, $separator = '-')
{
	$string = trim($string);

	$string = strtolower($string); // convert to lowercase text

	// Recommendation URL: http://www.webcheatsheet.com/php/regular_expressions.php

	// Only space, letters, numbers and underscore are allowed

	$string = trim(preg_replace('/[^ A-Za-z0-9_]/', ' ', $string));
	/*
	"t" (ASCII 9 (0x09)), a tab.
	"n" (ASCII 10 (0x0A)), a new line (line feed).
	"r" (ASCII 13 (0x0D)), a carriage return.
	*/

	//$string = ereg_replace("[ tnr]+", "-", $string);

	$string = str_replace(" ", $separator, $string);

	$string = preg_replace('/[ -]+/', '-', $string);

	return $string;
}

function write_ini_file($assoc_arr, $path, $has_sections=FALSE) {
    $content = "";
    if ($has_sections) {
        foreach ($assoc_arr as $key=>$elem) {
            $content .= "[".$key."]\n";
            foreach ($elem as $key2=>$elem2) {
                if(is_array($elem2))
                {
                    for($i=0;$i<count($elem2);$i++)
                    {
                        $content .= $key2."[] = \"".$elem2[$i]."\"\n";
                    }
                }
                else if($elem2=="") $content .= $key2." = \n";
                else $content .= $key2." = \"".$elem2."\"\n";
            }
        }
    } else {
        foreach ($assoc_arr as $key=>$elem) {
            if(is_array($elem))
            {
                for($i=0;$i<count($elem);$i++)
                {
                    $content .= $key2."[] = \"".$elem[$i]."\"\n";
                }
            }
            else if($elem=="") $content .= $key2." = \n";
            else $content .= $key2." = \"".$elem."\"\n";
        }
    }

    if (!$handle = fopen($path, 'w')) {
        return false;
    }
    if (!fwrite($handle, $content)) {
		fclose($handle);
        return false;
    }
    fclose($handle);
    return true;
}