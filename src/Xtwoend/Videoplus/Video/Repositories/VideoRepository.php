<?php namespace Xtwoend\Videoplus\Video\Repositories;

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

use File;
use Carbon\Carbon;
use Xtwoend\Videoplus\Entities\AbstractRepository;
use Xtwoend\Videoplus\Entities\AbstractRepositoryInterface;
use Xtwoend\Videoplus\Video\VideoInterface;
use Xtwoend\Videoplus\Upload\Upload;

class VideoRepository extends AbstractRepository implements VideoInterface , AbstractRepositoryInterface {

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
		$video = $this->model;
		$video->fill($attributes);
		
		return $video = $video->save();
	}

	/**
	 * @doc
	 * 
	 */
	public function update($id, array $attributes = array())
	{
		$hashids = new \Hashids\Hashids(\Config::get('app.key'), 7);
		$attributes['ticket'] = $hashids->encode($id);
		
		$model = $this->model->findOrNew($id);
		$model->fill($attributes);
		$model->save();

		if($model->ticket == '' OR is_null($model->ticket)){
			$model->ticket = $hashids->encode($model->id);
			$model->save();
		}

		return $model;
	}

	/**
	 * @doc
	 * 
	 */
	public function destroy($id)
	{
		$model = $this->model->findOrFail($id);
		if(File::exists($model->path)){
			File::delete($model->path);
		}
		return $model->delete();
	}

	/**
	 * @doc
	 *
	 */
	public function upload($detination, $token)
	{
		$config = array(
                    'temp'  => base_path('temp'),
                    'dest'  => $detination
                );

		$upload = new Upload($config);
		
		if($file = $upload->start()){
			
			$hashids = new \Hashids\Hashids(\Config::get('app.key'), 7);
			
			$video = $this->model->create([
								//ticket'	=> str_random(40),
								'source_url'=> $file['url_stream'],
								'path'		=> $file['source'],
								'size'		=> $file['size'],
								//'duration'	=> $file['duration'],
								'source_type' => 'L',
								'raw_path'	=> $file['source'],
								'token'	=> $token
								]);
			
			$video->ticket = $hashids->encode($video->id);
			$video->save();
			return $video;
		}
	}


	/**
	 * @doc
	 * 
	 */
	public function total_usege($user_id)
	{
		return $this->model->where('owner_id','=', $user_id)->sum('size');
	}

	/**
	 * @doc
	 * 
	 */
	public function unpublish()
	{
		return $this->model->where('publish','=', 0)->get();
	}

	/**
	 * @doc
	 * 
	 */
	public function public_videos()
	{
		return $this->model->where('who_watch', '=', 1)->get();
	}
}
