<?php

namespace DoctrinePagination\Collection;

use JsonSerializable;
use LogicException;

class PaginatedArrayCollection implements JsonSerializable
{
    protected int|null $total;

    protected int|null $last_page;

    protected int|null $per_page;

    protected int|null $current_page;

    protected string|null $next_page_url;

    protected string|null $prev_page_url;

    protected array|null $criteria = [];

    protected array|null $orderBy = [];

    protected array|null $data = null;

    public function __construct(
        array $elements = [],
        int $current_page = null,
        int $per_page = 10,
        int $total = null,
        array|null $criteria = [],
        array|null $orderBy = []
    )
    {
        $this->data = $elements;

        $this->total = $total;
        $this->per_page = $per_page;
        $this->current_page = $current_page;
        $this->criteria = $criteria;
        $this->orderBy = $orderBy;

        $this->last_page = $this->getLastPage();
        $this->next_page_url = $this->getNextPageUrl();
        $this->prev_page_url = $this->getPrevPageUrl();

        $this->criteria = null;
        $this->orderBy = null;
    }

    public function getTotal(): int|null
    {
        return $this->total;
    }

    public function getLastPage(): int|null
    {
        if (!$this->getPerPage()) {
            throw new LogicException('ResultsPerPage was not setted');
        }

        if (!$this->getTotal()) {
            return 0;
        }

        $this->last_page = ceil($this->getTotal() / $this->getPerPage());

        return $this->last_page;
    }

    public function getPerPage(): int|null
    {
        return $this->per_page;
    }

    public function getCurrentPage(): int|null
    {
        return $this->current_page;
    }

    public function getNextPageUrl(): string|null
    {
        $this->next_page_url = $this->mountUrl($this->getCurrentPage() + 1);

        return $this->next_page_url;
    }

    public function getPrevPageUrl(): string|null
    {
        $this->prev_page_url = $this->mountUrl($this->getCurrentPage() - 1);

        return $this->prev_page_url;
    }

    public function getCriteria(): array|null
    {
        return $this->criteria;
    }

    public function setCriteria(array|null $criteria): PaginatedArrayCollection
    {
        $this->criteria = $criteria;
        return $this;
    }

    public function getOrderBy(): array|null
    {
        return $this->orderBy;
    }

    public function setOrderBy(array|null $orderBy): PaginatedArrayCollection
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    private function mountUrl(int $page): string
    {
        $order = '';
        $criteria = '';

        if ($page < 1) {
            $page = 1;
        }

        if ($page > $this->getTotal()) {
            $page = $this->getTotal();
        }

        if (!empty($this->criteria)) {
            foreach ($this->criteria as $key => $data) {
                if (!is_array($data)) {
                    $param = sprintf("&%s=%s", $key, $data);
                } else {
                    $param = sprintf("&search=%s&search_field=%s", $data[1] ?? $data, $key);
                }

                $criteria .= $param;
            }
        }

        if (!empty($this->orderBy)) {
            foreach ($this->orderBy as $key => $data) {
                $order .= sprintf("&sort=%s&order=%s", $key, $data);
            }
        }

        return sprintf("?page=%s&limit=%s%s%s", $page, $this->getPerPage(), $order, $criteria);
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
