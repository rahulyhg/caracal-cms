<?php

namespace App\Gallery\Managing\Photo;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Gallery\GalleryRepository;
use App\Gallery\PhotoRepository;

class PhotoEditHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var GalleryRepository */
    private $galleryRepo;
    /** @var PhotoRepository */
    private $photoRepo;

    public function __construct(EntityManagerInterface $em, GalleryRepository $galleryRepo, PhotoRepository $photoRepo)
    {
        $this->em = $em;

        $this->galleryRepo = $galleryRepo;
        $this->photoRepo = $photoRepo;
    }

    public function __invoke(PhotoEditCommand $command): void
    {
        $id = $command->getData()->getId();
        $photo = $this->photoRepo->get($id);

        $command->getData()->updatePhoto($photo, $this->galleryRepo);

        $this->em->flush();
    }
}
