<?php
namespace Polidog\Todo\Resource\App;

use BEAR\Package\Annotation\Curies;
use BEAR\Resource\ResourceObject;

/**
 * @Curies(name="pt", href="/docs/{?rels}", template=true)
 */
class Index extends ResourceObject
{
    public $body = [
        '_links' => [
            'pt:todo' => ['href' => '/todo'],
            'pt:todos' => ['href' => '/todos']
        ]
    ];

    public function onGet()
    {
        return $this;
    }
}
