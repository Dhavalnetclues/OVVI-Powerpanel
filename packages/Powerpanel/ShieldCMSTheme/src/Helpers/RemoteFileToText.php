<?php
/* example to call :

require("class.filetotext.php"); 
$docObj = new Filetotext("test.docx");  //$docObj = new Filetotext("test.pdf"); 
$return = $docObj->convertToText(); 

var_dump( $return ) ; 
*/
namespace App\Helpers;
use ZipArchive;
use Config;

class RemoteFileToText {
    private $filePath;
    public function __construct($filePath) {
	$this->filePath = $filePath;
    }
    
    public function convertToText() {
        if(isset($this->filename) && !empty($this->filename)) {
                return "File Not exists";
        }
        
        $fileArray = pathinfo($this->filePath);
        $file_ext  = isset($fileArray['extension'])?$fileArray['extension']:''; 
        if($file_ext == "doc" || $file_ext == "docx" || $file_ext == "xlsx" || $file_ext == "xlsm" || $file_ext == "xls")
        {
            $docObj = new DocxConversion($this->filePath);
            $docText= $docObj->convertToText();
            return $docText;
        } else if( $file_ext == "pdf" ){
            $var = new PDF2Text();
            $var->setFilename($this->filePath);
            $var->decodePDF();
            return $var->output();
        } else {
           return "Invalid File Type";
        }
    }
}

class PDF2Text {

    // Some settings
    var $multibyte = 4; // Use setUnicode(TRUE|FALSE)
    var $convertquotes = ENT_QUOTES; // ENT_COMPAT (double-quotes), ENT_QUOTES (Both), ENT_NOQUOTES (None)
    var $showprogress = true; // TRUE if you have problems with time-out
// Variables
    var $filename = '';
    var $decodedtext = '';

    function setFilename($filename) {
        // Reset
        $this->decodedtext = '';
        $this->filename = $filename;
    }

    function output($echo = false) {
        if ($echo)
            echo $this->decodedtext;
        else
            return $this->decodedtext;
    }

    function setUnicode($input) {
        // 4 for unicode. But 2 should work in most cases just fine
        if ($input == true)
            $this->multibyte = 4;
        else
            $this->multibyte = 2;
    }

    function decodePDF() {
        // Read the data from pdf file
        //$infile = file_get_contents($this->filename, FILE_BINARY);
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, $this->filename);
        curl_setopt($resource, CURLOPT_HEADER, 1);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($resource, CURLOPT_BINARYTRANSFER, 1);
        
        $infile = curl_exec($resource);
        curl_close($resource);
        
