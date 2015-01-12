<?php namespace Xtwoend\Videoplus\Presenters;
    	
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
use DateTime;

abstract class AbstractPresenter {	
	
	/**
     * The model.
     *
     * @var Model
     */
    protected $model;
    /**
     * The attributes.
     *
     * @var array
     */
    protected $attributes;
    /**
     * The options.
     *
     * @var array
     */
    protected $options;
    /**
     * Create a new instance.
     *
     * @param  Model $model
     * @param  Array $options
     * @return void
     */
    public function __construct(Model $model, Array $options = array())
    {
        $this->model   = $model;
        $this->options = $options;
        $this->prepareData();
        unset($this->model);
    }
    /**
     * Return the model.
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }
    /**
     * Return a option.
     *
     * @param  string $key
     * @return mixed
     */
    public function getOption($key)
    {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        }
        return null;
    }
    /**
     * Prepare the data to retreive.
     *
     * @return void
     */
    public function prepareData()
    {}
    /**
     * Get an attribute from the template.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (isset($this->$key)) {
            return $this->$key;
        }
        return '';
    }
    /**
     * Set a given attribute on the template from model.
     *
     * @param  string  $key
     * @param  string  $newKey
     * @param  mixed  $value
     * @return void
     */
    public function setAttribute($key, $newKey = null, $value = null)
    {
        if (is_null($value)) {
            $value = $this->model->$key;
        }
        if ($value instanceof DateTime) {
            $value = $value->format('c');
        }
        if (is_numeric($value)) {
            $value = (float) $value;
        }
        if (is_null($newKey)) {
            $this->$key = $value;
        } else {
            $this->$newKey = $value;
        }
        unset($value);
    }
    /**
     * Dynamically retrieve attributes on the template.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }
	
}