<?php namespace Xtwoend\Videoplus\Users;
    	
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

use Cartalyst\Sentry\Users\Eloquent\User as Model;
use Cartalyst\Sentry\Users\UserInterface as SentryInterface;

class User extends Model implements SentryInterface {	
	


	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'users';


	/**
	 * @doc
	 * 
	 */
	public function videos()
	{
		return $this->hasMany('Xtwoend\Videoplus\Video\Video', 'owner_id');
	}


	//follower & following

	/**
     * User following relationship
     * @return void
     */
    public function follow()
    {
    	return $this->belongsToMany(get_class(), 'user_follows', 'user_id', 'follow_id');
    }


    /**
     * User following relationship
     * @return void
     */
    public function followers()
    {
    	return $this->belongsToMany(get_class(), 'user_follows', 'follow_id', 'user_id');
    }

	/**
	 * @doc
	 * 
	 */
	public function activities()
	{
		return $this->hasMany('Xtwoend\Videoplus\Activity\Activity', 'owner_id');
	}

	public function watchlaters()
    {
      return $this->belongsToMany('Xtwoend\Videoplus\Video\Video', 'watch_laters')->withTimestamps();
    }

    /**
     * @doc
     * 
     */
    public function yourchannels()
    {
    	return $this->hasMany('Xtwoend\Videoplus\Channels\Channel', 'owner_id');
    }

    /**
     * @doc
     * 
     */
    public function yourgroups()
    {
        return $this->hasMany('Xtwoend\Videoplus\Groups\Group', 'owner_id');
    }

    /**
     * @doc
     * Channel User
     */
    public function mpchannels()
    {
    	return $this->belongsToMany('Xtwoend\Videoplus\Channels\Channel', 'channel_users')->withTimestamps();
    }

    /**
     * @doc
     * Groups User
     */
    public function mpgroups()
    {
    	return $this->belongsToMany('Xtwoend\Videoplus\Groups\Group', 'group_users')->withTimestamps();
    }
}