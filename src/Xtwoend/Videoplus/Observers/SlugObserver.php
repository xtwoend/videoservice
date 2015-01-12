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


use Illuminate\Support\Str;

class SlugObserver
{

    public function creating($model)
    {
        // slug = null si vide
        $slug = (! is_null($model->slug)) ? $model->slug : null ;
        $model->slug = Str::slug($slug,'');

        if ($slug) {
            $i = 0;
            // Check uri is unique
            while ($model::where('slug', $model->slug)->first()) {
                $i++;
                // increment slug if exists
                $model->slug = Str::slug($slug.$i,'');
            }
        }
    }

    public function updating($model)
    {
        // slug = null si vide
        $slug =  (! is_null($model->slug)) ? $model->slug : null ;
        $model->slug =  Str::slug($slug,'');

        if ($slug) {
            $i = 0;
            // Check uri is unique
            while (
                $model::where('slug', $model->slug)
                    ->where('id', '!=', $model->id)
                    ->first()
                ) {
                $i++;
                // increment slug if exists
                $model->slug = Str::slug($slug.$i,'');
            }
        }
    }
}
