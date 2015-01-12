<?php namespace Xtwoend\Videoplus\Competitions\Repositories;

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

use Xtwoend\Videoplus\Entities\AbstractRepository;
use Xtwoend\Videoplus\Entities\AbstractRepositoryInterface;
use Xtwoend\Videoplus\Competitions\CompetitionInterface;


class CompetitionRepository extends AbstractRepository implements CompetitionInterface , AbstractRepositoryInterface {

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
    $model = $this->model;
    $model->fill($attributes);
    return $model = $model->save();
  }

  /**
   * @doc
   * 
   */
  public function update($id, array $attributes = array())
  {
    $model = $this->model->findOrNew($id);
    $model->fill($attributes);
    $model->save();

    return $model;
  }

}
