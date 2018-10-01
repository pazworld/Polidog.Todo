<?php
namespace Polidog\Todo\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\Package\PackageModule;
use BEAR\Package\Provide\Router\AuraRouterModule;
use BEAR\Resource\Module\JsonSchemaLinkHeaderModule;
use BEAR\Resource\Module\JsonSchemaModule;
use BEAR\Sunday\Module\Constant\NamedModule;
use Koriym\QueryLocator\QueryLocatorModule;
use Polidog\Todo\Form\TodoForm;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\IdentityValueModule\IdentityValueModule;
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
        require_once $appDir . '/env.php';
        // constants
        $this->install(new IdentityValueModule());
        $this->install(new NamedModule(require $appDir . '/var/locale/en.php'));
        // router
        $this->install(new AuraRouterModule($appDir . '/var/conf/aura.route.php'));
        // json schema
        $this->install(new JsonSchemaModule($appDir . '/var/json_schema', $appDir . '/var/json_validate'));
        $this->install(new JsonSchemaLinkHeaderModule('https://koriym.github.io/Polidog.Todo/'));
        // database
        $dbConfig = 'sqlite:' . $appDir . '/var/db/todo.sqlite3';
        $this->install(new AuraSqlModule($dbConfig));
        $this->install(new SqlQueryModule($appDir . '/var/sql'));
        // form
        $this->install(new AuraInputModule);
        $this->bind(TodoForm::class);
        $this->bind(FormInterface::class)->annotatedWith('todo_form')->to(TodoForm::class);
        // base
        $this->install(new PackageModule);
    }
}
