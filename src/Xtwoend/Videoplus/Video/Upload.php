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

class Upload {	

    /**
     * @doc
     * 
     */
    public function __construct($config)
    {
        $this->config = $config;
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

        if ($file->validateFile() && $file->save($this->config['dest'])) 
        {
          $path =  str_replace(public_path(), '', $this->config['dest']);
          $duration = $this->getDuration($this->config['dest']);
          $image  = $this->createImage($this->config['dest'], public_path('images/'.\Str::slug($request->getFileName(),'-').'.jpg'));
		      $img_url =  str_replace(public_path(), '', $image);
          // File upload was complete
          return [  
                  'success'     => true, 
                  'source'      => $this->config['dest'], 
                  'original_name' => $request->getFileName(),
                  'url_stream'  => \URL::to($path),
                  'size'        => $request->getTotalSize(),
                  'duration'    => $duration,
                  'image'       => \URL::to($img_url),
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
        $ffmpeg = FFMpeg\FFMpeg::create();
        $video = $ffmpeg->open($file);


        $format = new Format\Video\X264();
        $format->on('progress', function ($video, $format, $percentage) {
            echo "$percentage % transcoded";
        });

        $format
            -> setKiloBitrate(1000)
            -> setAudioChannels(2)
            -> setAudioKiloBitrate(256);

        $video
            ->save($format, $output);

        return $output;
    }
}