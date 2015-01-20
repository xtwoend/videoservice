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
use Response;

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
        	
            return $this->stream($file)

        }else{

           throw new \Exception("File Not Found");
           
        }
	}
	

    public function serveFilePartial($fileName, $fileTitle = null, $contentType = 'application/octet-stream')
    {
        if( !file_exists($fileName) )
            throw New \Exception(sprintf('File not found: %s', $fileName));
        if( !is_readable($fileName) )
            throw New \Exception(sprintf('File not readable: %s', $fileName));
        ### Remove headers that might unnecessarily clutter up the output
        header_remove('Cache-Control');
        header_remove('Pragma');
        ### Default to send entire file
        $byteOffset = 0;
        $byteLength = $fileSize = filesize($fileName);
        header('Accept-Ranges: bytes', true);
        header(sprintf('Content-Type: %s', $contentType), true);
        if( $fileTitle )
            header(sprintf('Content-Disposition: attachment; filename="%s"', $fileTitle));
        ### Parse Content-Range header for byte offsets, looks like "bytes=11525-" OR "bytes=11525-12451"
        if( isset($_SERVER['HTTP_RANGE']) && preg_match('%bytes=(\d+)-(\d+)?%i', $_SERVER['HTTP_RANGE'], $match) )
        {
            ### Offset signifies where we should begin to read the file
            $byteOffset = (int)$match[1];
            ### Length is for how long we should read the file according to the browser, and can never go beyond the file size
            if( isset($match[2]) )
                $byteLength = min( (int)$match[2], $byteLength - $byteOffset);
            header("HTTP/1.1 206 Partial content");
            header(sprintf('Content-Range: bytes %d-%d/%d', $byteOffset, $byteLength - 1, $fileSize));  ### Decrease by 1 on byte-length since this definition is zero-based index of bytes being sent
        }
        $byteRange = $byteLength - $byteOffset;
        header(sprintf('Content-Length: %d', $byteRange));
        header(sprintf('Expires: %s', date('D, d M Y H:i:s', time() + 60*60*24*90) . ' GMT'));
        $buffer = '';   ### Variable containing the buffer
        $bufferSize = 512 * 16; ### Just a reasonable buffer size
        $bytePool = $byteRange; ### Contains how much is left to read of the byteRange
        if( !$handle = fopen($fileName, 'r') )
            throw New \Exception(sprintf("Could not get handle for file %s", $fileName));
        if( fseek($handle, $byteOffset, SEEK_SET) == -1 )
            throw New \Exception(sprintf("Could not seek to byte offset %d", $byteOffset));
        while( $bytePool > 0 )
        {
            $chunkSizeRequested = min($bufferSize, $bytePool); ### How many bytes we request on this iteration
            ### Try readin $chunkSizeRequested bytes from $handle and put data in $buffer
            $buffer = fread($handle, $chunkSizeRequested);
            ### Store how many bytes were actually read
            $chunkSizeActual = strlen($buffer);
            ### If we didn't get any bytes that means something unexpected has happened since $bytePool should be zero already
            if( $chunkSizeActual == 0 )
            {
                ### For production servers this should go in your php error log, since it will break the output
                trigger_error('Chunksize became 0', E_USER_WARNING);
                break;
            }
            ### Decrease byte pool with amount of bytes that were read during this iteration
            $bytePool -= $chunkSizeActual;
            ### Write the buffer to output
            print $buffer;
            ### Try to output the data to the client immediately
            flush();
        }
        exit();
    }


    /**
     * @doc
     * 
     */
    public function stream($file)
    {
       
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
    
    }

    /**
     * @doc
     * 
     */
    public function laravelStream($file)
    {
        $stream = $fs->readStream($file);
        $headers = [
            "Content-Type" => "video/mp4",
            "Content-Length" => filesize($file),
            "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
        ];

        return Response::stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, $headers);
    }
}