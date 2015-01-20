<?php namespace Xtwoend\Videoplus\Groups;

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

use Illuminate\Database\Eloquent\Model;
use Xtwoend\Videoplus\Observers\SlugObserver;
use Xtwoend\Videoplus\Observers\OwnerObserver;

class Group extends Model {

  //taging
  use \Xtwoend\Videoplus\Tagging\Traits\Taggable;
  use \Xtwoend\Videoplus\Activity\Traits\Activitable;

  protected static $video = 'Xtwoend\Videoplus\Video\Video';

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'mpgroups';

  /**
  * The primary key for the model.
  *
  * @var string
  */
  //protected $primaryKey = 'post_id';

  protected $fillable = [
    'name',
    'description',
    'logo',
    'banner',
    'owner_id',
    'category_id',
    'group_type',
    'slug',
    ];

    /**
    * Observe slug
    * @return void
    */
    public static function boot()
    {
      parent::boot();
      static::observe(new SlugObserver);
      static::observe(new OwnerObserver);
    }


    public function videos()
    {
      return $this->belongsToMany(static::$video, 'mpgroup_videos')->withTimestamps();
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


    /**
     * user follower  groups
     * @params
     */
    public function followers()
    {
      return $this->belongsToMany(static::$user, 'group_users')->withTimestamps();
    }
  }
