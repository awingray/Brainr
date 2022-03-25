<?php

namespace Brainr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileFile extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['path', 'filename', 'filesize'];


    /**
     * @var array
     */
    protected $visible = ['id','path', 'filename', 'filesize'];

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
