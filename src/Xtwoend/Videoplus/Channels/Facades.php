<?php namespace Xtwoend\Videoplus\Channels;

use Illuminate\Support\Facades\Facade;

class Facades extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'Xtwoend\Videoplus\Channels\ChannelInterface'; }

}