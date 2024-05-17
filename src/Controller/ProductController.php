<?php

namespace App\Controller;

use App\Entity\Books;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BooksRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    #[Route('/library', name: 'library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig');
    }

    #[Route('/book/create', name: 'book_create_get', methods: ['GET'])]
    public function createProductGet(): Response
    {


        return $this->render('library/create.html.twig');

    }


    #[Route('/book/create', name: 'book_create_post', methods: ['POST'])]
    public function createProduct(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();
        $title = $request->request->get('title');
        $isbn = $request->request->get('isbn');
        $author = $request->request->get('author');
        $image = $request->request->get('image');
        $books = new Books();
        $books->setTitle($title);
        $books->setIsbn($isbn);
        $books->setAuthor($author);
        if ($image !== null) {
            $books->setImageUrl($image);
        }

        // tell Doctrine you want to (eventually) save the books
        // (no queries yet)
        $entityManager->persist($books);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->redirectToRoute('books_view_all');

    }

    #[Route('api/library/books', name: 'api/library/books')]
    public function showAllBooks(
        BooksRepository $bookRepository
    ): Response {
        $books = $bookRepository
            ->findAll();

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route('api/library/book/{isbn}', name: 'book_by_isbn')]
    public function showBookByIsbn(
        BooksRepository $bookRepository,
        ManagerRegistry $doctrine,
        string $isbn
    ): Response {

        // $book = $entityManager->getRepository(Books::class)->find($isbn);
        $book = $bookRepository->findByIsbn($isbn);

        

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;;


    }

    #[Route('/book/show/{id}', name: 'book_by_id')]
    public function showBookById(
        BooksRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository
            ->find($id);

        $data = [
            'book' => $book
        ];

        return $this->render('library/book.html.twig', $data);


    }

    #[Route('/book/delete/{id}', name: 'book_delete_by_id')]
    public function deleteBookById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Books::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('books_view_all');
    }

    #[Route('/book/edit/{id}', name: 'book_edit_by_id', methods:"GET")]
    public function editBookById(
        BooksRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository
            ->find($id);

        $data = [
            'book' => $book
        ];

        return $this->render('library/edit.html.twig', $data);


    }

    #[Route('/book/edit/{id}', name: 'book_edit', methods:"POST")]
    public function editBook(
        BooksRepository $bookRepository,
        ManagerRegistry $doctrine,
        Request $request,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Books::class)->find($id);
        $title = $request->request->get('title');
        $isbn = $request->request->get('isbn');
        $author = $request->request->get('author');
        $image = $request->request->get('image');
        $book->setTitle($title);
        $book->setIsbn($isbn);
        $book->setAuthor($author);
        if ($image !== null) {
            $book->setImageUrl($image);
        }
        $entityManager->flush();
        return $this->redirectToRoute('books_view_all');


    }
    #[Route('/book/update/{id}/{title}', name: 'book_update')]
    public function updateBook(
        ManagerRegistry $doctrine,
        int $id,
        string $title
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Books::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $book->setTitle($title);
        $entityManager->flush();

        return $this->redirectToRoute('books_show_all');
    }

    #[Route('/books/view', name: 'books_view_all')]
    public function viewAllBooks(
        BooksRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();

        $data = [
            'books' => $books
        ];

        return $this->render('library/view.html.twig', $data);
    }
}
