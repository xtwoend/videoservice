<?php namespace Xtwoend\Videoplus\Channels;

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

interface ChannelInterface {

  /**
  * @doc
  * get model
  */
  public function getModel();

  /**
  * @doc
  * set model
  */
  public function setModel(Model $model);

  /**
  * @doc
  * Create Post by Attributes (fill)
  */
  public function create(array $attributes = array());

  /**
   * @doc
   * update channel
   */
  public function update($id, array $attributes=array());

  /**
   * @doc
   * channel list official
   */
  public function official();
  

  public function tv();

  public function series();
}
