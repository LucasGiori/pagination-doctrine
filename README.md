
[![Minimum PHP Version](https://img.shields.io/badge/php-%5E8.0-blue)](https://php.net/)
[![Latest Stable Version](https://poser.pugx.org/kadudutra/pagination-doctrine/v/stable.svg)](https://packagist.org/packages/lucasgiori/pagination-doctrine)
[![Latest Unstable Version](https://poser.pugx.org/kadudutra/pagination-doctrine/v/unstable.svg)](https://packagist.org/packages/lucasgiori/pagination-doctrine)
[![License](https://poser.pugx.org/lucasgiori/pagination-doctrine/license.svg)](https://packagist.org/packages/lucasgiori/pagination-doctrine)
[![Total Downloads](https://poser.pugx.org/lucasgiori/pagination-doctrine/downloads)](https://packagist.org/packages/lucasgiori/pagination-doctrine)

This library provides a paginated repository and collection for Doctrine.

# Installation

## Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
$ composer require lucasgiori/doctrine-pagination
```

# Configure Repository

## Use it as Entity repository

Configure PaginatedRepository in your entity:

```php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="DoctrinePagination\ORM\PaginatedRepository")
 */
class Task
{

}
```

## Create your custom Paginated repository

Create custom repository extending PaginatedRepository:

```php
namespace Repository;

use DoctrinePagination\ORM\PaginatedQueryBuilder;
use DoctrinePagination\ORM\PaginatedRepository;

/**
 * Class TaskRepository
 */
class TaskRepository extends PaginatedRepository
{

}
```

Configure your Entity:

```php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="Repository\TaskRepository")
 */
class Task
{

}
```

If needed, override processCriteria method in your custom repository to add some custom actions:

```php
protected function processCriteria(PaginatedQueryBuilder $qb, array $criteria)
{
    foreach ($criteria as $field => $value) {
        switch ($field) {
            case 'description':
                $qb->andWhere(...);
                unset($criteria[$field]);
                break;
        }
    }

    parent::processCriteria($qb, $criteria);
}
```

Parameter DTO to facilitate data searches:

```php
use Doctrine\ORM\Query;
use DoctrinePagination\DTO\Params;

$params = (new Params())
                ->setCriteria(["field" => "teste"]) // Array the fields and values to apply filter in sql
                ->setPage(1) // Page of query data
                ->setPerPage(10) // Quantity per page
                ->setHydrateMode(Query::HYDRATE_ARRAY) //Result handling mode
                ->setSearchField("nome") // Search Field define field to apply `like` of sql
                ->setSearch("gazin"); // Field Value  apply `like` in sql



```

Class that performs the search of data consuming DTO and return PaginatedArrayCollention;

```php
use DoctrinePagination\ORM\PaginatedRepository;
use DoctrinePagination\DTO\Params;

class Example extends PaginatedRepository {


    public function findWithFilter(Params $params): ?PaginatedArrayCollection
    {
        return $this->findPageWithDTO($params);
    }
}
```

# Using Paginated Repository

*public* **findPageBy** *($page, $per_page, array $criteria = [], array $orderBy = null)*

Returns a paginated collection of elements that matches criteria.

*public* **countBy** *(array $criteria = [])*

Returns the total number of elements that matches criteria.

*protected* **createPaginatedQueryBuilder** *(array $criteria = [], $indexBy = null)*

This method is used by findPageBy and countBy methods to create a QueryBuilder, and can be used in
 other repository custom methods.

**processCriteria (protected)**

This method is called from createPaginatedQueryBuilder to add criteria conditions.

This can be overridden to customize those criteria conditions.

**findBy and findAll**

PaginatedRepository overrides findBy and findAll default Doctrine Repository methods to provides
 code compatibility.

# Using Paginated Collections

The PaginatedRepository always returns a PaginatedArrayCollection:

```php
// some parameters
$page = 5;
$per_page = 10;

// get repository
$repository = $doctrine->getRepository('Task');

/** @var PaginatedArrayCollection */
$result = $repository->findPageBy($page, $per_page, ['field'=>'value']);
```

**count()**

```php
// count obtained results as usual
$pageResults = $result->count(); // 10
```

**getTotal()**

```php
// get total results
$totalResults = $result->getTotal(); // 95
```

**getPage()**

```php
// current page
$currentPage = $result->getPage(); // 5
```

**getResultsPerPage()**

```php
// current results per page
$currentResultsPerPage = $result->getResultsPerPage(); // 10
```

**getPages()**

```php
// get total pages
$totalPages = $result->getPages(); // 10
```

**getNextPage()**

```php
// get next page number
$nextPage = $result->getNextPage(); // 6
```

**getPrevPage()**

```php
// get prev page number
$prevPage = $result->getPrevPage(); // 4
```

```php 
repository_forked = "https://github.com/javihgil/doctrine-pagination"
```