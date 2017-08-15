<?php
namespace Polidog\Todo\Module;

use BEAR\Package\PackageModule;
use BEAR\Package\Provide\Router\AuraRouterModule;
use BEAR\Resource\Module\JsonSchemalModule;
use BEAR\Sunday\Module\Constant\NamedModule;
use josegonzalez\Dotenv\Loader as Dotenv;
use Koriym\Now\NowModule;
use Koriym\QueryLocator\QueryLocatorModule;
use Polidog\Todo\Form\TodoForm;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\Di\AbstractModule;
use Ray\WebFormModule\AuraInputModule;
use Ray\WebFormModule\FormInterface;

class AppModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $appDir = dirname(dirname(__DIR__));
        Dotenv::load([
            'filepath' => dirname(dirname(__DIR__)) . '/.env',
            'toEnv' => true
        ]);
        $this->install(new AuraRouterModule($appDir . '/var/conf/aura.route.php'));
        $this->install(new PackageModule);
        $this->install(new NowModule);
        $this->install(new QueryLocatorModule($appDir . '/var/sql'));
        $this->install(new NamedModule(require $appDir . '/var/conf/messages.php'));
        $this->install(new NamedModule(require $appDir . '/var/locale/en.php'));
        $this->install(new JsonSchemaModule($appDir . '/var/json_schema', $appDir . '/var/json_validate'));
        // Database
        $dbConfig = 'sqlite:' . $appDir . '/var/db/todo.sqlite3';
        $this->install(new AuraSqlModule($dbConfig));
        // Form
        $this->install(new AuraInputModule);
        $this->bind(TodoForm::class);
        $this->bind(FormInterface::class)->annotatedWith('todo_form')->to(TodoForm::class);
    }
}
