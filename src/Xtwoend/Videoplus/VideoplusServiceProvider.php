<?php namespace Xtwoend\Videoplus;

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

use Illuminate\Support\ServiceProvider;
use Xtwoend\Videoplus\Video\Repositories\VideoRepository;
use Xtwoend\Videoplus\Channels\Repositories\ChannelRepository;
use Xtwoend\Videoplus\Groups\Repositories\GroupRepository;
use Xtwoend\Videoplus\Posts\Repositories\PostRepository;
use Xtwoend\Videoplus\Users\Repositories\UserRepository;
use Xtwoend\Videoplus\Category\Repositories\CategoryRepository;
use Xtwoend\Videoplus\Sliders\Repositories\SlideRepository;
use Xtwoend\Videoplus\Activity\Repositories\ActivityRepository;
use Xtwoend\Videoplus\OnDemand\Repositories\OnDemandRepository;
use Xtwoend\Videoplus\Competitions\Repositories\CompetitionRepository;
use Xtwoend\Videoplus\Users\MPSdk;
use Event;

class VideoplusServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;


	/**
	 * @doc
	 * Start Auto After Booting Service Provider
	 */
	public function boot()
	{
		Event::listen('video.watch','Xtwoend\Videoplus\Events\WatchVideoHandler');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerVideoRepository();
		$this->registerChannelRepository();
		$this->registerGroupRepository();
		$this->registerPostRepository();
		$this->registerUserRepository();
		$this->registerCategoryRepository();
		$this->registerSlideRepository();
		$this->registerActivityRepository();
		$this->registerCompetitionRepository();
		$this->registerOnDemandRepository();
		$this->registerMpApi();
	}

	/**
	 * register video repository
	 *
	 */
	public function registerVideoRepository()
	{
		$this->app->bind('Xtwoend\Videoplus\Video\VideoInterface', function($app)
		{
			// initial model post
			$model = 'Xtwoend\Videoplus\Video\Video';
			$class = '\\'.ltrim($model, '\\');

			//register post repository
			return new VideoRepository(new $class);
		});

		/*
		$this->app['video.repository'] = $this->app->share(function($app)
		{

			// initial model post
			$model = 'Xtwoend\Videoplus\Video\Video';
			$class = '\\'.ltrim($model, '\\');

			//register post repository
			return new VideoRepository(new $class);
		});
		*/
	}

	/**
	* register Channel repository
	*
	*/
	public function registerChannelRepository()
	{
		$this->app->bind('Xtwoend\Videoplus\Channels\ChannelInterface', function($app)
		{
			// initial model post
			$model = 'Xtwoend\Videoplus\Channels\Channel';
			$class = '\\'.ltrim($model, '\\');

			//register post repository
			return new ChannelRepository(new $class);
		});
	}

	/**
	* register Groups repository
	*
	*/
	public function registerGroupRepository()
	{
		$this->app->bind('Xtwoend\Videoplus\Groups\GroupInterface', function($app)
		{
			// initial model post
			$model = 'Xtwoend\Videoplus\Groups\Group';
			$class = '\\'.ltrim($model, '\\');

			//register post repository
			return new GroupRepository(new $class);
		});
	}

	/**
	* register Category repository
	*
	*/
	public function registerCategoryRepository()
	{
		$this->app->bind('Xtwoend\Videoplus\Category\CategoryInterface', function($app)
		{
			// initial model post
			$model = 'Xtwoend\Videoplus\Category\Category';
			$class = '\\'.ltrim($model, '\\');

			//register post repository
			return new CategoryRepository(new $class);
		});
	}

	/**
	* register OnDemand repository
	*
	*/
	public function registerOnDemandRepository()
	{
		$this->app->bind('Xtwoend\Videoplus\OnDemand\OnDemandInterface', function($app)
		{
			// initial model post
			$model = 'Xtwoend\Videoplus\OnDemand\OnDemand';
			$class = '\\'.ltrim($model, '\\');

			//register post repository
			return new OnDemandRepository(new $class);
		});
	}

	/**
	* register Post repository
	*
	*/
	public function registerPostRepository()
	{
		$this->app->bind('Xtwoend\Videoplus\Posts\PostInterface', function($app)
		{
			// initial model post
			$model = 'Xtwoend\Videoplus\Posts\Post';
			$class = '\\'.ltrim($model, '\\');

			//register post repository
			return new PostRepository(new $class);
		});
	}

	/**
	* register User repository
	*
	*/
	public function registerUserRepository()
	{
		$this->app->bind('Xtwoend\Videoplus\Users\UserInterface', function($app)
		{
			// initial model post
			$model = 'Xtwoend\Videoplus\Users\User';
			$class = '\\'.ltrim($model, '\\');

			//register post repository
			return new UserRepository(new $class);
		});
	}

	/**
	* register Sliders repository
	*
	*/
	public function registerSlideRepository()
	{
		$this->app->bind('Xtwoend\Videoplus\Sliders\SlideInterface', function($app)
		{
			// initial model post
			$model = 'Xtwoend\Videoplus\Sliders\Slide';
			$class = '\\'.ltrim($model, '\\');

			//register post repository
			return new SlideRepository(new $class);
		});
	}

	/**
	* register Activity Log repository
	*
	*/
	public function registerActivityRepository()
	{
		$this->app->bind('Xtwoend\Videoplus\Activity\ActivityInterface', function($app)
		{
			// initial model post
			$model = 'Xtwoend\Videoplus\Activity\Activity';
			$class = '\\'.ltrim($model, '\\');

			//register post repository
			return new ActivityRepository(new $class);
		});
	}

	/**
	* register Competition Log repository
	*
	*/
	public function registerCompetitionRepository()
	{
		$this->app->bind('Xtwoend\Videoplus\Competitions\CompetitionInterface', function($app)
		{
			// initial model post
			$model = 'Xtwoend\Videoplus\Competitions\Competition';
			$class = '\\'.ltrim($model, '\\');

			//register post repository
			return new CompetitionRepository(new $class);
		});
	}

	/**
	 * @doc
	 * 
	 */
	public function registerMpApi()
	{	
		$this->app->bind('Merahputih\MpApi', function(){
			return new MPSdk(\Config::get('merahputih.api'));
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array(
				'Xtwoend\Videoplus\Channels\ChannelInterface',
				'Xtwoend\Videoplus\Sliders\SlideInterface',
				'Xtwoend\Videoplus\Video\VideoInterface',
				'Xtwoend\Videoplus\Groups\GroupInterface'
				);
	}
}