        if (empty($infile))
            return "";

// Get all text data.
        $transformations = array();
        $texts = array();

// Get the list of all objects.
        preg_match_all("#obj[\n|\r](.*)endobj[\n|\r]#ismU", $infile . "endobj\r", $objects);
        $objects = @$objects[1];
        
// Select objects with streams.
        for ($i = 0; $i < count($objects); $i++) {
            $currentObject = $objects[$i];

// Prevent time-out
            @set_time_limit();
            if ($this->showprogress) {
// echo ". ";
                //flush();
                //ob_flush();
            }

// Check if an object includes data stream.
            if (preg_match("#stream[\n|\r](.*)endstream[\n|\r]#ismU", $currentObject . "endstream\r", $stream)) {
                $stream = ltrim($stream[1]);
                // Check object parameters and look for text data.
                $options = $this->getObjectOptions($currentObject);

                if (!(empty($options["Length1"]) && empty($options["Type"]) && empty($options["Subtype"])))
// if ( $options["Image"] && $options["Subtype"] )
// if (!(empty($options["Length1"]) && empty($options["Subtype"])) )
                    continue;

// Hack, length doesnt always seem to be correct
                unset($options["Length"]);

// So, we have text data. Decode it.
                $data = $this->getDecodedStream($stream, $options);

                if (strlen($data)) {
                    if (preg_match_all("#BT[\n|\r](.*)ET[\n|\r]#ismU", $data . "ET\r", $textContainers)) {
                        $textContainers = @$textContainers[1];
                        $this->getDirtyTexts($texts, $textContainers);
                    } else
                        $this->getCharTransformations($transformations, $data);
                }
            }
        }

// Analyze text blocks taking into account character transformations and return results.
        $this->decodedtext = $this->getTextUsingTransformations($texts, $transformations);
    }

    function decodeAsciiHex($input) {
        $output = "";

        $isOdd = true;
        $isComment = false;

        for ($i = 0, $codeHigh = -1; $i < strlen($input) && $input[$i] != '>'; $i++) {
            $c = $input[$i];

            if ($isComment) {
                if ($c == '\r' || $c == '\n')
                    $isComment = false;
                continue;
            }

            switch ($c) {
                case '\0': case '\t': case '\r': case '\f': case '\n': case ' ': break;
                case '%':
                    $isComment = true;
                    break;

                default:
                    $code = hexdec($c);
                    if ($code === 0 && $c != '0')
                        return "";

                    if ($isOdd)
                        $codeHigh = $code;
                    else
                        $output .= chr($codeHigh * 16 + $code);

                    $isOdd = !$isOdd;
                    break;
            }
        }

        if ($input[$i] != '>')
            return "";

        if ($isOdd)
            $output .= chr($codeHigh * 16);

        return $output;
    }

    function decodeAscii85($input) {
        $output = "";

        $isComment = false;
        $ords = array();

        for ($i = 0, $state = 0; $i < strlen($input) && $input[$i] != '~'; $i++) {
            $c = $input[$i];

            if ($isComment) {
                if ($c == '\r' || $c == '\n')
                    $isComment = false;
                continue;
            }

            if ($c == '\0' || $c == '\t' || $c == '\r' || $c == '\f' || $c == '\n' || $c == ' ')
                continue;
            if ($c == '%') {
                $isComment = true;
                continue;
            }
            if ($c == 'z' && $state === 0) {
                $output .= str_repeat(chr(0), 4);
                continue;
            }
            if ($c < '!' || $c > 'u')
                return "";

            $code = ord($input[$i]) & 0xff;
            $ords[$state++] = $code-ord('!');

            if ($state == 5) {
                $state = 0;
                for ($sum = 0, $j = 0; $j < 5; $j++)
                    $sum = $sum * 85 + $ords[$j];
                for ($j = 3; $j >= 0; $j--)
                    $output .= chr($sum >> ($j * 8));
            }
        }
        if ($state === 1)
            return "";
        elseif ($state > 1) {
            for ($i = 0, $sum = 0; $i < $state; $i++)
                $sum += ($ords[$i] + ($i == $state-1)) * pow(85, 4-$i);
            for ($i = 0; $i < $state-1; $i++) {
                try {
                    if (false == ($o = chr($sum >> ((3-$i) * 8)))) {
                        throw new Exception('Error');
                    }
                    $output .= $o;
                } catch (Exception $e) { /* Dont do anything */
                }
            }
        }

        return $output;
    }

    function decodeFlate($data) {
        return @gzuncompress($data);
    }

    function getObjectOptions($object) {
        $options = array();

        if (preg_match("#<<(.*)>>#ismU", $object, $options)) {
            $options = explode("/", $options[1]);
            @array_shift($options);

            $o = array();
            for ($j = 0; $j < @count($options); $j++) {
                $options[$j] = preg_replace("#\s+#", " ", trim($options[$j]));
                if (strpos($options[$j], " ") !== false) {
                    $parts = explode(" ", $options[$j]);
                    $o[$parts[0]] = $parts[1];
                } else
                    $o[$options[$j]] = true;
            }
            $options = $o;
            unset($o);
        }

        return $options;
    }

    function getDecodedStream($stream, $options) {
        $data = "";
        if (empty($options["Filter"]))
            $data = $stream;
        else {
            $length = !empty($options["Length"]) ? $options["Length"] : strlen($stream);
            $_stream = substr($stream, 0, $length);

            foreach ($options as $key => $value) {
                if ($key == "ASCIIHexDecode")
                    $_stream = $this->decodeAsciiHex($_stream);
                elseif ($key == "ASCII85Decode")
                    $_stream = $this->decodeAscii85($_stream);
                elseif ($key == "FlateDecode")
                    $_stream = $this->decodeFlate($_stream);
                elseif ($key == "Crypt") { // TO DO
                }
            }
            $data = $_stream;
        }
        return $data;
    }

    function getDirtyTexts(&$texts, $textContainers) {
        for ($j = 0; $j < count($textContainers); $j++) {
            if (preg_match_all("#\[(.*)\]\s*TJ[\n|\r]#ismU", $textContainers[$j], $parts))
                $texts = array_merge($texts, array(@implode('', $parts[1])));
            elseif (preg_match_all("#T[d|w|m|f]\s*(\(.*\))\s*Tj[\n|\r]#ismU", $textContainers[$j], $parts))
                $texts = array_merge($texts, array(@implode('', $parts[1])));
            elseif (preg_match_all("#T[d|w|m|f]\s*(\[.*\])\s*Tj[\n|\r]#ismU", $textContainers[$j], $parts))
                $texts = array_merge($texts, array(@implode('', $parts[1])));
        }
    }

    function getCharTransformations(&$transformations, $stream) {
        preg_match_all("#([0�9]+)\s+beginbfchar(.*)endbfchar#ismU", $stream, $chars, PREG_SET_ORDER);
        preg_match_all("#([0�9]+)\s+beginbfrange(.*)endbfrange#ismU", $stream, $ranges, PREG_SET_ORDER);

        for ($j = 0; $j < count($chars); $j++) {
            $count = $chars[$j][1];
            $current = explode("\n", trim($chars[$j][2]));
            for ($k = 0; $k < $count && $k < count($current); $k++) {
                if (preg_match("#<([0�9a-f]{2,4})>\s+<([0�9a-f]{4,512})>#is", trim($current[$k]), $map))
                    $transformations[str_pad($map[1], 4, "0")] = $map[2];
            }
        }
        for ($j = 0; $j < count($ranges); $j++) {
            $count = $ranges[$j][1];
            $current = explode("\n", trim($ranges[$j][2]));
            for ($k = 0; $k < $count && $k < count($current); $k++) {
                if (preg_match("#<([0�9a-f]{4})>\s+<([0�9a-f]{4})>\s+<([0�9a-f]{4})>#is", trim($current[$k]), $map)) {
                    $from = hexdec($map[1]);
                    $to = hexdec($map[2]);
                    $_from = hexdec($map[3]);

                    for ($m = $from, $n = 0; $m <= $to; $m++, $n++)
                        $transformations[sprintf("%04X", $m)] = sprintf("%04X", $_from + $n);
                } elseif (preg_match("#<([0�9a-f]{4})>\s+<([0�9a-f]{4})>\s+\[(.*)\]#ismU", trim($current[$k]), $map)) {
                    $from = hexdec($map[1]);
                    $to = hexdec($map[2]);
                    $parts = preg_split("#\s+#", trim($map[3]));

                    for ($m = $from, $n = 0; $m <= $to && $n < count($parts); $m++, $n++)
                        $transformations[sprintf("%04X", $m)] = sprintf("%04X", hexdec($parts[$n]));
                }
            }
        }
    }

    function getTextUsingTransformations($texts, $transformations) {
        $document = "";
        for ($i = 0; $i < count($texts); $i++) {
            $isHex = false;
            $isPlain = false;

            $hex = "";
            $plain = "";
            for ($j = 0; $j < strlen($texts[$i]); $j++) {
                $c = $texts[$i][$j];
                switch ($c) {
                    case "<":
                        $hex = "";
                        $isHex = true;
                        $isPlain = false;
                        break;
                    case ">":
                        $hexs = str_split($hex, $this->multibyte); // 2 or 4 (UTF8 or ISO)
                        for ($k = 0; $k < count($hexs); $k++) {

                            $chex = str_pad($hexs[$k], 4, "0"); // Add tailing zero
                            if (isset($transformations[$chex]))
                                $chex = $transformations[$chex];
                            $document .= html_entity_decode("&#x" . $chex . ";");
                        }
                        $isHex = false;
                        break;
                    case "(":
                        $plain = "";
                        $isPlain = true;
                        $isHex = false;
                        break;
                    case ")":
                        $document .= $plain;
                        $isPlain = false;
                        break;
                    case "\\":
                        $c2 = $texts[$i][$j + 1];
                        if (in_array($c2, array("\\", "(", ")")))
                            $plain .= $c2;
                        elseif ($c2 == "n")
                            $plain .= '\n';
                        elseif ($c2 == "r")
                            $plain .= '\r';
                        elseif ($c2 == "t")
                            $plain .= '\t';
                        elseif ($c2 == "b")
                            $plain .= '\b';
                        elseif ($c2 == "f")
                            $plain .= '\f';
                        elseif ($c2 >= '0' && $c2 <= '9') {
                            $oct = preg_replace("#[?-9]#", "", substr($texts[$i], $j + 1, 3));
                            $j += strlen($oct)-1;
                            $plain .= html_entity_decode("&#" . octdec($oct) . ";", $this->convertquotes);
                        }
                        $j++;
                        break;

                    default:
                        if ($isHex)
                            $hex .= $c;
                        elseif ($isPlain)
                            $plain .= $c;
                        break;
                }
            }
            $document .= "\n";
        }

        return $document;
    }
    
    function is_binarystring( $str )
    {
            # Check if entered string is actually a binary string ( fit for conversion )
            # so, length dividable by 8 and only 1's and 0's.

            if( is_int( strlen( $str ) / 8 ) )
            {
                    for( $i = 0; $i < strlen( $str ); $i++ )
                    {
                            $char = substr( $str, $i, 1 );
                            if( ( $char !== chr( 48 ) ) && ( $char !== chr( 49 ) ) )
                            {	
                                    return FALSE;
                            }
                    }
                    return TRUE;
            }
            else
            {
                    return FALSE;
            }
    }
    
    function bin2text( $bin )
    {
            if ( is_binarystring( $bin ) )
            {
                    # valid binary string, split, explode and other magic
                    # prepare string for conversion
                    $chars = explode( "\n", chunk_split( str_replace( "\n", '', $bin ), 8 ) );
                    $char_count = count( $chars );

                    # converting the characters one by one
                    for( $i = 0; $i < $char_count; $text .= chr( bindec( $chars[$i] ) ), $i++ );

                    # let's return the result
                    return "Result: " . $text;
            }
            else
            {
                    # not valid binary to text string
                    return "Input problems! Are we missing some ones and zeros?";
            }
    }

}

