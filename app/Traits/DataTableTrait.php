<?php

namespace App\Traits;

use Illuminate\Support\Str;

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

        $query->orderBy($sortBy, $sortDir);

        $results = $query->paginate($perPage);

        // Transform results to flatten relation data
        // $this->flattenRelationData($results, $relations);

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
            if (str_contains($relation, '.')) {
                [$relation, $field] = explode('.', $relation, 2);
                $query->withSelectedColumns($relation, $field);
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
            if ($key === 'email_verified') {
                $value === 'verified'
                    ? $query->whereNotNull('email_verified_at')
                    : $query->whereNull('email_verified_at');
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
            foreach ($relationColumns as $relation => $fields) {
                if (!isset($item->$relation)) {
                    continue;
                }

                foreach ($fields as $field) {
                    $item->setAttribute("{$relation}_{$field}", $item->$relation->$field ?? null);
                }

                unset($item->$relation);
            }

            return $item;
        });
    }
}
