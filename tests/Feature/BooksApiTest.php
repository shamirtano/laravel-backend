<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    function can_get_all_books()
    {      
        $books = Book::factory(4)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ])->assertJsonFragment([
            'title' => $books[1]->title
        ]);
    }

    /** @test */
    function can_get_one_book()
    {      
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    /** @test */
    function can_create_book()
    {   
        $book = Book::factory()->make();

        $response = $this->postJson(route('books.store'), $book->toArray());

        $response->assertCreated();

        $this->assertDatabaseHas('books', [
            'title' => $book->title
        ]);
    }

    /** @test */
    function can_update_book()
    {   
        $book = Book::factory()->create();

        $response = $this->putJson(route('books.update', $book), [
            'title' => 'New Title'
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('books', [
            'title' => 'New Title'
        ]);
    }

    /** @test */ // <--- This is the test that fails
    function can_delete_book()
    {   
        $book = Book::factory()->create();

        $response = $this->deleteJson(route('books.destroy', $book));

        $response->assertNoContent();

        $this->assertDatabaseMissing('books', [
            'title' => $book->title
        ]);
    }
}