/*class for convert docs */

class DocxConversion{
    private $filename;

    public function __construct($filePath) {
        $this->filename = $filePath;
    }

    private function read_doc() {
        //$fileHandle = fopen($this->filename, "r");
        //$line = @fread($fileHandle, filesize($this->filename));   
        //$line = @file_get_contents($this->filename, FILE_BINARY);
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, $this->filename);
        curl_setopt($resource, CURLOPT_HEADER, 1);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($resource, CURLOPT_BINARYTRANSFER, 1);
        
        $line = curl_exec($resource);
        curl_close($resource);
        $lines = explode(chr(0x0D),$line);
        $outtext = "";
        foreach($lines as $thisline)
          {
            $pos = strpos($thisline, chr(0x00));
            if (($pos !== FALSE)||(strlen($thisline)==0))
              {
              } else {
                $outtext .= $thisline." ";
              }
          }
         $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
        return $outtext;
    }

    private function read_docx(){

        $striped_content = '';
        $content = '';

        $zip = zip_open($this->filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }// end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
    }

 /************************excel sheet************************************/

function xlsx_to_text($input_file){
    $xml_filename = "xl/sharedStrings.xml"; //content file name
    $zip_handle = new ZipArchive;
    $output_text = "";
    if(true === $zip_handle->open($input_file)){
        if(($xml_index = $zip_handle->locateName($xml_filename)) !== false){
            $xml_datas = $zip_handle->getFromIndex($xml_index);
            $xml_handle = DOMDocument::loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            $output_text = strip_tags($xml_handle->saveXML());
        }else{
            $output_text .="";
        }
        $zip_handle->close();
    }else{
    $output_text .="";
    }
    return $output_text;
}

