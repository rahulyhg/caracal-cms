<?php

namespace App\Gallery\Managing;

use App\Command\CreateCommand;

class GalleryCreateCommand implements CreateCommand
{
    public function __construct(
        string $id,
        string $name = null,
        string $description = null,
        array $photoIds = null
    ) {
        $this->data = new GalleryData($id, $name, $description, $photoIds);
    }

    public function getData(): GalleryData
    {
        return $this->data;
    }

    /** @var GalleryData */
    private $data;
}
