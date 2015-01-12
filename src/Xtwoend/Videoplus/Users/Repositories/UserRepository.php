<?php namespace Xtwoend\Videoplus\Users\Repositories;

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

use Carbon\Carbon;
use Xtwoend\Videoplus\Entities\AbstractRepository;
use Xtwoend\Videoplus\Entities\AbstractRepositoryInterface;
use Xtwoend\Videoplus\Users\UserInterface;
use Sentry;

class UserRepository extends AbstractRepository implements UserInterface , AbstractRepositoryInterface {

	/**
	 * model
	 */

	protected $model;

	/**
	 *
	 * @params
	 */
	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	/**
	 * @doc
	 * get default model active
	 */
	public function getModel()
	{
		return $this->model;
	}


	/**
	 * @doc
	 * set model to default
	 */
	public function setModel(Model $model)
	{
		$this->model = $model;
	}


	/**
	 * @doc
	 * insert new video
	 */
	public function create(array $attributes=array())
	{
		$video = $this->model;
		$video->fill($attributes);

		return $video = $video->save();
	}


	/**
	 * @doc
	 * 
	 */
	public function total_usege($id = null)
	{	
		if(is_null($id)) {
			if(Sentry::check()){
				$id = Sentry::getUser()->id;
			}
		}
		//return $this->model->where('owner_id','=', $user_id)->sum('size');
	}

	public function activated($id)
	{
		$user = $this->find($id);
		if($user){
			$set = ($user->activated == 1) ? 0 : 1;

			$user->activated = $set;

			$user->save();
		}
	}


	/**
	 * @doc
	 * 
	 */
	public function make_admin($id)
	{
		$user = $this->find($id);
		
		$adminGroup = Sentry::findGroupById(1);
	        // Find the Administrator group
		$admin = Sentry::findGroupByName('Administrator');

		    // Check if the user is in the administrator group
		    if ($user->inGroup($admin))
		    {
		        // User is in Administrator group
		        $user->removeGroup($adminGroup);
		    }
		    else
		    {
		    	$user->addGroup($adminGroup);
		        // User is not in Administrator group
		    }
	   
	}
}
