<?php namespace Xtwoend\Videoplus\Users;

/**
 * Part of the Auth package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Auth
 * @version    2.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2013, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Illuminate\Database\Eloquent\Model;

class MPUser extends Model {

	protected $connection = 'api';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'tbl_user';
	
}