/*************************power point files*****************************/
function pptx_to_text($input_file){
    $zip_handle = new ZipArchive;
    $output_text = "";
    if(true === $zip_handle->open($input_file)){
        $slide_number = 1; //loop through slide files
        while(($xml_index = $zip_handle->locateName("ppt/slides/slide".$slide_number.".xml")) !== false){
            $xml_datas = $zip_handle->getFromIndex($xml_index);
            $xml_handle = DOMDocument::loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            $output_text .= strip_tags($xml_handle->saveXML());
            $slide_number++;
        }
        if($slide_number == 1){
            $output_text .="";
        }
        $zip_handle->close();
    }else{
    $output_text .="";
    }
    return $output_text;
}


    public function convertToText() {
        
        /*if(isset($this->filename)) {
            return "File Not exists";
        }*/

        $fileArray = pathinfo($this->filename);
        $file_ext  = $fileArray['extension'];
        if($file_ext == "doc" || $file_ext == "docx" || $file_ext == "xlsx" || $file_ext == "pptx" || $file_ext == "xlsm" || $file_ext == "xls")
        {
            if($file_ext == "doc") {
                return $this->read_doc();
            } elseif($file_ext == "docx") {
                return $this->extractDocxText();
            } elseif($file_ext == "xlsx" || $file_ext == "xlsm") {
                //return $this->xlsx_to_text();
                return $this->extractXlsxText();
            }elseif($file_ext == "pptx") {
                //return $this->pptx_to_text();
            }
        } else {
            return "Invalid File Type";
        }
    }
    
    //=========DOCX===========
