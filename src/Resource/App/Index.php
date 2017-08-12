<?php
namespace Polidog\Todo\Resource\App;

use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    public $body = [
        'curies' => [
            'href' => '/docs/{?rel}',
            'name' => 'pt',
            'templated' => true
        ],
        'message' => 'Welcome to the Polidog.Todo API ! Our hope is to be as self-documenting and RESTful as possible.',
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
