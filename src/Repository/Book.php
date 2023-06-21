<?php
namespace App\Repository;

use MongoDB\Client;
use MongoDB\Collection;

class Book
{
    private Collection $collection;
    public function __construct(Client $client)
    {
        $this->collection = $client->selectCollection('local', 'books');
    }

    public function findByPattern($pattern): array
    {
        $books = $this->collection->find(
            [
                '$or' => [
                    ['title' => ['$regex' => $pattern, '$options' => 'i']],
                    ['longDescription' => ['$regex' => $pattern, '$options' => 'i']]
                ]
            ],
            [
                'limit' => 5,
                'sort' => ['pageCount' => -1],
            ]
        );

        return $books->toArray();
    }

    public function import(array $books): bool
    {
        $result = $this->collection->insertMany($books);

        if ($result->getInsertedCount() > 0) {
            return true;
        }

        return false;
    }

    public function findAll(): array
    {
        return $this->collection->find()->toArray();
    }
}
