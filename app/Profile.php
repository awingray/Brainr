<?php

namespace Brainr;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use Taggable, Ownable, SoftDeletes;

    /**
     * @var array
     */
    protected $visible = ['id', 'name', 'description', 'content', 'published', 'created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'content', 'published'];

    /**
     * @var array
     */
    protected $attributes = [
        'published' => false,
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    /**
     * Relations to other profiles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function relations()
    {
        return $this->belongsToMany(ProfileRelation::class, 'related');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(ProfileFile::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany(ProfileLocation::class);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Brainr\Profile  $profile
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnrelated(Builder $query, Profile $profile)
    {
        return $query->where('id', '!=', $profile->id)
                     ->whereNotIn('id', function ($query) use ($profile) {
                         $query->select('pprl.profile_id')
                               ->from('related AS pprl')
                               ->rightJoin('related AS pprr',
                                   'pprl.profile_relation_id', '=', 'pprr.profile_relation_id')
                               ->where('pprr.profile_id', $profile->id);
                     });
    }
}
