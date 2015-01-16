<?php namespace Xtwoend\Videoplus\Competitions;
    	
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

class Competition extends Model {	
	
	//taging channel
  use \Xtwoend\Videoplus\Tagging\Traits\Taggable;

  protected static $partisipan = 'Xtwoend\Videoplus\Competitions\Partisipan';

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'competitions';

  /**
  * The primary key for the model.
  *
  * @var string
  */
  //protected $primaryKey = 'post_id';

  protected $fillable = [
    'name',
    'description',
    'content',
    'start_date',
    'end_date',
    'owner_id',
    'com_type',
    'briff_attach',
    'file_attach',
    'image',
    'slug',
    'total_prize'
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


    public function partisipans()
    {
      return $this->hasMany(static::$partisipan, 'competition_id');
    }
	
}