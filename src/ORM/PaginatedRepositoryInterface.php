<?php

namespace DoctrinePagination\ORM;

use Doctrine\ORM\AbstractQuery;
use DoctrinePagination\Collection\PaginatedArrayCollection;
use Doctrine\Persistence\ObjectRepository;
use DoctrinePagination\DTO\Params;

interface PaginatedRepositoryInterface extends ObjectRepository
{
    public function findPageWithDTO(Params|null $params): PaginatedArrayCollection;

    public function findPageBy(
        int|null $page = 1,
        int|null $per_page = 20,
        array $criteria = [],
        array|null $orderBy = null,
        int|null $hydrateMode = AbstractQuery::HYDRATE_OBJECT
    ): PaginatedArrayCollection;

    public function countBy(array $criteria = []): int;
}
