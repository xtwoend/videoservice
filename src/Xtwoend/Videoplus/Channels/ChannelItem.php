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

class ChannelItem extends Model {

  protected static $channel = 'Xtwoend\Videoplus\Channels\Channel';
  protected static $video = 'Xtwoend\Videoplus\Video\Video';

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'channel_videos';

  /**
  * The primary key for the model.
  *
  * @var string
  */
  //protected $primaryKey = 'post_id';

  protected $fillable = [
      'video_url',
      'video_id',
      'channel_id'
  ];

  public function channel()
  {
      return $this->belongsTo(static::$channel);
  }

  /**
   * @doc
   * 
   */
  public function video()
  {
    return $this->hasOne(static::$video, 'videos_id');
  }
}
