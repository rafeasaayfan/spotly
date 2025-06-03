<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RSDataTable extends Command
{
    protected $signature = 'app:r-s-data-table';
    protected $description = 'Command to make new table with its data';

    protected $createEditRelations = [];

    public function handle()
    {
        $name = $this->ask("Enter the name of the vue folder (like users)");
        $Name = ucfirst($name); // Users
        $model = \Illuminate\Support\Str::singular($Name); // User

        $path = $this->ask("Enter the path to this folder", 'dashboard/pages');
        if (!$path) $path = 'dashboard/pages';

        $basePath = resource_path("js/pages/{$path}/{$name}");
        $actionsPath = "$basePath/actions";
        @mkdir($actionsPath, 0777, true);

        $mainColumns = $this->handleColumns();

        $createEditColumns = $this->handleCreateEditColumns($name);

        $mainFile = "$basePath/" . ucfirst($name) . ".vue";
        $createFile = "$actionsPath/Create.vue";
        $editFile = "$actionsPath/Edit.vue";
        $viewFile = "$actionsPath/View.vue";

        file_put_contents($mainFile, $this->getStubContent('Main.vue.stub', $name, $mainColumns));
        file_put_contents($createFile, $this->getStubContent('Create.vue.stub', $name, $createEditColumns));
        file_put_contents($editFile, $this->getStubContent('Edit.vue.stub', $name, $createEditColumns));
        file_put_contents($viewFile, $this->getStubContent('View.vue.stub', $name));

        $this->info("✅ Created Vue DataTable page for '{$name}' at 'resources/js/{$path}/{$name}'");

        //* Controller starts
        $this->comment("Lets make the controller now");
        $controllerPath = $this->ask("Enter controller path", 'Dashboard/Pages');

        // Check if the last part of the path is already the name (case-insensitive)
        $lastPart = collect(explode('/', $controllerPath))->last();

        if (strtolower($lastPart) === strtolower($name)) {
            $requestNamespace = str_replace('/', '\\', "App\\Http\\Requests\\{$controllerPath}");
        } else {
            $requestNamespace = str_replace('/', '\\', "App\\Http\\Requests\\{$controllerPath}\\{$Name}");
        }

        $this->createController($name, $controllerPath, $requestNamespace);

        //* Requests starts
        $this->comment('Store and Update request validation.');
        if (strtolower($lastPart) === strtolower($name)) {
            $requestPath = app_path("Http/Requests/{$controllerPath}");
        } else {
            $requestPath = app_path("Http/Requests/{$controllerPath}/{$Name}");
        }
        @mkdir($requestPath, 0777, true);

        // Store request
        $storeCols = $this->askForArray("Enter columns to validate in store (comma separated)", []);
        $this->generateRequestFile("Store{$model}Request", $storeCols, $requestNamespace, $requestPath);

        // Update request
        $updateCols = $this->askForArray("Enter columns to validate in update (comma separated)", []);
        $this->generateRequestFile("Update{$model}Request", $updateCols, $requestNamespace, $requestPath);
    }

    protected function handleColumns()
    {
        $columnsArray = [];
        $this->info("Enter the columns of the displayed table one by one. Type 'done' as the key to finish.");

        while (true) {
            $key = $this->ask("➕ Column key");
            if (strtolower($key) === 'done' || $key === '') break;
            if (!$key) continue;

            $label = $this->ask("📝 Label for '{$key}'", str_replace('_', ' ', ucfirst($key)));
            $columnsArray[] = [
                'key' => trim($key),
                'label' => trim($label),
            ];
        }

        if (empty($columnsArray)) {
            $this->error("❌ No columns entered. Aborting.");
            return;
        }

        return collect($columnsArray)->map(fn($col) => "{ key: '{$col['key']}', label: '{$col['label']}' },")->implode("\n");
    }

    protected function handleCreateEditColumns($name = '')
    {
        $columns = [];

        $this->info("Enter your columns of the create and edit actions. Type 'done' or leave blank when finished.");

        while (true) {
            $key = $this->ask('Key (e.g., name)');
            if (!$key || strtolower($key) === 'done') break;

            $label = $this->ask('Label (e.g., Name)');
            $type = $this->ask('Type (text, number, textarea, select, select_with_search)');
            $required = $this->confirm('Is this field required?', true);
            $placeholder = $this->ask('enter the placeholder', '');

            $options = [];
            if (in_array($type, ['select', 'select_with_search'])) {
                $isRelation = $this->confirm('Does this select use a relation?', false);
                if ($isRelation) {
                    $relationName = $this->ask('Enter the relation name prop (e.g., users)');
                    $this->createEditRelations[] = $relationName;

                    $columns[] = [
                        'label' => trim($label),
                        'key' => trim($key),
                        'type' => trim($type),
                        'required' => trim($required),
                        'options' => [],
                        'relation' => $relationName,
                        'placeholder' => $placeholder
                    ];
                    continue;
                } else {
                    $opts = $this->ask('Enter options (value:label, comma separated)', '');
                    foreach (explode(',', $opts) as $opt) {
                        if (str_contains($opt, ':')) {
                            [$val, $lab] = array_map('trim', explode(':', $opt));
                            $options[] = ['value' => $val, 'label' => $lab];
                        }
                    }
                }
            }

            $columns[] = [
                'label' => trim($label),
                'key' => trim($key),
                'type' => trim($type),
                'required' => trim($required),
                'options' => $options ?? null,
                'relation' => $relationName ?? null,
                'placeholder' => $placeholder ?? null
            ];
        }

        return collect($columns)->map(function ($col) {
            $line = "{ key: '{$col['key']}', label: '{$col['label']}', type: '{$col['type']}', required: " . ($col['required'] ? 'true' : 'false');

            if (!empty($col['options'])) {
                $line .= ", options: " . json_encode($col['options']);
            }

            if (!empty($col['relation'])) {
                $line .= ", relation: props.{$col['relation']}";
            }

            $line .= " },";
            return $line;
        })->implode("\n");
    }

    protected function getStubContent(string $stubName, string $name, string $columns = ''): string
    {
        $stubPath = app_path("Console/stubs/{$stubName}");
        if (!file_exists($stubPath)) return "<template><div>Missing stub: {$stubName}</div></template>";

        $content = file_get_contents($stubPath);

        $href = $this->ask('enter the href', $name);

        if ($stubName === 'Create.vue.stub') {
            $propsCode = '';
            foreach ($this->createEditRelations as $relation) {
                $propsCode .= "    {$relation}: Array<{ id: any; name: any }>;\n";
            }
            $replacements = [
                '{{ slug }}' => $name,
                '{{ columns }}' => $columns,
                '{{ props }}' => $propsCode,
                '{{ href }}' => $href,
            ];
        } elseif ($stubName === 'Edit.vue.stub') {
            $propsCode = "data: Record<string, any>;";

            $replacements = [
                '{{ slug }}' => strtolower($name),
                '{{ columns }}' => $columns,
                '{{ props }}' => $propsCode,
                '{{ href }}' => $href,
            ];
        } elseif ($stubName === 'View.vue.stub') {
            $replacements = [
                '{{ slug }}' => strtolower($name),
                '{{ href }}' => $href,
            ];
        } else {
            $replacements = [
                '{{ Title }}' => str_replace('_', ' ', ucfirst($name)),
                '{{ slug }}' => $name,
                '{{ columns }}' => $columns,
            ];
        }

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    protected function createController(string $name, string $controllerPath, $requestNamespace): void
    {
        $columnsSearching = $this->askForArray('Enter columns to search (comma separated)', ['name']);
        $columnsSelection = $this->askForArray('Enter columns to select (comma separated)', []);
        $relationsInput = $this->ask('Enter relationships for the displayed table (format: relation_column like user_name, city_title)', '');

        $relations = array_filter(array_map('trim', explode(',', $relationsInput)));

        $Name = ucfirst($name); // Users
        $model = \Illuminate\Support\Str::singular($Name); // User
        $singularLowerName = strtolower($model); // user
        $controllerName = "{$Name}Controller";

        $namespace = str_replace('/', '\\', "App\\Http\\Controllers\\{$controllerPath}");
        $path = app_path("Http/Controllers/{$controllerPath}");
        @mkdir($path, 0777, true);

        $functionsRelations = $this->askForRelationsCreateEditShow();

        $createEditrelations = '';
        $createEditrelationsReturn = '';

        foreach ($functionsRelations as $relation) {
            $relationModel = trim($relation['model']);
            $relationColumns = $relation['columns'];
            $variable = \Illuminate\Support\Str::plural(strtolower($relationModel));

            $columnsArray = $relationColumns === null ? "'all'" : collect($relationColumns)->map(fn($col) => "'$col'")->join(', ');
            $columnsCode = $relationColumns === null ? "['all']" : "[$columnsArray]";

            $createEditrelations .= "\${$variable} = \$this->getRelation('{$relationModel}', {$columnsCode});\n        ";
            $createEditrelationsReturn .= "'{$variable}' => \${$variable},\n            ";
        }

        $filePath = "$path/{$controllerName}.php";
        $stubContent = file_get_contents(app_path("Console/stubs/Controller.stub"));

        $replacements = [
            '{{ namespace }}' => $namespace,
            '{{ requestNamespace }}' => $requestNamespace,
            '{{ model }}' => $model,
            '{{ controllerName }}' => $controllerName,
            '{{ folder }}' => $name,
            '{{ modelVariable }}' => $singularLowerName,
            '{{ pluralVariable }}' => $name,
            '{{ pluralModel }}' => ucfirst($name),
            '{{ columnsSearching }}' => '[' . implode(', ', array_map(fn($c) => "'$c'", $columnsSearching)) . ']',
            '{{ selectionCols }}' => '[' . implode(', ', array_map(fn($c) => "'$c'", $columnsSelection)) . ']',
            '{{ relations }}' => '[' . implode(', ', array_map(fn($r) => "'$r'", $relations)) . ']',
            '{{ createEditrelations }}' => rtrim($createEditrelations),
            '{{ createEditrelationsReturn }}' => rtrim($createEditrelationsReturn),
        ];

        $finalContent = str_replace(array_keys($replacements), array_values($replacements), $stubContent);
        file_put_contents($filePath, $finalContent);

        $this->info("✅ Created controller at: {$filePath}");
    }

    protected function askForArray(string $question, array $default = []): array
    {
        $answer = $this->ask($question);
        if (is_null($answer) || trim($answer) === '') return $default;
        return array_filter(array_map('trim', explode(',', $answer)));
    }

    protected function askForRelationsCreateEditShow(): array
    {
        $relations = [];

        while (true) {
            $this->comment("Create and Edit relation controller functions.");
            $relation = $this->ask("Enter a relation model (e.g., role), or leave empty/done to finish");

            if (empty($relation) || strtolower($relation) === 'done') {
                break;
            }

            $columns = $this->ask("Enter columns to select for {$relation} (comma separated, or 'all')", 'all');

            $relations[] = [
                'model' => $relation,
                'columns' => strtolower($columns) === 'all'
                    ? 'all'
                    : array_filter(array_map('trim', explode(',', $columns))),
            ];
        }

        return $relations;
    }

    protected function generateRequestFile(string $className, array $columns, string $namespace, string $path): void
    {
        $rules = collect($columns)->map(fn($col) => "'{$col}' => 'required',")->implode("\n            ");

        $stubPath = app_path("Console/stubs/Request.stub");
        $filePath = "$path/{$className}.php";
        $content = str_replace(
            ['{{ namespace }}', '{{ class }}', '{{ rules }}'],
            [$namespace, $className, $rules],
            file_get_contents($stubPath)
        );

        file_put_contents($filePath, $content);
        $this->info("✅ Created request class: {$filePath}");
    }
}
