<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RSDataTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:r-s-data-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to make new table with his data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask("Enter the name of the folder (like users)");
        $path = $this->ask("Enter the path to this folder", 'dashboard/pages');

        if (!$path) {
            $path = 'dashboard/pages';
        }

        $basePath = resource_path("js/pages/{$path}/{$name}");
        $actionsPath = "{$basePath}/actions";

        // Create directories
        @mkdir($actionsPath, 0777, true);

        // Columns
        $columnsFormatted = $this->handleColumns();

        // File paths
        $mainFile = "{$basePath}/" . ucfirst($name) . ".vue";
        $createFile = "{$actionsPath}/Create.vue";
        $editFile = "{$actionsPath}/Edit.vue";
        $viewFile = "{$actionsPath}/View.vue";

        // Create files with stub content
        file_put_contents($mainFile, $this->getStubContent('Main.vue.stub', $name, $columnsFormatted));
        file_put_contents($createFile, $this->getStubContent('Create.vue.stub', $name));
        file_put_contents($editFile, $this->getStubContent('Edit.vue.stub', $name));
        file_put_contents($viewFile, $this->getStubContent('View.vue.stub', $name));

        $this->info("✅ Created Vue DataTable page for '{$name}' at 'resources/js/{$path}/{$name}'");

        // the controller maker
        $controllerPath = $this->ask("Enter controller path", 'Dashboard/Pages');
        $this->createController($name, $controllerPath);
    }

    /**
     * Ask and get the cols with keys and labels
     */
    protected function handleColumns()
    {
        $columnsArray = [];

        $this->info("Enter columns one by one. Type 'done' as the key to finish.");

        while (true) {
            $key = $this->ask("➕ Column key");

            if (strtolower($key) === 'done') {
                break;
            }

            if (!$key) {
                continue;
            }

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

        // Format columns for stub
        return collect($columnsArray)->map(fn($col) => "{ key: '{$col['key']}', label: '{$col['label']}' },")->implode("\n");
    }

    /**
     * Get the content from a stub file and replace placeholders.
     */
    protected function getStubContent(string $stubName, string $name, string $columns = ''): string
    {
        $stubPath = app_path("Console/stubs/{$stubName}");

        if (!file_exists($stubPath)) {
            return "<template><div>Missing stub: {$stubName}</div></template>";
        }

        $content = file_get_contents($stubPath);

        $replacements = [
            '{{ name }}' => ucfirst($name),
            '{{ Title }}' => str_replace('_', ' ', ucfirst($name)), // Human readable
            '{{ slug }}' => strtolower($name),
            '{{ columns }}' => $columns,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    /**
     * Create the controller.
     */
    protected function createController(string $name, string $controllerPath): void
    {
        $columnsSearching = $this->askForArray('Enter columns to search (comma separated)');
        $columnsSelection = $this->askForArray('Enter columns to select (comma separated)', ['*']);
        $relationsInput = $this->ask('Enter relationships (format: relation_column like user_name,city_title)', '');

        $relations = array_filter(array_map('trim', explode(',', $relationsInput)));

        $relationSelects = '';
        $relationReturns = '';

        foreach ($relations as $relation) {
            [$relationName, $relationColumn] = explode('_', $relation);
            $modelName = ucfirst(\Illuminate\Support\Str::singular($relationName));

            $relationSelects .= "        \${$relationName} = \\App\\Models\\{$modelName}::select(['id', '{$relationColumn}'])->get();\n";
            $relationReturns .= "            'data' => \${$relationName},\n";
        }

        $studlyName = ucfirst($name);
        // Use singular form for the model name
        $singularStudlyName = \Illuminate\Support\Str::singular($studlyName);
        $pluralStudlyName = \Illuminate\Support\Str::pluralStudly($singularStudlyName);
        $controllerName = "{$pluralStudlyName}Controller";

        $namespace = str_replace('/', '\\', "App\\Http\\Controllers\\{$controllerPath}");
        $path = app_path("Http/Controllers/{$controllerPath}");

        @mkdir($path, 0777, true);

        $filePath = "{$path}/{$controllerName}.php";

        $stubContent = file_get_contents(app_path("Console/stubs/Controller.stub"));

        $replacements = [
            '{{ namespace }}' => $namespace,
            '{{ model }}' => $singularStudlyName, // Use singular form for model
            '{{ controller }}' => $controllerName,
            '{{ folder }}' => strtolower($name),
            '{{ modelVariable }}' => strtolower($singularStudlyName),
            '{{ pluralVariable }}' => strtolower(\Illuminate\Support\Str::plural($name)),
            '{{ pluralModel }}' => $pluralStudlyName,
            '{{ columnsSearching }}' => '[' . implode(', ', array_map(fn($c) => "'$c'", $columnsSearching)) . ']',
            '{{ selectionCols }}' => '[' . implode(', ', array_map(fn($c) => "'$c'", $columnsSelection)) . ']',
            '{{ relations }}' => '[' . implode(', ', array_map(fn($r) => "'$r'", $relations)) . ']',
            '{{ relationsSelects }}' => rtrim($relationSelects),
            '{{ relationsReturn }}' => rtrim($relationReturns),
        ];

        $finalContent = str_replace(array_keys($replacements), array_values($replacements), $stubContent);

        file_put_contents($filePath, $finalContent);

        $this->info("✅ Created controller at: {$filePath}");
    }

    protected function askForArray(string $question, array $default = []): array
    {
        $answer = $this->ask($question);

        if (is_null($answer) || trim($answer) === '') {
            return $default;
        }

        return array_filter(array_map('trim', explode(',', $answer)));
    }
}
