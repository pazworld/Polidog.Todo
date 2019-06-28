<?php
namespace Polidog\Todo\Resource\App;

use BEAR\Package\AppInjector;
use BEAR\Resource\ResourceInterface;
use BEAR\Resource\ResourceObject;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use PHPUnit\Framework\TestCase;

class TodoTest extends TestCase
{
    /**
     * @var \BEAR\Resource\ResourceInterface
     */
    private $resource;

    protected function setUp() : void
    {
        $this->resource = (new AppInjector('Polidog\Todo', 'test-app'))->getInstance(ResourceInterface::class);
    }

    public function testOnPost()
    {
        $ro = $this->resource->post('app://self/todo', ['title' => 'test']);
        $this->assertSame(StatusCode::CREATED, $ro->code);

        return $ro;
    }

    /**
     * @depends testOnPost
     */
    public function testOnGet(ResourceObject $ro)
    {
        $location = $ro->headers[ResponseHeader::LOCATION];
        $ro = $this->resource->get('app://self' . $location);
        $this->assertSame(StatusCode::OK, $ro->code);
    }

    public function testOnGet404()
    {
        $ro = $this->resource->get('app://self/todo?id=0');
        $this->assertSame(StatusCode::NOT_FOUND, $ro->code);
    }

    /**
     * @depends testOnPost
     */
    public function testOnPut(ResourceObject $createdRo)
    {
        $location = $createdRo->headers[ResponseHeader::LOCATION];
        $ro = $this->resource->put('app://self' . $location, ['status' => Todo::COMPLETE]);
        /* @var $ro ResourceObject */
        $this->assertSame(StatusCode::NO_CONTENT, $ro->code);
        $get = $this->resource->get('app://self' . $location);
        /* @var $get ResourceObject */
        $status = $get->body['todo']['status'];
        $this->assertSame(Todo::COMPLETE, (int) $status);
    }

    /**
     * @depends testOnPost
     */
    public function testDelete(ResourceObject $ro)
    {
        $location = $ro->headers[ResponseHeader::LOCATION];
        $ro = $this->resource->delete('app://self' . $location);
        $this->assertSame(StatusCode::NO_CONTENT, $ro->code);
        $ro = $this->resource->get('app://self' . $location);
        $this->assertSame(StatusCode::NOT_FOUND, $ro->code);
    }
}
