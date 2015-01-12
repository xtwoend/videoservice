<?php namespace Xtwoend\Videoplus\Observers;

/**
* Part of the Observers package.
*
* NOTICE OF LICENSE
*
* Licensed under the 3-clause BSD License.
*
* This source file is subject to the 3-clause BSD License that is
* bundled with this package in the LICENSE file.  It is also available at
* the following URL: http://www.opensource.org/licenses/BSD-3-Clause
*
* @package    Observers
* @version    0.1
* @author     Abdul Hafidz Anshari
* @license    BSD License (3-clause)
* @copyright  (c) 2014
*/

use Sentry;

class OwnerObserver
{

  public function creating($model)
  {
    $owner_id = (! is_null($model->owner_id)) ? $model->owner_id : null ;
    if(Sentry::check()){
    	
    	if(is_null($owner_id)){
  			$owner_id = Sentry::getUser()->id;
  		}
  	}
    $model->owner_id = $owner_id;
  	
  }

}
