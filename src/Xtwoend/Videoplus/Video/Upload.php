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

use PHPVideoToolkit;

class Upload {	

    /**
     * @doc
     * 
     */
    public function __construct($config)
    {
        $this->config = $config;

        $config = new PHPVideoToolkit\Config(array(
                'temp_directory' => base_path('temp'),
                'ffmpeg' => '/usr/local/bin/ffmpeg',
                'ffprobe' => '/usr/local/bin/ffprobe',
                'yamdi' => '/usr/bin/yamdi',
                'qtfaststart' => '/usr/local/bin/qt-faststart',
                'cache_driver' => 'InTempDirectory',
            ), true);
    }

	  /**
     * @doc
     * Flow Upload New
     */
    public function start()
    {   
        $config = new \Flow\Config();
        $config->setTempDir($this->config['temp']);

        $request = new \Flow\Request();
        $file = new \Flow\File($config, $request);
        $file_name = $request->getFileName(); 

        $file_origin_name = strtolower(pathinfo($file_name, PATHINFO_FILENAME));
        $file_ext =  strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $new_file_name = md5($file_origin_name . time()) . '.' .$file_ext;
        $new_convert_name = md5($file_origin_name . time()) . '.mp4'; 
        
        //allow format
        $allow_format = array('mp4','webm', 'ogg');

        $dest = $this->config['dest'].  $new_file_name;

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if ($file->checkChunk()) {
                header("HTTP/1.1 200 Ok");
            } else {
                header("HTTP/1.1 404 Not Found");
                return ;
            }
        } else {
          if ($file->validateChunk()) {
              $file->saveChunk();
          } else {
              // error, invalid chunk upload request, retry
              header("HTTP/1.1 400 Bad Request");
              return ;
          }
        }

        if ($file->validateFile() && $file->save($dest)) 
        {

          
          $duration = $this->getDuration($dest);
          $img_url = $this->extract_image($file_origin_name,  $dest);
          
          $convert_status = 0;
          $convert_id  = null;
          $source_file = $dest;

          if(! in_array(strtolower($file_ext), $allow_format)){
            $convert_id = $this->convert($dest,  $this->config['dest']. $new_convert_name);
            $convert_status = 1;
            $source_file = $this->config['dest']. $new_convert_name;
          }else{          

            $parser = new PHPVideoToolkit\MediaParser();
            $videoInfo = $parser->getFileInformation($dest);

            if($videoInfo['video']['dimensions']['height'] > 480){
              $convert_id = $this->convert($dest,  $this->config['dest']. $new_convert_name);
              $convert_status = 1;
              $source_file = $this->config['dest']. $new_convert_name;
            }
          }

          $video_stream =  str_replace(public_path(), '', $source_file);
          $img_url =  str_replace(public_path(), '', $img_url);

          // File upload was complete
          return [  
                  'success'     => true, 
                  'source'      => $source_file,
                  'source_raw'  => $dest,
                  'original_name' => $new_file_name,
                  'url_stream'  => \URL::to($video_stream),
                  'size'        => $request->getTotalSize(),
                  'duration'    => $duration,
                  'image'       => $img_url,
                  'convert_status'  => $convert_status,
                  'convert_id'  => $convert_id
                ];


    		} else {
    		  // This is not a final chunk, continue to upload
    		  return false;
        }
    }

    /**
     * Get Duration Video
     * @return void
     */
    public function getDuration($file)
    {
        //get duration
        $duration = 0;
        $cmd = "ffmpeg -i {$file}";
        exec ( "$cmd 2>&1", $output );
        $text = implode ( "\r", $output );
        if (preg_match ( '!Duration: ([0-9:.]*)[, ]!', $text, $matches )) {
            list ( $v_hours, $v_minutes, $v_seconds ) = explode ( ":", $matches [1] );
                                // duration in time format
            $d = $v_hours * 3600 + $v_minutes * 60 + $v_seconds;            
        }
        if(isset($d)) {     
            list ($duration, $trash ) = explode ( ".", $d );
        }      
        return $duration;
    }

    /**
     * @doc
     * 
     */
    public  function createImage($file, $output)
    {
        $imgout   = "ffmpeg -i {$file} -y -f image2  -ss 00:00:05 -vframes 1 -s 960x540 " . $output;
        shell_exec($imgout);
        
        return $output;
    }

    /**
     * @doc
     * 
     */
    public function convert($file, $output)
    { 
  
        $videoffmpeg  = new PHPVideoToolkit\Video($file);
        $output_format = new PHPVideoToolkit\VideoFormat_Mp4();

        $output_format->setVideoRotation(true)
                        ->setVideoDimensions(852, 480);

        $process = $videoffmpeg->saveNonBlocking($output, $output_format, PHPVideoToolkit\Video::OVERWRITE_EXISTING);          
                
        return $videoffmpeg->getPortableId();    
    }

    private function extract_image($name, $path)
    {
      $pathvideo  = new PHPVideoToolkit\Video($path);
      $imagepath = public_path('images/uploads/'.md5($name . time()).'.jpg');
      $process = $pathvideo->extractFrame(new PHPVideoToolkit\Timecode(10))
                            ->save($imagepath, null, PHPVideoToolkit\Media::OVERWRITE_EXISTING);

      return \URL::to($imagepath);
    }
}