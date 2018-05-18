<?php
namespace Polidog\Todo\Resource\App;

use BEAR\Package\Annotation\ReturnCreatedResource;
use BEAR\Resource\Annotation\JsonSchema;
use BEAR\Resource\ResourceObject;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use Koriym\Now\NowInterface;
use Koriym\QueryLocator\QueryLocatorInject;
use Ray\AuraSqlModule\AuraSqlInject;
use Ray\Di\Di\Assisted;
use Ray\Di\Di\Named;
use Ray\Query\QueryInterface;
use Ray\Query\RowInterface;

class Todo extends ResourceObject
{
    use AuraSqlInject;
    use QueryLocatorInject;

    const INCOMPLETE = 1;
    const COMPLETE = 2;

    /**
     * complete message
     *
     * true: complete
     * false: incomplete
     *
     * @var array
     */
    private $msg = [];

    /**
     * @var callable
     */
    private $getTodo;

    /**
     * @var callable
     */
    private $createTodo;

    /**
     * @var callable
     */
    private $updateTodo;

    /**
     * @var callable
     */
    private $deleteTodo;

    /**
     * @Named("msg=app_todo,getTodo=todo_select,createTodo=todo_insert,updateTodo=todo_update,,deleteTodo=todo_delete")
     */
    public function __construct(
        array $msg,
        RowInterface $getTodo,
        callable $createTodo,
        callable $updateTodo,
        callable $deleteTodo
    ) {
        $this->msg = $msg;
        $this->getTodo = $getTodo;
        $this->createTodo = $createTodo;
        $this->updateTodo = $updateTodo;
        $this->deleteTodo = $deleteTodo;
    }

    /**
     * Return a todo
     *
     * @param string $id todo id
     *
     * @JsonSchema(key="todo", schema="todo.json", params="todo.get.json")
     */
    public function onGet(string $id) : ResourceObject
    {
        $todo = ($this->getTodo)(['id' => $id]);
        if (empty($todo)) {
            $this->code = StatusCode::NOT_FOUND;

            return $this;
        }
        $todo['status_name'] = $todo['status'] == self::INCOMPLETE ? $this->msg[true] : $this->msg[false];
        $this->body['todo'] = $todo;

        return $this;
    }

    /**
     * Create a todo
     *
     * @param string       $title todo title
     * @param NowInterface $now   current time
     *
     * @Assisted({"now"})
     * @Named("createTodo=todo_insert")
     * @ReturnCreatedResource
     */
    public function onPost(string $title, NowInterface $now = null) : ResourceObject
    {
        ($this->createTodo)([
            'title' => $title,
            'status' => self::INCOMPLETE,
            'created' => (string) $now,
            'updated' => (string) $now,
        ]);
        $id = $this->pdo->lastInsertId();
        $this->code = StatusCode::CREATED;
        $this->headers[ResponseHeader::LOCATION] = "/todo?id={$id}";

        return $this;
    }

    /**
     * Set Todo status
     *
     * @param string $id     Todo id
     * @param int    $status Todo status
     */
    public function onPut(string $id, int $status) : ResourceObject
    {
        ($this->updateTodo)([
            'id' => $id,
            'status' => $status
        ]);
        $this->code = StatusCode::NO_CONTENT;

        return $this;
    }

    /**
     * Delete todo
     *
     * @param string $id todo id
     */
    public function onDelete(string $id) : ResourceObject
    {
        ($this->deleteTodo)(['id' => $id]);
        $this->code = StatusCode::NO_CONTENT;

        return $this;
    }
}
