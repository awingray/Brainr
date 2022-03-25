<?php

namespace Brainr;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProfileRelation extends Model
{
    use Taggable;

    /**
     * @var array
     */
    protected $visible = ['id', 'name', 'created_at', 'other', 'updated_at', 'profiles'];

    /**
     * @var string[]
     */
    protected $appends = ['other'];

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @var string[]
     */
    protected $with = ['profiles'];

    protected $casts = [
        'visible' => 'boolean',
    ];

    /**
     * The profiles that are linked
     *
     * @return mixed
     */
    public function profiles()
    {
        return $this->belongsToMany(Profile::class, 'related');
    }

    /**
     *
     */
    public function getOtherAttribute()
    {
        if (! $this->pivot) {
            return null;
        }

        return $this->profiles->first(function ($value) {
            return $this->pivot->profile_id != $value->id;
        });
    }
}
