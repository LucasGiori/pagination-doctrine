<?php

declare(strict_types=1);

namespace DoctrinePagination\DTO;

use Doctrine\ORM\AbstractQuery;

class Params
{
    private int|null $page = 1;

    private int|null $per_page = 20;

    private array|null $criteria = [];

    private string|null $sort = 'ASC';

    private string|null $order = '';

    private string|null $search = '';

    private string|null $search_field = '';

    private int|null $hydrateMode = AbstractQuery::HYDRATE_OBJECT;

    public function __construct(array|null $dados = [])
    {
        if (empty($dados))
            return;

        foreach ($dados as $key => $dado) {
            $key = trim($key);
            $dado = trim($dado);

            if (!isset($this->$key) || $dado === "undefined") {
                continue;
            }

            $this->$key = $this->treatData($key, $dado);
        }
    }

    public function getPage(): int|null
    {
        return $this->page;
    }

    public function setPage(int|null $page): Params
    {
        $this->page = $page;
        return $this;
    }

    public function getPerPage(): int|null
    {
        return $this->per_page;
    }

    public function setPerPage(int|null $per_page): Params
    {
        $this->per_page = $per_page;
        return $this;
    }

    public function getCriteria(): array|null
    {
        if (empty($this->getSearch()) || empty($this->getSearchField())) {
            return $this->criteria;
        }

        return array_merge($this->criteria, [
            $this->getSearchField() => ["ILIKE", $this->getSearch()]
        ]);
    }

    public function setCriteria(array|null $criteria): Params
    {
        $this->criteria = $criteria;
        return $this;
    }

    public function getSort(): string|null
    {
        return $this->sort;
    }

    public function setSort(string|null $sort): Params
    {
        $this->sort = $sort;
        return $this;
    }

    public function getOrder(): string|null
    {
        return $this->order;
    }

    public function setOrder(string|null $order): Params
    {
        $this->order = $order;
        return $this;
    }

    public function getOrderBy(): array|null|string
    {
        if ($this->getSort() && $this->getOrder()) {
            return [$this->getOrder() => $this->getSort()];
        }

        return [];
    }

    public function getSearch(): string|null
    {
        return $this->search;
    }

    public function setSearch(string|null $search): Params
    {
        $this->search = $search;
        return $this;
    }

    public function getSearchField(): string|null
    {
        return $this->search_field;
    }

    public function setSearchField(string|null $search_field): Params
    {
        $this->search_field = $search_field;
        return $this;
    }

    public function getHydrateMode(): int|null
    {
        return $this->hydrateMode;
    }

    public function setHydrateMode(int|null $hydrateMode): Params
    {
        $this->hydrateMode = $hydrateMode;
        return $this;
    }

    private function treatData($key, $data): mixed
    {
        $typeData = gettype($this->$key);

        switch ($typeData) {
            case "integer":
            case "string":
                $method = sprintf("%sval", substr($typeData, 0, 3));

                return call_user_func($method, $data);
            case "array":
                return is_array($data) ? $data : (array)$data;
            default:
                return $data;
        }
    }
}
