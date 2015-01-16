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

use Illuminate\Database\Eloquent\Model;
use Xtwoend\Videoplus\Observers\SlugObserver;
use Xtwoend\Videoplus\Observers\OwnerObserver;

class Video extends Model {

  //trait tagging
  use \Xtwoend\Videoplus\Tagging\Traits\Taggable;
  use \Xtwoend\Videoplus\Relations\VideoRelations;
  use \Xtwoend\Videoplus\Activity\Traits\Activitable;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'videos';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	//protected $primaryKey = 'post_id';

    protected $fillable = [
      'ticket',
      'slug',
      'title',
      'description',
      'source_url',
      'source_type', // Y => youtube, V => Vimeo, L = Local Source
      'path',
      'subtitle',
      'image',
      'owner_id',
      'status',
      'video_type', //VOD VIDEO
      'trailer_url',
      'duration',
      'size',
      'ondemand',
      'price',
      'content_rating',
      'who_watch',
      'allow_embedded',
      'allow_comment',
      'allow_download',
      'allow_collected',
      'views',
      'password_access'
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

    

}
