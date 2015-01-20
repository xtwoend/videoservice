<?php namespace Xtwoend\Videoplus\Sliders\Repositories;
    	
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
use Xtwoend\Videoplus\Sliders\SlideInterface;
use Xtwoend\Videoplus\Entities\AbstractRepository;
use Xtwoend\Videoplus\Entities\AbstractRepositoryInterface;

class SlideRepository extends AbstractRepository implements SlideInterface , AbstractRepositoryInterface {	
	
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
	 * 
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * @doc
	 * 
	 */
	public function setModel(Model $model)
	{
		$this->model = $model;
	}
	
	/**
	 * @doc
	 * 
	 */
	public function all()
	{	
		$posts = $this->model->all();
		return $posts;
	}

	/**
	 * @doc
	 * 
	 */
	public function create(array $attributes = array())
	{
		$post = $this->model;
		$post->fill($attributes);
		$post->save();
		return $post;
	}

	/**
	 * @doc
	 * 
	 */
	public function update($id, array $attributes = array())
	{
		$model = $this->model->find($id);
		$model->fill($attributes);
		$model->save();
		return $model;
	}

	/**
	 * @doc
	 * 
	 */
	public function delete($id)
	{
		$model = $this->model->find($id);
		return $model->delete();
	}


	/**
	 * @doc
	 * 
	 */
	public function getActiveSlide()
	{	
		return $this->findAllBy('active',1);	 
	}
}