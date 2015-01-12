<?php namespace Xtwoend\Videoplus\Posts;
    	
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
use Xtwoend\News\Relations\PostRelations;
use Xtwoend\Videoplus\Observers\SlugObserver;

class Post extends Model {	

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';

	protected $fillable = [	
							'title',
							'rate',
							'slug', 
							'content',
							'category_id',
							'description',
							'owner_id',
							'image',
							'image_title',
							'post_type',
							'trailer_url',
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




    protected static $category = 'Xtwoend\Videoplus\Category\Category';
    /**
     * @doc
     * 
     */
    public function category()
    {
    	return $this->blongsTo(static::$category);
    }
}