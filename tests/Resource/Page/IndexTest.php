<?php
namespace Polidog\Todo\Resource\Page;

use BEAR\Package\AppInjector;
use BEAR\Resource\ResourceInterface;
use BEAR\Resource\ResourceObject;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
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
        $ro = $this->resource->get('page://self/index');
        $this->assertSame(StatusCode::OK, $ro->code);
        $todos = $ro->body['todos'];
        /* @var \BEAR\Resource\AbstractRequest $todos */
        $requestString = $todos->toUri();
        $this->assertSame('app://self/todos', $requestString);

        return $ro;
    }

    public function testOnPost()
    {
        $ro = $this->resource->post('page://self/index', ['title' => 'test']);
        $this->assertSame(StatusCode::MOVED_PERMANENTLY, $ro->code);
        $this->assertSame('/', $ro->headers[ResponseHeader::LOCATION]);
    }

    public function testOnPost400()
    {
        $ro = $this->resource->post('page://self/index', ['title' => '']);
        $this->assertSame(StatusCode::BAD_REQUEST, $ro->code);
    }

    /**
     * @depends testOnGet
     */
    public function testView(ResourceObject $ro)
    {
        $html = (string) $ro;
        $this->assertStringStartsWith('<!DOCTYPE html>', $html);
        $this->assertStringEndsWith(('</html>'), $html);
    }
}
