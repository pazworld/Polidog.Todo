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
     * @Named("app_todo")
     */
    public function __construct(array $msg)
    {
        $this->msg = $msg;
    }

    /**
     * Return a todo
     *
     * @param string $id todo id
     *
     * @JsonSchema(schema="todo.json", params="todo.get.json")
     */
    public function onGet(string $id) : ResourceObject
    {
        $todo = $this->pdo->fetchOne($this->query['todo_select'], ['id' => $id]);
        if (empty($todo)) {
            $this->code = StatusCode::NOT_FOUND;

            return $this;
        }
        $todo['status_name'] = $todo['status'] == self::INCOMPLETE ? $this->msg[true] : $this->msg[false];
        $this['todo'] = $todo;

        return $this;
    }

    /**
     * Create a todo
     *
     * @param $title todo title
     *
     * @Assisted("now")
     * @ReturnCreatedResource
     */
    public function onPost(string $title, NowInterface $now = null) : ResourceObject
    {
        $value = [
            'title' => $title,
            'status' => self::INCOMPLETE,
            'created' => (string) $now,
            'updated' => (string) $now,
        ];
        $this->pdo->perform($this->query['todo_insert'], $value);
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
        $value = [
            'id' => $id,
            'status' => $status
        ];
        $this->pdo->perform($this->query['todo_update'], $value);
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
        $this->pdo->perform($this->query['todo_delete'], ['id' => $id]);
        $this->code = StatusCode::NO_CONTENT;

        return $this;
    }
}
