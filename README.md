#  Book API
A simple API with CRUD functionality for a book resource

##  How to setup  
1.  Clone this repo
2.  cd into the directory
3.  Run `composer install`
4.  Run `php artisan key:generate`
5.  Run the table migrations `php artisan migrate`
6.  Run the database seeder `php artisan db:seed` 
7.  Update `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` variables in .env file
8.  Run the tests `php artisan test`

##  Endpoints
|Verb          	|Path               |Description                  |
|---------------|-------------------|-----------------------------|
|POST			|`api/v1/books`   	|creates a book from the json object|
|GET         	|`api/v1/books`		|returns a list of books      |
|GET         	|`api/v1/books/:id`	|returns the book with :id |
|PATCH         	|`api/v1/books/:id`	|updates the book with :id |
|DELETE         |`api/v1/books/:id`	|deletes the book with :id |
|GET            |`api/external-books?name=:nameOfABook`	|returns the book with the name :nameOfABook from the [Ice And Fire API](https://anapioficeandfire.com/Documentation#books)|
