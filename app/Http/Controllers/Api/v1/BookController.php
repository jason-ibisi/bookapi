<?php

namespace App\Http\Controllers\Api\v1;

use App\Book;
use App\Http\Resources\v1\BookResource;
use App\Http\Resources\v1\BookResourceCollection;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookController extends Controller
{

    /**
     * Show all books
     *
     * @return BookResourceCollection
     */
    public function index(): BookResourceCollection
    {
        return new BookResourceCollection(Book::paginate());
    }

    /**
     * Show a Book
     *
     * @param Book $book
     * @return BookResource
     */
    public function show(Book $book): BookResource
    {
        return new BookResource($book);
    }

    /**
     * Create a book
     *
     * @param Request $request
     * @return BookResource
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'isbn' => 'required|max:14',
            'authors' => 'required|array|max:255',
            'country' => 'required|max:255',
            'number_of_pages' => 'required|max:65535',
            'publisher' => 'required|max:255',
            'release_date' => 'required|date_format:Y-m-d'
        ]);

        $data = $request->all();

        $data['authors'] = serialize($request->authors);

        $book = Book::create($data);

        return new BookResource($book);
    }

    /**
     * Update a book
     *
     * @param Request $request
     * @param Book $book
     * @return bookResource
     */
    public function update(Request $request, Book $book): BookResource
    {
        $request->validate([
            'name' => 'max:255',
            'isbn' => 'max:14',
            'authors' => 'array|max:255',
            'country' => 'max:255',
            'number_of_pages' => 'max:65535',
            'publisher' => 'max:255',
            'release_date' => 'date_format:Y-m-d'
        ]);

        $data = $request->all();

        $data['authors'] = serialize($request->authors);

        $book->update($request->all());

        return new BookResource($book);
    }

    /**
     * Delete a book
     *
     * @param Book $book
     * @return Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'status_code' => 204,
            'status' => 'success',
            'message' => 'The book '.$book->name.' was deleted successfully',
            'data' => []
        ]);
    }
}
