<?php  namespace Xtwoend\Videoplus\Users;

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

interface  UserInterface {
	
	/**
	 * @doc
	 * 
	 */
	public function total_usege($id=null);
	
	/**
	 * @doc
	 * 
	 */
	public function activated($id);

	/**
	 * @doc
	 * 
	 */
	public function make_admin($id);

} // END interface 