<?php
namespace Polidog\Todo\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\Package\PackageModule;
use BEAR\Package\Provide\Router\AuraRouterModule;
use BEAR\Resource\Module\JsonSchemaLinkHeaderModule;
use BEAR\Resource\Module\JsonSchemaModule;
use BEAR\Sunday\Module\Constant\NamedModule;
use josegonzalez\Dotenv\Loader as Dotenv;
use Koriym\Now\NowModule;
use Koriym\QueryLocator\QueryLocatorModule;
use Polidog\Todo\Form\TodoForm;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\Query\SqlQueryModule;
use Ray\WebFormModule\AuraInputModule;
use Ray\WebFormModule\FormInterface;

class AppModule extends AbstractAppModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $appDir = $this->appMeta->appDir;
        Dotenv::load([
            'filepath' => dirname(dirname(__DIR__)) . '/.env',
            'toEnv' => true
        ]);
        $this->install(new AuraRouterModule($appDir . '/var/conf/aura.route.php'));
        $this->install(new PackageModule);
        $this->install(new NowModule);
        $this->install(new QueryLocatorModule($appDir . '/var/sql'));
        $this->install(new NamedModule(require $appDir . '/var/locale/en.php'));
        $this->install(new JsonSchemaModule($appDir . '/var/json_schema', $appDir . '/var/json_validate'));
        $this->install(new JsonSchemaLinkHeaderModule('https://koriym.github.io/Polidog.Todo/'));
        // Database
        $dbConfig = 'sqlite:' . $appDir . '/var/db/todo.sqlite3';
        $this->install(new AuraSqlModule($dbConfig));
        // Form
        $this->install(new AuraInputModule);
        $this->bind(TodoForm::class);
        $this->bind(FormInterface::class)->annotatedWith('todo_form')->to(TodoForm::class);
        $this->install(new SqlQueryModule($appDir . '/var/sql'));
    }
}
