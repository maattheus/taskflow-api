<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeFullCrud extends Command
{
    protected $signature = 'make:full {name}';
    protected $description = 'Create Model, Controller, Service and Repository + Interface';

    public function handle()
    {
        $name = $this->argument('name');
        $camel = lcfirst($name); // Ex: Board -> board

        /**
         * Criar Model
         */
        $this->call('make:model', ['name' => $name]);

        /**
         * Criar Controller com injeção do Service
         */
        $controllerPath = app_path("Http/Controllers/Api/v1/{$name}Controller.php");
        if (!file_exists($controllerPath)) {
            if (!is_dir(dirname($controllerPath))) {
                mkdir(dirname($controllerPath), 0755, true);
            }

            file_put_contents(
                $controllerPath,
                "<?php

namespace App\Http\Controllers\Api\\v1;

use App\Http\Controllers\Controller;
use App\Services\\{$name}Service;
use Illuminate\Http\Request;

class {$name}Controller extends Controller
{
    protected \${$camel}Service;

    public function __construct({$name}Service \${$camel}Service)
    {
        \$this->{$camel}Service = \${$camel}Service;
    }

}
"
            );

            $this->info("Controller created: {$controllerPath}");
        }

        /**
         * Criar Service com injeção do RepositoryInterface
         */
        $servicePath = app_path("Services/{$name}Service.php");
        if (!file_exists($servicePath)) {
            if (!is_dir(dirname($servicePath))) {
                mkdir(dirname($servicePath), 0755, true);
            }

            file_put_contents(
                $servicePath,
                "<?php

namespace App\Services;

use App\Repositories\\{$name}\\{$name}Repository;

class {$name}Service
{
    protected \${$camel}Repository;

    public function __construct({$name}Repository \${$camel}Repository)
    {
        \$this->{$camel}Repository = \${$camel}Repository;
    }

}
"
            );

            $this->info("Service created: {$servicePath}");
        }

        /**
         * Criar diretório do Repository
         */
        $repositoryDir = app_path("Repositories/{$name}");
        if (!is_dir($repositoryDir)) {
            mkdir($repositoryDir, 0755, true);
        }

        /**
         * Criar Interface do Repository
         */
        $interfacePath = "{$repositoryDir}/{$name}Interface.php";
        if (!file_exists($interfacePath)) {
            file_put_contents(
                $interfacePath,
                "<?php

namespace App\Repositories\\{$name};

interface {$name}Interface
{
    
}
"
            );

            $this->info("Interface created: {$interfacePath}");
        }

        /**
         * Criar Repository que implementa a Interface
         */
        $repositoryPath = "{$repositoryDir}/{$name}Repository.php";
        if (!file_exists($repositoryPath)) {
            file_put_contents(
                $repositoryPath,
                "<?php

namespace App\Repositories\\{$name};

class {$name}Repository implements {$name}Interface
{

}
"
            );

            $this->info("Repository created: {$repositoryPath}");
        }

        $this->info("✅ Full structure for {$name} created successfully!");
    }
}
