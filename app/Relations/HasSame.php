<?php

namespace App\Relations;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class HasSame extends Relation {
    public $fields;

    public function __construct(Builder $query, Model $parent, $fields) {
        $this->fields = $fields;

        parent::__construct($query, $parent);
    }

    public function addConstraints() {
        if (static::$constraints) {
            foreach($this->fields as $field) {
                $this->query->where($field, $this->parent->{$field});
            }
        }
    }

    public function addEagerConstraints(array $models) {}

    public function match(array $models, Collection $results, $relation) {
        foreach ($models as $model) {
            $q = $this->parent->newQuery();

            foreach($this->fields as $field) {
                $q->where($field, $model->{$field});
            }

            $model->setRelation(
                $relation, $q->get()
            );
        }

        return $models;
    }

    public function getResults() {
        return $this->query->get();
    }

    public function initRelation(array $models, $relation) {
        foreach ($models as $model) {
            $model->setRelation($relation, null);
        }

        return $models;
    }
}
