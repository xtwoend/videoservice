<?php namespace Xtwoend\Videoplus\OnDemand;

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

class OnDemand extends Model {

  //public $timestamps = false;

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'ondemand';

  /**
  * The primary key for the model.
  *
  * @var string
  */
  //protected $primaryKey = 'post_id';

  protected $fillable = [
    'title',
    'slug',
    'description',
    'image',
    'price_rent',
    'price_buy',
    'trailer_id',
    'video_id',
    'owner_id',
    'genres',
    'category_id',
    'duration'
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
