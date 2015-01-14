<?php namespace Xtwoend\Videoplus\Relations;

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

trait VideoRelations {

	/**
     * Model Category
     */
    protected static $category = 'Xtwoend\Videoplus\Categories\Category';

    /**
     *
     * @params
     */
    public function category()
    {
      return $this->belongsTo(static::$category, 'category_id');
    }

    /**
     * Model Category
     */
    protected static $channel = 'Xtwoend\Videoplus\Channels\Channel';
    
    /**
     *
     * @params
     */
    public function channels()
    {
        return $this->belongsToMany(static::$channel, 'channel_videos');
    }

    /**
     * Model Category
     */
    protected static $group = 'Xtwoend\Videoplus\Groups\Group';
    
    /**
     *
     * @params
     */
    public function groups()
    {
        return $this->belongsToMany(static::$group, 'mpgroup_videos');
    }

    /**
     * Model User
     */
    protected static $user = 'Xtwoend\Videoplus\Users\User';
    
    /**
     *
     * @params
     */
    public function owner()
    {
        return $this->belongsTo(static::$user, 'owner_id');
    }

}
