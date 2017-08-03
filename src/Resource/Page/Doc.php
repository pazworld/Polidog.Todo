<?php
namespace Polidog\Todo\Resource\Page;

use BEAR\RepositoryModule\Annotation\Cacheable;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Inject\ResourceInject;

/**
 * @Cacheable(type="view", expiry="never")
 */
class Doc extends ResourceObject
{
    use ResourceInject;

    public function onGet(string $rel = null) : ResourceObject
    {
        $uri = "app://self/{$rel}";
        $optionsJson = $this->resource->options->uri($uri)->eager->request()->view;
        $this->body = json_decode($optionsJson);

        return $this;
    }
}
