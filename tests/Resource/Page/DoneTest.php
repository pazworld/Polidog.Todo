<?php
namespace Polidog\Todo\Resource\Page;

use BEAR\Package\AppInjector;
use BEAR\Resource\ResourceInterface;
use BEAR\Resource\ResourceObject;
use Koriym\HttpConstants\StatusCode;
use PHPUnit\Framework\TestCase;

class DoneTest extends TestCase
{
    /**
     * @var \BEAR\Resource\ResourceInterface
     */
    private $resource;

    protected function setUp()
    {
        $this->resource = (new AppInjector('Polidog\Todo', 'test-html-app'))->getInstance(ResourceInterface::class);
    }

    public function testOnGet()
    {
        $ro = $this->resource->get('page://self/done', ['id' => $this->getId()]);
        $this->assertSame(StatusCode::PERMANENT_REDIRECT, $ro->code);
    }

    public function getId()
    {
        $this->resource->post('app://self/todo', ['title' => 'test']);
        $body = $this->resource->get('app://self/todos')->body;
        $id = $body[0]['id'];

        return $id;
    }
}
