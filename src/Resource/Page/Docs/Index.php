<?php
namespace Polidog\Todo\Resource\Page\Docs;

use BEAR\RepositoryModule\Annotation\Cacheable;
use BEAR\Resource\Exception\HrefNotFoundException;
use BEAR\Resource\Exception\ResourceNotFoundException;
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
        $index = $this->resource->options->uri('app://self/')()->body;
        $rel = sprintf('%s:%s', $index['curies']['name'], $rel);
        $links = $index['_links'];
        if (! isset($links[$rel]['href'])) {
            throw new ResourceNotFoundException($rel);
        }
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
        $name = $index['curies']['name'];
        $links = [];
        foreach ($index['_links'] as $rel => $value) {
            $newRel = str_replace($name . ':' , '', $rel);
            $links[$newRel] = $value;
        }
        $this->body = [
            'name' => $name,
            'message' => $index['message'],
            'links' => $links
        ];

        return $this;
    }

}
