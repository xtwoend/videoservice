<?php namespace Xtwoend\Videoplus\Entities;
    	
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



interface AbstractRepositoryInterface {	
	
	/**
	 * returns a collection of all models
	 *
	 * @return Collection
	 */
	public function all();
	/**
	 * returns the model found
	 *
	 * @param int $id
	 * @return Model
	 */
	public function find($id);
	/**
	 * returns the repository itself, for fluent interface
	 *
	 * @param array $with
	 * @return self
	 */
	public function with(array $with);
	/**
	 * returns the first model found by conditions
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param string $operator
	 * @return Model
	 */
	public function findFirstBy($key, $value, $operator = '=');
	/**
	 * returns all models found by conditions
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param string $operator
	 * @return Collection
	 */
	public function findAllBy($key, $value, $operator = '=');
	/**
	 * returns all models that have a required relation
	 *
	 * @param string $relation
	 * @return Collection
	 */
	public function has($relation);
	/**
	 * @param int $page
	 * @param int $limit
	 * @return PaginatedInterface
	 */
	public function getPaginated($page = 1, $limit = 10);
	
}