<?php

namespace DoctrinePagination\ORM;

use DoctrinePagination\Collection\PaginatedArrayCollection;

trait PaginatedRepositoryFindByTrait
{
    public function findByPaginate(
        array|null $criteria = [],
        array|null $orderBy = null,
        int|null $limit = null,
        int|null $offset = null
    ): PaginatedArrayCollection
    {
        if ($offset !== null && $limit !== null && $limit > 0) {
            $page = ceil($offset / $limit) + 1;
        } else {
            $page = 1;
        }

        return $this->findPageBy($page, $limit !== null && $limit > 0 ? $limit : -1, $criteria, $orderBy);
    }
}
