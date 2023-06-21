<?php declare(strict_types=1);

namespace App\Controller;


use App\Repository\Book;
use MongoDB\Client;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    private Book $bookRepository;
    public function __construct(Book $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function index()
    {
        //$books = $this->bookRepository->findByPattern("php");
        $books = $this->bookRepository->findAll();

        $response = new Response(json_encode($books));
        $response->headers->set("Content-Type", "application/json");
        $response->headers->set("Transfer-Encoding", "chunked");
        return $response;
    }

    public function import()
    {
        $data = file_get_contents(__DIR__ . '/../../data/books.json');
        $books = json_decode($data);

        if (!$this->bookRepository->import($books)) {
            return new Response("Cannot import data.");
        }

        return new Response('OK');
    }
}
