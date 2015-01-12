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
use Xtwoend\Videoplus\Observers\OwnerObserver;

class Partisipan extends Model {	
	

  protected static $competition = 'Xtwoend\Videoplus\Competitions\Competition';

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'partisipants';


  protected $fillable = [
    'competition_id',
    'video_link',
    'owner_id',
    'description',
    'file_attach',
    ];

    /**
    * Observe slug
    * @return void
    */
    public static function boot()
    {
      parent::boot();
      static::observe(new OwnerObserver);
    }


    public function competition()
    {
      return $this->belongsTo(static::$competition);
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