function extractDocxText(){
        $docx = $this->get_url($this->filename);
        $newfile = Config::get('Constant.LOCAL_CDN_PATH') . '/vendor/filetotext/' . time() . '.' .'docx';
        file_put_contents($newfile,$docx);
        $xml_filename = "word/document.xml"; //content file name
        $zip_handle = new ZipArchive;
        $output_text = "";
        if(true === $zip_handle->open($newfile)){
            if(($xml_index = $zip_handle->locateName($xml_filename)) !== false){
                $xml_datas = $zip_handle->getFromIndex($xml_index);
                //file_put_contents($input_file.".xml",$xml_datas);
                $replace_newlines = preg_replace('/<w:p w[0-9-Za-z]+:[a-zA-Z0-9]+="[a-zA-z"0-9 :="]+">/',"\n\r",$xml_datas);
                $replace_tableRows = preg_replace('/<w:tr>/',"\n\r",$replace_newlines);
                $replace_tab = preg_replace('/<w:tab\/>/',"\t",$replace_tableRows);
                $replace_paragraphs = preg_replace('/<\/w:p>/',"\n\r",$replace_tab);
                $replace_other_Tags = strip_tags($replace_paragraphs);          
                $output_text = $replace_other_Tags;
            }else{
                $output_text .="";
            }
            $zip_handle->close();
        }else{
        $output_text .=" ";
        }
        unlink($newfile);
        //save to file or echo content
        //file_put_contents($file_name,$output_text);
        return $output_text;
    }
    
    //========XLSX==========
function extractXlsxText(){
    $xlsx = $this->get_url($this->filename);
    $newfile = Config::get('Constant.LOCAL_CDN_PATH') . '/vendor/filetotext/' . time() . '.' .'txt';
    file_put_contents ($newfile, $xlsx);
    $content = "";
    $dir = Config::get('Constant.LOCAL_CDN_PATH') . '/vendor/filetotext/xlsx';
    // Unzip
    $zip = new ZipArchive;
    $zip->open($newfile);
    $zip->extractTo($dir);
    // Open up shared strings & the first worksheet
    $strings = simplexml_load_file($dir . '/xl/sharedStrings.xml');
    $sheet   = simplexml_load_file($dir . '/xl/worksheets/sheet1.xml');
    // Parse the rows
    $xlrows = $sheet->sheetData->row;
    foreach ($xlrows as $xlrow) {
        $arr = array();

        // In each row, grab it's value
        foreach ($xlrow->c as $cell) {
            $v = (string) $cell->v;

            // If it has a "t" (type?) of "s" (string?), use the value to look up string value
            if (isset($cell['t']) && $cell['t'] == 's') {
                $s  = array();
                $si = $strings->si[(int) $v];

                // Register & alias the default namespace or you'll get empty results in the xpath query
                $si->registerXPathNamespace('n', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
                // Cat together all of the 't' (text?) node values
                foreach($si->xpath('.//n:t') as $t) {
                    $content .= $t."  ";}   }
            }
        }
        unlink($newfile);
        self::delete_directory($dir);
    return $content;
    
    }
    
    public static function delete_directory($dirname) {
         if (is_dir($dirname))
           $dir_handle = opendir($dirname);
            if (!$dir_handle)
                 return false;
            while($file = readdir($dir_handle)) {
                  if ($file != "." && $file != "..") {
                       if (!is_dir($dirname."/".$file))
                            unlink($dirname."/".$file);
                       else
                            self::delete_directory($dirname.'/'.$file);
                  }
            }
            closedir($dir_handle);
            rmdir($dirname);
            return true;
       }
    
    
    function get_url( $url,$timeout = 5 )
    {
        $url = str_replace( "&amp;", "&", urldecode(trim($url)) );
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        $content = curl_exec( $ch );
        //$response = curl_getinfo( $ch ); 
        curl_close ( $ch );
        return $content;
    }

}

