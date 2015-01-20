<?php namespace Xtwoend\Videoplus\Category;

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

class Category extends Model {

  public $timestamps = false;

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'categories';

  /**
  * The primary key for the model.
  *
  * @var string
  */
  //protected $primaryKey = 'post_id';

  protected $fillable = [
    'cat_type',
    'name',
    'slug',
    'parent_id',
    'lft',
    'rgt',
    'depth',
    'domain_url',
    'style_css',
    'lang',
    'status',
    'icon'
    ];

    /**
    * Observe slug
    * @return void
    */
    public static function boot()
    {
      parent::boot();
      static::observe(new SlugObserver);
    }

    /**
     * Model posts
     */
    protected static $post = 'Xtwoend\Videoplus\Posts\Post';
    
    /**
     *
     * @params
     */
    public function posts()
    {
        return $this->hasMany(static::$post, 'category_id');
    }
  }
