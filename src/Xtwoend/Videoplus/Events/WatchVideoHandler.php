<?php namespace Xtwoend\Videoplus\Events;
    	
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
use Xtwoend\Videoplus\Video\Video;
use Illuminate\Session\Store;
use Sentry;

class WatchVideoHandler {	

	private $session;

	/**
	 * @doc
	 * 
	 */
	public function __construct(Store $session)
	{
		$this->session = $session;
	}

	public function handle(Video $video)
    {
    	//dd($this->isVideoWatched($video));
    	$user_id = (Sentry::check())? Sentry::getUser()->id : 0;

        if ( ! $this->isVideoWatched($video))
	    {
	        $video->increment('views');
	        $video->views += 1;
	        $video->addActivity(array('events'=> 'watched video', 'owner_id' => $user_id, 'type' => 1));
	        $this->storeWatch($video);
	    }
    }

	private function isVideoWatched($video)
	{
	    // Get all the watched videos from the session. If no
	    // entry in the session exists, default to an
	    // empty array.
	    $watched = $this->session->get('watched_video', []);

	    // Check the watched videos array for the existance
	    // of the id of the video
	    return in_array($video->id, $watched);
	}

	private function storeWatch($video)
	{    
	    // Push the video id onto the watched_video session array.
	    $this->session->push('watched_video', $video->id);
	}
	
}