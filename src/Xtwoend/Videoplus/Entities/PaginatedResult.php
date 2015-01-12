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

use Illuminate\Database\Eloquent\Collection;

class PaginatedResult implements PaginatedInterface {
	/**
	 * current page
	 *
	 * @var int
	 */
	protected $page = 1;
	/**
	 * current limit
	 *
	 * @var int
	 */
	protected $limit = 10;
	/**
	 * total items count
	 *
	 * @var int
	 */
	protected $totalItems = 0;
	/**
	 * items
	 *
	 * @var Collection
	 */
	protected $items;
	/**
	 * creates an instance of the paginated result
	 *
	 * @param int $page
	 * @param int $limit
	 * @param int $totalItems
	 * @param Collection $items
	 */
	public function __construct($page, $limit, $totalItems, $items)
	{
		$this->page = $page;
		$this->limit = $limit;
		$this->totalItems = $totalItems;
		$this->items = $items;
	}
	/**
	 * returns current page
	 *
	 * @return int
	 */
	public function getPage()
	{
		return $this->page;
	}
	/**
	 * returns current limit
	 *
	 * @return int
	 */
	public function getLimit()
	{
		return $this->limit;
	}
	/**
	 * returns total items
	 *
	 * @return int
	 */
	public function getTotalItems()
	{
		return $this->totalItems;
	}
	/**
	 * returns items for page
	 *
	 * @return Collection
	 */
	public function getItems()
	{
		if ($this->items === null)
			return new Collection();
		return $this->items;
	}
}