<?php

namespace App\Http\Pagination;
use Symfony\Component\HttpFoundation\Response;
use Kamyshev\ResponderBundle\Responder\ResponderInterface;
use Kamyshev\ResponderBundle\Responder\ResultMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class PageResponder implements ResponderInterface
{
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /** @psalm-suppress MissingParamType */
    public function supports($page, ResultMetadata $meta): bool
    {
        return Page::class === $meta->getType();
    }

    /** @psalm-suppress MissingParamType */
    public function make($page, ResultMetadata $meta): Response
    {
        /** @var Page $page */

        $data = $this->serializer->serialize([
            'items' => $page->getItems(),
            'page' => $page->getPagination()->getPage(),
            'perPgae' => $page->getPagination()->getPerPage(),
            'totalItems' => $page->getTotalCount(),
        ], 'json');

        return new JsonResponse($data);
    }
}
