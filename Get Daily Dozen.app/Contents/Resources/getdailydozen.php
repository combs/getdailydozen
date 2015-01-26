#!/usr/bin/php
<?php
error_reporting(E_ALL | E_STRICT);
// ini_set('display_errors', true);
ini_set('auto_detect_line_endings', true);


// h/t http://stackoverflow.com/questions/5384962/writing-exif-data-in-php



define("IPTC_OBJECT_NAME", "005");
define("IPTC_EDIT_STATUS", "007");
define("IPTC_PRIORITY", "010");
define("IPTC_CATEGORY", "015");
define("IPTC_SUPPLEMENTAL_CATEGORY", "020");
define("IPTC_FIXTURE_IDENTIFIER", "022");
define("IPTC_KEYWORDS", "025");
define("IPTC_RELEASE_DATE", "030");
define("IPTC_RELEASE_TIME", "035");
define("IPTC_SPECIAL_INSTRUCTIONS", "040");
define("IPTC_REFERENCE_SERVICE", "045");
define("IPTC_REFERENCE_DATE", "047");
define("IPTC_REFERENCE_NUMBER", "050");
define("IPTC_CREATED_DATE", "055");
define("IPTC_CREATED_TIME", "060");
define("IPTC_ORIGINATING_PROGRAM", "065");
define("IPTC_PROGRAM_VERSION", "070");
define("IPTC_OBJECT_CYCLE", "075");
define("IPTC_BYLINE", "080");
define("IPTC_BYLINE_TITLE", "085");
define("IPTC_CITY", "090");
define("IPTC_PROVINCE_STATE", "095");
define("IPTC_COUNTRY_CODE", "100");
define("IPTC_COUNTRY", "101");
define("IPTC_ORIGINAL_TRANSMISSION_REFERENCE", "103");
define("IPTC_HEADLINE", "105");
define("IPTC_CREDIT", "110");
define("IPTC_SOURCE", "115");
define("IPTC_COPYRIGHT_STRING", "116");
define("IPTC_CAPTION", "120");
define("IPTC_LOCAL_CAPTION", "121");

class IPTC
{
    var $meta = [];
    var $file = null;

    function __construct($filename)
    {
        $info = null;

        $size = getimagesize($filename, $info);

        if(isset($info["APP13"])) $this->meta = iptcparse($info["APP13"]);

        $this->file = $filename;
    }

    function getValue($tag)
    {
        return isset($this->meta["2#$tag"]) ? $this->meta["2#$tag"][0] : "";
    }

    function setValue($tag, $data)
    {
        $this->meta["2#$tag"] = [$data];

        $this->write();
    }

    private function write()
    {
        $mode = 0;

        $content = iptcembed($this->binary(), $this->file, $mode);   

        $filename = $this->file;

        if(file_exists($this->file)) unlink($this->file);

        $fp = fopen($this->file, "w");
        fwrite($fp, $content);
        fclose($fp);
    }         

    private function binary()
    {
        $data = "";

        foreach(array_keys($this->meta) as $key)
        {
            $tag = str_replace("2#", "", $key);
            $data .= $this->iptc_maketag(2, $tag, $this->meta[$key][0]);
        }       

        return $data;
    }

    function iptc_maketag($rec, $data, $value)
    {
        $length = strlen($value);
        $retval = chr(0x1C) . chr($rec) . chr($data);

        if($length < 0x8000)
        {
            $retval .= chr($length >> 8) .  chr($length & 0xFF);
        }
        else
        {
            $retval .= chr(0x80) . 
                       chr(0x04) . 
                       chr(($length >> 24) & 0xFF) . 
                       chr(($length >> 16) & 0xFF) . 
                       chr(($length >> 8) & 0xFF) . 
                       chr($length & 0xFF);
        }

        return $retval . $value;            
    }   

    function dump()
    {
        echo "<pre>";
        print_r($this->meta);
        echo "</pre>";
    }


}




$day=$argv[1];
if ($day=="" || $day===false) {
	exit("Usage: " . basename($argv[0]) . " 2015-01-12\n");
}


$api_key=rtrim(file_get_contents($_SERVER['HOME'] . "/.yourshot-api-key"));
if (!$api_key) {
 exit("\nPlease put your Your Shot API key in ~/.yourshot-api-key\n");
}


$url="http://yourshot.nationalgeographic.com/api/v1/dailydozen/$day/photo/?format=json&apikey=" . trim($api_key) . "&limit=18&page=1";
$json_string_dailydozen = file_get_contents($url);
 
$parsed_json = json_decode($json_string_dailydozen);

$objects = $parsed_json->{'objects'};

$index=1;

foreach ($objects as $object) {
	$id=$object->{'id'};
	$object_url=$object->{'sizes'}->{'large-2048'};
	$object_url=$object->{'sizes'}->{'large-2048'};
	if (strpos($object_url,"http")===false) {
		$object_url="http://yourshot.nationalgeographic.com/" . $object_url;
	}
	$jpeg=file_get_contents($object_url);
	$filename="daily-dozen-" . trim($day) . "-" . sprintf("%02d",$index) . "-yourshot-" . trim($id) . ".jpg";
	file_put_contents($filename,$jpeg);
	$objIPTC = new IPTC($filename);

	$objIPTC->setValue(IPTC_OBJECT_NAME, $object->{'title'});
	$objIPTC->setValue(IPTC_HEADLINE, $object->{'title'});
	$objIPTC->setValue(IPTC_CAPTION, $object->{'title'});
	$objIPTC->setValue(IPTC_LOCAL_CAPTION, $object->{'title'});
	$objIPTC->setValue(IPTC_CREDIT, $object->{'owner'}->{'display_name'});
	$objIPTC->setValue(IPTC_BYLINE, $object->{'owner'}->{'display_name'});

	
	$index++;
		
	
}



?>