<?php namespace Xtwoend\Videoplus\Video;

use Illuminate\Support\Facades\Facade;

class Facades extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'Xtwoend\Videoplus\Video\VideoInterface'; }

}