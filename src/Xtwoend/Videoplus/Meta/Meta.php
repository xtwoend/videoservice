<?php namespace Xtwoend\Videoplus\Meta;

/**
 * Meta
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the MIT License.
 *
 * This source file is subject to the MIT License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://opensource.org/licenses/MIT
 *
 * @package    Meta
 * @version    1.0.0
 * @author     Abdul Hafidz A <aditans88@gmail.com>
 * @license    MIT License
 */


class Meta 
{

	/**
	 * Regions in the theme.
	 *
	 * @var array
	 */
	protected $regions = array();

	/**
	 * Content arguments.
	 *
	 * @var array
	 */
	protected $arguments = array();

	/**
	 * Set a place to regions.
	 *
	 * @param  string $region
	 * @param  string $value
	 * @return Theme
	 */
	public function set($region, $value)
	{
		// Content is reserve region for render sub-view.
		if ($region == 'content') return;

		$this->regions[$region] = $value;

		return $this;
	}


	/**
	 * Check region exists.
	 *
	 * @param  string  $region
	 * @return boolean
	 */
	public function has($region)
	{
		return (boolean) isset($this->regions[$region]);
	}

	/**
	 * Render a region.
	 *
	 * @param  string $region
	 * @param  mixed  $default
	 * @return string
	 */
	public function get($region, $default = null)
	{
		if ($this->has($region))
		{
			return $this->regions[$region];
		}

		return $default ? $default : '';
	}
	
	/**
	 * Magic method for set, prepend, append, has, get.
	 *
	 * @param  string $method
	 * @param  array  $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters = array())
	{
		$callable = preg_split('|[A-Z]|', $method);

		if (in_array($callable[0], array('set', 'get')))
		{
			$value = lcfirst(preg_replace('|^'.$callable[0].'|', '', $method));

			array_unshift($parameters, $value);

			return call_user_func_array(array($this, $callable[0]), $parameters);
		}

		trigger_error('Call to undefined method '.__CLASS__.'::'.$method.'()', E_USER_ERROR);
	}
}