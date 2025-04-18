<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * CanLoadRelationships
 */
trait CanLoadRelationships
{
    /**
     * loadRelationships
     *
     * @param Model|Builder|QueryBuilder|HasMany $for
     * @param array $relations
     * @return Model|Builder|QueryBuilder|HasMany $for
     */
    public function loadRelationships(
        Model|Builder|QueryBuilder|HasMany $for,
        ?array $relations = null
    ): Model|Builder|QueryBuilder|HasMany {
        $relations = $relations ?? $this->relations ?? [];

        foreach ($relations as $relation) {
            $for->when(
                $this->shouldIncludeRelation($relation),
                fn ($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
            );
        }

        return $for;
    }

    /**
     * shouldIncludeRelation
     *
     * @param string $relation
     * @return boolean
     */
    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = \request()->query('include');

        if (! $include) {
            return false;
        }

        $relations = \array_map('trim', \explode(',', $include));

        return in_array($relation, $relations);
    }
}
