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

use Flow\Config;
use Flow\Request;
use Flow\File;

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
        $config = new Config();
        $config->setTempDir($this->config['temp']);

        $request = new Request();
        $file = new File($config, $request);
        $file_name = $request->getFileName(); 

        $file_name_without_ext = strtolower(pathinfo($file_name, PATHINFO_FILENAME));
        $file_ext =  strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $new_file_name = md5($file_name_without_ext . time()) . '.' .$file_ext;

        $dest = $this->config['dest']. $new_file_name;
        

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
          $path =  str_replace(public_path(), '', $dest);
          // File upload was complete
          return [  
                  'success'     => true, 
                  'source'      => $dest, 
                  'original_name' => $new_file_name,
                  'url_stream'  => \URL::to($path),
                  'size'        => $request->getTotalSize(),
                  'ext'         => $file_ext,
                ];


    		} else {
    		  // This is not a final chunk, continue to upload
    		  return false;
        }
    }
}