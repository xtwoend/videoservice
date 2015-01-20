<?php namespace Xtwoend\Videoplus\Activity\Traits;

/**
 * Comment Trait
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the MIT License.
 *
 * This source file is subject to the MIT License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://opensource.org/licenses/MIT
 *
 * @package    Comment
 * @version    1.0.0
 * @author     Abdul Hafidz A <aditans88@gmail.com>
 * @license    MIT License
 */


trait Activitable {

	/**
	 * Return collection of comments related to this model
	 * 
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function activities() 
	{
		return $this->morphMany('Xtwoend\Videoplus\Activity\Activity', 'activitable');
	}
	

	/**
	 * Adds a Comment
	 * 
	 * @param $attribute array
	 */
	public function addActivity(array $attribute) {
		
		return $this->activities()->create($attribute);
	}

	/**
	 * Removes a single Comment
	 * 
	 * @param $id integer
	 */
	public function removeActivity($id) {
		
		return $this->activities()->find($id)->delete();
	}

}