<?php
namespace Polidog\Todo\Resource\Page\Docs;

use BEAR\RepositoryModule\Annotation\Cacheable;
use BEAR\Resource\ResourceObject;
use Ray\Di\Di\Named;

/**
 * @Cacheable(type="view", expiry="never")
 */
class Schema extends ResourceObject
{
    private $schemaDir;

    /**
     * @Named("schemaDir=json_schema_dir")
     */
    public function __construct(string $schemaDir = '')
    {
        $this->schemaDir = $schemaDir;
    }

    public function onGet(string $id) : ResourceObject
    {
        $path = realpath($this->schemaDir . '/' . $id);
        if (strpos($path, $this->schemaDir) == false) {
            //            throw new \DomainException($id);
        }
        $schema = (array) json_decode(file_get_contents($path), true);
        $this->body['schema'] = $schema;

        return $this;
    }
}
