<?php


namespace Brainr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ProfileLocation extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['address', 'country', 'lng', 'lat', 'profile_id'];


    /**
     * @var array
     */
    protected $visible = ['id', 'address', 'country', 'lng', 'lat'];

    /**
     * Relations to its profile
     *
     * @return BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
