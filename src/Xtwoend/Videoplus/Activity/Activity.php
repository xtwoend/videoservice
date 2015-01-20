<?php namespace Xtwoend\Videoplus\Activity;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model {
    
    /**
     * Table name
     * @var string
     */
	protected $table = 'activities';

	
    /**
     *
     * {@inheritdoc}
     */ 
    protected $fillable = array('owner_id','type','object_id','events');
	
    
    /**
     * Model User
     */
    protected static $user = 'Xtwoend\Videoplus\Users\User';
    
    /**
     *
     * @params
     */
    public function owner()
    {
        return $this->belongsTo(static::$user, 'owner_id');
    }

}