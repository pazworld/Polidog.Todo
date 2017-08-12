<?php
namespace Polidog\Todo\Resource\Page;

use BEAR\RepositoryModule\Annotation\Cacheable;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Inject\ResourceInject;

/**
 * @Cacheable(type="view", expiry="never")
 */
class Docs extends ResourceObject
{
    use ResourceInject;

    public function onGet(string $rel = null) : ResourceObject
    {
        $links = $this->resource->options->uri('app://self/')()->body['_links'];
        $href = $links[$rel]['href'];
        $uri = 'app://self'. $href;
        $optionsJson = $this->resource->options->uri($uri)()->view;
        $this->body = [
            'doc' => json_decode($optionsJson, true),
            'rel' => $rel,
            'href' => $href
        ];


        return $this;
    }
}
