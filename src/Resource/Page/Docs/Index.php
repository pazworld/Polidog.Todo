<?php
namespace Polidog\Todo\Resource\Page\Docs;

use BEAR\RepositoryModule\Annotation\Cacheable;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Inject\ResourceInject;

/**
 * @Cacheable(type="view", expiry="never")
 */
class Index extends ResourceObject
{
    use ResourceInject;

    public function onGet(string $rel = null) : ResourceObject
    {
        if ($rel === null) {
            return $this->index();
        }
        $links = $this->resource->options->uri('app://self/')()->body['_links'];
        $href = $links[$rel]['href'];
        $uri = 'app://self' . $href;
        $optionsJson = $this->resource->options->uri($uri)()->view;
        $this->body = [
            'doc' => json_decode($optionsJson, true),
            'rel' => $rel,
            'href' => $href
        ];

        return $this;
    }

    private function index()
    {
        $index = $this->resource->uri('app://self/index')()->body;
        $this->body = [
            'message' => $index['message'],
            'links' => $index['_links']
        ];

        return $this;
    }

}
