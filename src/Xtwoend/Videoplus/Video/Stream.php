<?php namespace Xtwoend\Videoplus\Video;
    	
/**
 * Part of the package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    
 * @version    0.1
 * @author     Abdul Hafidz Anshari
 * @license    BSD License (3-clause)
 * @copyright  (c) 2014 
 */

use File;
use Request;

class Stream {	
	
	private $source;

	/**
	 * 
	 * @params
	 */	
	public function __construct($source)
	{
		$this->source = $source;
	}


	/**
	 * @doc
	 * 
	 */
	public function start()
	{
		$file = $this->source;

        if(File::isFile($file)){
        	
            if(Request::server('HTTP_RANGE', false)){
                
                $fp = @fopen($file, 'rb');
                $size = File::size($file); // File size
                $length = $size; // Content length
                $start = 0; // Start byte
                $end = $size - 1; // End byte
                header('Content-type: video/mp4');
                header("Accept-Ranges: 0-$length");
                //header("Accept-Ranges: bytes");
                if (isset($_SERVER['HTTP_RANGE'])) {
                    $c_start = $start;
                    $c_end = $end;
                    list(, $range) = explode('=', Request::server('HTTP_RANGE'), 2);
                    if (strpos($range, ',') !== false) {
                        header('HTTP/1.1 416 Requested Range Not Satisfiable');
                        header("Content-Range: bytes $start-$end/$size");
                        exit;
                    }
                    
                    if ($range == '-') {
                        $c_start = $size - substr($range, 1);
                    }else{
                        $range = explode('-', $range);
                        $c_start = $range[0];
                        $c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
                    }
                    $c_end = ($c_end > $end) ? $end : $c_end;
                    
                    if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                        header('HTTP/1.1 416 Requested Range Not Satisfiable');
                        header("Content-Range: bytes $start-$end/$size");
                        exit;
                    }
                    $start = $c_start;
                    $end = $c_end;
                    $length = $end - $start + 1;
                    fseek($fp, $start);
                    header('HTTP/1.1 206 Partial Content');
                }
                header("Content-Range: bytes $start-$end/$size");
                header("Content-Length: ".$length);
                $buffer = 1024 * 8;
                while(!feof($fp) && ($p = ftell($fp)) <= $end) {
                    if ($p + $buffer > $end) {
                        $buffer = $end - $p + 1;
                    }
                    set_time_limit(0);
                    echo fread($fp, $buffer);
                    flush();
                }
                fclose($fp);
                exit();
            }else{
                header("Content-Length: ".filesize($file));
                header("Content-Disposition: attachment; filename=\"$file\"");  
                readfile($file);
                //throw new \Exception("Error Processing Request");
            }

        }else{

           throw new \Exception("File Not Found");
           
        }
	}
	
}