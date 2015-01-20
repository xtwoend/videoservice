<?php namespace Xtwoend\Videoplus\Sliders;
    	
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

class Slide extends Model {	

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sliders';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';

	protected $fillable = [	
							'title',
							'description',
							'image',
							'link',
							'style',
							'active',
							'trailer_url'
						];
}