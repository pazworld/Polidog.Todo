<?php
namespace Polidog\Todo\Resource\App;

use BEAR\Resource\Annotation\JsonSchema;
use BEAR\Resource\ResourceObject;
use Koriym\QueryLocator\QueryLocatorInject;
use Ray\AuraSqlModule\AuraSqlInject;
use Ray\Di\Di\Named;

class Todos extends ResourceObject
{
    use AuraSqlInject;
    use QueryLocatorInject;

    /**
     * @var string[]
     */
    public $text;

    /**
     * @Named("text=page_index")
     */
    public function __construct(array $text)
    {
        $this->text = $text;
    }

    /**
     * Return a list of todos
     *
     * @param int $status todo status
     *
     * @JsonSchema(schema="todos.json")
     */
    public function onGet(int $status = null) : ResourceObject
    {
        $this->body = $status === null ?
            $this->pdo->fetchAll($this->query['todos_list'])
            : $this->pdo->fetchAll($this->query['todos_item'], ['status' => $status]);

        return $this;
    }
}
