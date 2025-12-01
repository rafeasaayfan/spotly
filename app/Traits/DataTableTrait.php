<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

trait DataTableTrait
{
    protected function dataTable($query, $request, $columsSearching = [], $columnsSelection = [], $relations = [])
    {
        try {
            $search          = trim($request->search ?? null);
            $filters         = $request->filter ?? [];
            $sortBy          = $request->sortBy ?? 'id';
            $sortDir         = $request->sort_dir ?? 'desc';
            // $page = (int)$request->get('page', 1);
            $perPage         = (int) ($request->limit ?? 10);
            $model = $query->getModel();

            // Apply column selection
            if (!empty($columnsSelection)) {
                $this->applyColumnSelection($query, $columnsSelection);
            }

            if (method_exists($model, 'media')) {
                $query->with('media');
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

            $result = $query->paginate($perPage);

            // Transform result to flatten relation data
            if (!empty($relations)) {
                $this->flattenRelationData($result, $relations);
            }

            return $result;
        } catch (\Throwable $e) {
            Log::error('Error on DataTable: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error on DataTable',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
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
        $existingColumns = $query->getQuery()->columns;

        if ($existingColumns) {
            $columns = array_merge($columns, $existingColumns);
        }

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
                if (str_contains($relation, ':')) {
                    [$relationWithCol, $foreignKey] = explode(':', $relation, 2);
                    [$relationName, $field] = explode('_', $relationWithCol, 2);
                    $query->with([$relationName => function ($q) use ($foreignKey, $field) {
                        $q->select('id', $foreignKey, $field);
                    }]);
                } else {
                    [$relationName, $field] = explode('_', $relation, 2);
                    $query->with([$relationName => function ($q) use ($field) {
                        $q->select('id', $field);
                    }]);
                }
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
                        $subQ->where($field, 'like', "%{$search}%");
                    });
                } else {
                    $q->orWhere($column, 'like', "%{$search}%");
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
            if (trim($value) === 'empty' || trim($value) === 'notEmpty') {
                $value === 'notEmpty'
                    ? $query->whereNotNull($key)
                    : $query->whereNull($key);
                continue;
            }

            if (trim($value) === 'all' || $value === null) {
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
            $query->where($key, trim($value));
        }
    }

    /**
     * Flatten relation data in result
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $result
     * @param array $relationColumns
     * @return void
     */
    protected function flattenRelationData($result, array $relationColumns)
    {
        if ($result instanceof \Illuminate\Pagination\AbstractPaginator || $result instanceof \Illuminate\Support\Collection) {
            $result->getCollection()->transform(function ($item) use ($relationColumns) {
                return $this->applyFlattening($item, $relationColumns);
            });
        } else {
            return $this->applyFlattening($result, $relationColumns);
        }
    }

    protected function applyFlattening($item, array $relationColumns)
    {
        foreach ($relationColumns as $relationColumn) {
            if (str_contains($relationColumn, '_')) {
                $relation = null;
                $field = null;

                if (str_contains($relationColumn, ':')) {
                    [$relationWithCol, $foreignKey] = explode(':', $relationColumn, 2);
                    [$relation, $field] = explode('_', $relationWithCol, 2);
                } else {
                    [$relation, $field] = explode('_', $relationColumn, 2);
                }

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
    }
}
