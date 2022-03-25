<?php

namespace Brainr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['type', 'detail'];

    /**
     * @var string[]
     */
    protected $visible = ['id', 'type', 'detail', 'created_at', 'updated_at'];

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
