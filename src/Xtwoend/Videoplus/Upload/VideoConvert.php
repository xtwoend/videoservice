<?php namespace Xtwoend\Videoplus\Upload;
    	
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

class VideoConvert {	
	

	public $path;


	public $dest;
	/**
	 * 
	 * @params
	 */	
	public function __construct()
	{
		$config = new PHPVideoToolkit\Config(array(
                'temp_directory' => base_path('temp'),
                'ffmpeg' => '/usr/local/bin/ffmpeg',
                'ffprobe' => '/usr/local/bin/ffprobe',
                'yamdi' => '/usr/bin/yamdi',
                'qtfaststart' => '/usr/local/bin/qt-faststart',
                'cache_driver' => 'InTempDirectory',
            ), true);

	}
	
	public function path($path)
	{
		$this->path = $path;
	}

	public function dest($dest)
	{
		$this->dest = $dest;
	}

	public function start()
	{
		$videoffmpeg  = new PHPVideoToolkit\Video($this->path);
        $output_format = new PHPVideoToolkit\VideoFormat_Mp4();
        $output_format->setVideoRotation(true)
                                  ->setVideoDimensions(852, 480);

        $prosess = $videoffmpeg->saveNonBlocking($this->dest, $output_format, PHPVideoToolkit\Video::OVERWRITE_EXISTING);
        
        return $videoffmpeg->getPortableId();    
	}

	public function status($convert_id)
	{
		$handler = new PHPVideoToolkit\ProgressHandlerPortable($convert_id);
        $probe = $handler->probe();

        return $probe;
	}
}