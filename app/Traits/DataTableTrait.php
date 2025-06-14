<?php

namespace App\Traits;

// use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

trait DataTableTrait
{
    protected function dataTable($query, $request, $columsSearching = [], $columnsSelection = [], $relations = [])
    {
        $search = trim($request->input('search', ''));
        $filters = $request->input('filter', []);
        $sortBy = $request->input('sort_by', 'id');
        $sortDir = $request->input('sort_dir', 'desc');
        // $page = (int)$request->get('page', 1);
        $perPage = (int)$request->input('limit', 10);

        // Apply column selection
        if (!empty($columnsSelection)) {
            $this->applyColumnSelection($query, $columnsSelection);
        }

        // Load relations
        if (!empty($relations)) {
            $this->loadRelations($query, $relations);
        }

        if (!empty($search)) {
            $this->applySearch($query, $search, $columsSearching);
        }

        if (!empty($filters)) {
            $this->applyFilters($query, $filters);
        }

        $table = $query->getModel()->getTable();
        if (Schema::hasColumn($table, $sortBy)) {
            $query->orderBy($sortBy, $sortDir);
        }

        $results = $query->paginate($perPage);

        // Transform results to flatten relation data
        if (!empty($relations)) {
            $this->flattenRelationData($results, $relations);
        }

        return $results;
    }

    /**
     * Apply column selection to query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param (array || string) $columns
     * @return void
     */
    protected function applyColumnSelection($query, array $columns)
    {
        $query->select($columns);
    }

    /**
     * Load relations with their columns
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $relationColumns
     * @return void
     */
    protected function loadRelations($query, array $relationColumns)
    {
        foreach ($relationColumns as $relation) {
            if (str_contains($relation, '_')) {
                [$relationName, $field] = explode('_', $relation, 2);
                $query->with([$relationName => function ($q) use ($field) {
                    $q->select('id', $field);
                }]);
            } else {
                $query->with($relation);
            }
        }
    }

    /**
     * Apply search conditions to query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @param array $columns
     * @return void
     */
    protected function applySearch($query, string $search, array $columns)
    {
        $query->where(function ($q) use ($search, $columns) {
            foreach ($columns as $column) {
                if (str_contains($column, '.')) {
                    [$relation, $field] = explode('.', $column, 2);
                    $q->orWhereHas($relation, function ($subQ) use ($field, $search) {
                        $subQ->where($field, 'like', "{$search}%");
                    });
                } else {
                    $q->orWhere($column, 'like', "{$search}%");
                }
            }
        });
    }

    /**
     * Apply filters to query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return void
     */
    protected function applyFilters($query, array $filters)
    {
        foreach ($filters as $key => $value) {
            // Special handling for email verification status
            if ($value === 'empty' || $value === 'notEmpty') {
                $value === 'notEmpty'
                    ? $query->whereNotNull($key)
                    : $query->whereNull($key);
                continue;
            }

            if($value === 'all' || $value === null) {
                continue;
            }

            // Handle array values as whereIn conditions
            if (is_array($value)) {
                if (!empty($value)) {
                    $query->whereIn($key, $value);
                }
                continue;
            }

            // Standard where condition
            $query->where($key, $value);
        }
    }

    /**
     * Flatten relation data in results
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $results
     * @param array $relationColumns
     * @return void
     */
    protected function flattenRelationData($results, array $relationColumns)
    {
        $results->getCollection()->transform(function ($item) use ($relationColumns) {
            foreach ($relationColumns as $relationColumn) {
                if (str_contains($relationColumn, '_')) {
                    [$relation, $field] = explode('_', $relationColumn, 2);

                    if (!isset($item->$relation)) {
                        continue;
                    }

                    $relationValue = $item->$relation;


                    if ($relationValue instanceof \Illuminate\Support\Collection) {
                        $flattenedValue = $relationValue->pluck($field)->filter()->implode(', ');
                    }
                    // If it's a single model (hasOne, belongsTo)
                    elseif ($relationValue instanceof \Illuminate\Database\Eloquent\Model) {
                        $flattenedValue = $relationValue->$field ?? null;
                    } else {
                        $flattenedValue = null;
                    }

                    $item->setAttribute("{$relation}_{$field}", $flattenedValue);

                    unset($item->$relation);
                }
            }

            return $item;
        });
    }

    /**
     * Create and edit relations data in return
     *
     * @param string $model
     * @param (array | string) $selectedCols
     * @return array
     */
    protected function getRelation(string $model, array|string $selectedCols = 'all')
    {
        // $alias = Str::plural(Str::lower($model));

        $modelClass = "\\App\\Models\\" . ucfirst($model);

        if (!class_exists($modelClass)) {
            return collect();
        }

        if ($selectedCols === 'all') {
            return $modelClass::all();
        }

        if (is_array($selectedCols)) {
            return $modelClass::select(['id', ...$selectedCols])->get();
        }

        return $modelClass::select(['id', $selectedCols])->get();
    }
}
