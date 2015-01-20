<?php namespace Xtwoend\Videoplus\Upload;

/**
 * Backend App
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the MIT License.
 *
 * This source file is subject to the MIT License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://opensource.org/licenses/MIT
 *
 * @package    Backend
 * @version    1.0.0
 * @author     Abdul Hafidz A <aditans88@gmail.com>
 * @license    MIT License
 */

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class FolderManage {

	  /**
     * static app
     *
     * @var array
     */
    public static $app;


    /**
     * Create a new Order object.
     *
     * @param  VideoRepositoryInterface $repository
     * @param  integer $ownerId
     *
     * @return void
     */
    public function __construct()
    {   
        if ( ! static::$app )
            static::$app = app();
    }
	
    /**
     * Get upload path with date folders
     * @param $date
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     * @throws \Doctrine\Common\Proxy\Exception\InvalidArgumentException
     * @return string
     */
    public function getUploadFolder($folder ,$username = 'guest', $date=null)
    {
        // Check that a date was given
        if (is_null($date)) {
            $date = Carbon::now();
        } elseif (! is_a($date, 'Carbon')) {
            throw new InvalidArgumentException('Must me a Carbon object');
        }

        // Get the configuration value for the upload path
        $path = 'public/'.$folder.'/'.$username;

        $path = $this->cleanPath($path);

        // Add the project base to the path
        $path = static::$app['path.base'].$path;

        $this->dateFolderPath = str_replace('-','/',$date->toDateString()) . '/';

        // Parse in to a folder format. 2013:03:30 -> 2013/03/30/{filename}.jpg
        $folder = $path . $this->dateFolderPath;

        // Check to see if the upload folder exists
        if (! File::exists($folder)) {
            // Try and create it
            if (! File::makeDirectory($folder, 0777, true)) {
                throw new FileException('Directory is not writable. Please make upload folder writable.');
            }
        }

        // Check that the folder is writable
        if (! File::isWritable($folder)) {
            throw new FileException('Folder is not writable.');
        }

        return $folder;
    }

   
    public function cleanPath($path)
    {
        // Check to see if it begins in a slash
        if(substr($path, 0) != '/') {
            $path = '/' . $path;
        }

        // Check to see if it ends in a slash
        if(substr($path, -1) != '/') {
            $path .= '/';
        }

        return $path;
    }
}