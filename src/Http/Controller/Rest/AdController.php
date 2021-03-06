<?php

namespace App\Http\Controller\Rest;

use App\Business\Sale\Create\AdCreateCommand;
use App\Business\Sale\Delete\AdDeleteCommand;
use App\Business\Sale\Edit\AdEditCommand;
use App\Http\Annotation\AdminAccess\AdminAccess;
use App\Http\Annotation\HttpCodeCreated\HttpCodeCreated;
use App\Http\Pagination\Page;
use App\Http\Pagination\Pagination;
use App\Http\Pagination\Paginator;
use App\Http\Response\EmptySuccess\EmptySuccessResponse;
use App\Http\Response\Item\AdResponse;
use App\Business\Sale\Ad;
use App\Business\Sale\AdRepository;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/rest/ads") */
class AdController
{
    /** @Route("/", methods={"GET"}) */
    public function getList(Pagination $pagination, Paginator $paginator): Page
    {
        $ads = array_map(
            function (Ad $ad): AdResponse {
                return AdResponse::fromEntity($ad);
            },
            $paginator->find(Ad::class, $pagination)
        );

        $totalAds = $paginator->getCount(Ad::class);

        return new Page($ads, $pagination, $totalAds);
    }

    /** @Route("/{id}", methods={"GET"}) */
    public function get(Ad $ad): AdResponse
    {
        return AdResponse::fromEntity($ad);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     * @AdminAccess()
     */
    public function put(AdEditCommand $command, MessageBusInterface $bus, AdRepository $repo): AdResponse
    {
        $bus->dispatch($command);

        $id = $command->getData()->getId();

        return AdResponse::fromEntity(
            $repo->get($id)
        );
    }

    /**
     * @Route("/", methods={"POST"})
     * @HttpCodeCreated()
     * @AdminAccess()
     */
    public function post(AdCreateCommand $command, MessageBusInterface $bus, AdRepository $repo): AdResponse
    {
        $bus->dispatch($command);

        $id = $command->getData()->getId();

        return AdResponse::fromEntity(
            $repo->get($id)
        );
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     * @AdminAccess()
     */
    public function delete(AdDeleteCommand $command, MessageBusInterface $bus): EmptySuccessResponse
    {
        $bus->dispatch($command);

        return new EmptySuccessResponse();
    }
}
