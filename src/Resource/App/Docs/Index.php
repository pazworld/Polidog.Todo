<?php
namespace Polidog\Todo\Resource\App\Docs;

use BEAR\RepositoryModule\Annotation\Cacheable;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Inject\ResourceInject;

/**
 * @Cacheable(type="view")
 */
class Index extends ResourceObject
{
    use ResourceInject;

    public $headers = ['content-type' => 'application/json'];

    public function onGet(string $rel) : ResourceObject
    {
        $this->view = $this->resource->options->uri("app://self/{$rel}")()->view;

        return $this;
    }
}
