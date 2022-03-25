<?php

namespace Brainr\Observers;

use Brainr\Profile;

class ProfileObserver
{
    /**
     * Handle the profile "created" event.
     *
     * @param  \Brainr\Profile  $profile
     *
     * @return void
     */
    public function created(Profile $profile)
    {
    }

    /**
     * Handle the profile "updated" event.
     *
     * @param  \Brainr\Profile  $profile
     *
     * @return void
     */
    public function updated(Profile $profile)
    {
        if ($profile->published !== $profile->getOriginal('published')) {
            foreach ($profile->relations as $relation) {
                $relation->visible = $relation->profiles()
                                              ->where('published', true)
                                              ->count() > 1;

                $relation->save();
            }
        }
    }

    /**
     * Handle the profile "deleted" event.
     *
     * @param  \Brainr\Profile  $profile
     *
     * @return void
     */
    public function deleted(Profile $profile)
    {
        $profile->relations()
                ->delete();

        $profile->forceDelete();
    }

    /**
     * Handle the profile "restored" event.
     *
     * @param  \Brainr\Profile  $profile
     *
     * @return void
     */
    public function restored(Profile $profile)
    {
    }

    /**
     * Handle the profile "force deleted" event.
     *
     * @param  \Brainr\Profile  $profile
     *
     * @return void
     */
    public function forceDeleted(Profile $profile)
    {
    }
}
