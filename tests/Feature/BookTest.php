<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Book;

class BookTest extends TestCase
{

    /**
     * Test to get a book from IceAndFire API
     *
     * @return void
     */
    public function testGetBookFromIceandfireApiSuccessfully()
    {
        $this->get('api/external-books?name=The Hedge Knight')
            ->assertStatus(200)
            ->assertJson([
                'status_code' => 200,
                'status' => "success",
                'data' => [
                    [
                        "name" => "The Hedge Knight",
                        "isbn" => "978-0976401100",
                        "authors" =>["George R. R. Martin"],
                        "numberOfPages" => 164,
                        "publisher" => "Dabel Brothers Publishing",
                        "country" => "United States",
                        "release_date" => "2005-03-09"
                    ]
                ]
            ]);
    }

     /**
     * Test to get a non-existing book from IceAndFire API
     *
     * @return void
     */
    public function testGetNonExistingBookFromIceandfireApiSuccessfully()
    {
        $this->get('api/external-books?name=This is a missing book $$')
            ->assertStatus(200)
            ->assertJson([
                'status_code' => 200,
                'status' => "success",
                'data' => []
            ]);
    }

    /**
     * Test to create a book.
     *
     * @return void
     */
    public function testBookCreatedSuccessfully()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $bookData = [
            "name" => "Pirates of the Caribbean",
            "isbn" => "123-1234567890",
            "authors" => ["Earl Jones", "Stacy Gordon"],
            "country" => "Pakistan",
            "number_of_pages" => 512,
            "publisher" => "A long Story Inc",
            "release_date" => "2020-09-01"
        ];

        $this->json('POST', 'api/v1/books', $bookData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                "status_code" => 201,
                "status" => "success",
                "data" => [
                    "name" => "Pirates of the Caribbean",
                    "isbn" => "123-1234567890",
                    "authors" => ["Earl Jones", "Stacy Gordon"],
                    "country" => "Pakistan",
                    "number_of_pages" => 512,
                    "publisher" => "A long Story Inc",
                    "release_date" => "2020-09-01"
                ]
            ]);
    }

    /**
     * Test to get a book by id
     *
     * @return void
     */
    public function testGetABookByIdSuccessfully()
    {
        $book = factory(Book::class)->create([
            "name" => "Pirates of the Caribbean",
            "isbn" => "123-1234567890",
            "authors" => serialize(["Earl Jones", "Stacy Gordon"]),
            "country" => "Pakistan",
            "number_of_pages" => 512,
            "publisher" => "A long Story Inc",
            "release_date" => "2020-09-01"
        ]);

        $this->json('GET', 'api/v1/books/'.$book->id, [], ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonFragment([
                'status_code' => 200,
                'status' => "success",
            ])
            ->assertSee(
                str_replace(["{", "}"], "", json_encode([
                    "name" => "Pirates of the Caribbean",
                    "isbn" => "123-1234567890",
                    "authors" => ["Earl Jones", "Stacy Gordon"],
                    "country" => "Pakistan",
                    "number_of_pages" => "512",
                    "publisher" => "A long Story Inc",
                    "release_date" => "2020-09-01"
                ])),
                $escaped=false
            );
    }

    /**
     * Test to get books when database is seeded automatically
     * and manually
     *
     * @return void
     */
    public function testGetBooksSuccessfully()
    {
        factory(Book::class)->create([
            "name" => "Pirates of the Caribbean",
            "isbn" => "123-1234567890",
            "authors" => serialize(["Earl Jones", "Stacy Gordon"]),
            "country" => "Pakistan",
            "number_of_pages" => 512,
            "publisher" => "A long Story Inc",
            "release_date" => "2020-09-01"
        ]);

        factory(Book::class)->create([
            "name" => "Pirates of the Caribbean 2",
            "isbn" => "808-1234567890",
            "authors" => serialize(["Earl Jones", "Stacy Gordon"]),
            "country" => "Pakistan",
            "number_of_pages" => 514,
            "publisher" => "A long Story Inc",
            "release_date" => "2020-10-01"
        ]);

        $this->json('GET', 'api/v1/books', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonFragment([
                'status_code' => 200,
                'status' => "success",
            ])
            ->assertSee(
                str_replace(["{", "}"], "", json_encode([
                    "name" => "Pirates of the Caribbean 2",
                    "isbn" => "808-1234567890",
                    "authors" => ["Earl Jones", "Stacy Gordon"],
                    "country" => "Pakistan",
                    "number_of_pages" => "514",
                    "publisher" => "A long Story Inc",
                    "release_date" => "2020-10-01"
                ])),
                $escaped=false
            )
            ->assertSee(
                str_replace(["{", "}"], "", json_encode([
                    "name" => "Pirates of the Caribbean",
                    "isbn" => "123-1234567890",
                    "authors" => ["Earl Jones", "Stacy Gordon"],
                    "country" => "Pakistan",
                    "number_of_pages" => "512",
                    "publisher" => "A long Story Inc",
                    "release_date" => "2020-09-01"
                ])),
                $escaped=false
            );
    }

    /**
     * Test to get books when database is empty
     *
     * @return void
     */
    public function testGetBooksEmptyResponseSuccessfully()
    {
        $this->json('GET', 'api/v1/books', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'status_code' => 200,
                'status' => "success",
                'data' => []
            ]);
    }

    /**
     * Test to update a book
     *
     * @return void
     */
    public function testBookUpdatedSuccessfully()
    {
        $book = factory(Book::class)->create([
            "name" => "Pirates of the Caribbean",
            "isbn" => "123-1234567890",
            "authors" => serialize(["Earl Jones", "Stacy Gordon"]),
            "country" => "Pakistan",
            "number_of_pages" => 512,
            "publisher" => "A long Story Inc",
            "release_date" => "2020-09-01"
        ]);

        $payload = [
            "name" => "Pirates of the Caribbean II",
            "isbn" => "800-1234567890",
            "authors" => ["Earl Jones", "Stacy Gordon"],
            "country" => "Pakistan",
            "number_of_pages" => 518,
            "publisher" => "A long Story Inc",
            "release_date" => "2020-10-01"
        ];

        $this->json('PATCH', 'api/v1/books/'.$book->id, $payload, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "status_code" => 200,
                "status" => "success",
                "message" => "The book Pirates of the Caribbean II was updated successfully",
                "data" => [
                    "name" => "Pirates of the Caribbean II",
                    "isbn" => "800-1234567890",
                    "authors" => ["Earl Jones", "Stacy Gordon"],
                    "country" => "Pakistan",
                    "number_of_pages" => 518,
                    "publisher" => "A long Story Inc",
                    "release_date" => "2020-10-01"
                ]
            ]);
    }

    /**
     * Test to delete a book
     *
     * @return void
     */
    public function testBookDeletedSuccessfully()
    {
        $book = factory(Book::class)->create([
            "name" => "Pirates of the Caribbean",
            "isbn" => "123-1234567890",
            "authors" => serialize(["Earl Jones", "Stacy Gordon"]),
            "country" => "Pakistan",
            "number_of_pages" => 512,
            "publisher" => "A long Story Inc",
            "release_date" => "2020-09-01"
        ]);

        $this->json('DELETE', 'api/v1/books/'.$book->id, [], ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "status_code" => 204,
                "status" => "success",
                "message" => "The book ".$book->name." was deleted successfully",
                "data" => []
            ]);
    }
}
