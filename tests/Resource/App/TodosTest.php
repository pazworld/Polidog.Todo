<?php
namespace Polidog\Todo\Resource\App;

use BEAR\Package\AppInjector;
use BEAR\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;

class TodosTest extends TestCase
{
    /**
     * @var \BEAR\Resource\ResourceInterface
     */
    private $resource;

    protected function setUp()
    {
        $this->resource = (new AppInjector('Polidog\Todo', 'test-app'))->getInstance(ResourceInterface::class);
    }

    public function testOnPost()
    {
        $ro = $this->resource->get('app://self/todos', ['status' => TODO::COMPLETE]);
        $this->assertSame(200, $ro->code);

        return $ro;
    }
}
