<?php

namespace Brainr\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TrueColumnScope implements Scope
{
    /**
     * @var
     */
    private $column;

    /**
     * TrueColumnScope constructor.
     *
     * @param $column
     */
    public function __construct($column)
    {
        $this->column = $column;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where($this->column, 1);
    }
}
