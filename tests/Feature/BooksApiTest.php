<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;
    function test_can_get_all_books()
    {
        $books = Book::factory(4)->create();

        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title,
            ])->assertJsonFragment([
                'title' => $books[1]->title,
            ]);
    }
    function test_can_get_a_book()
    {
        $book = Book::factory()->create();

        $this->getJson(route('books.show', $book))
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }
    function test_can_create_a_book()
    {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrors(['title']);

        $this->postJson(route('books.store'), [
            'title' => 'A new book',
        ])->assertJsonFragment([
            'title' => 'A new book',
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'A new book',
        ]);
    }
    function test_can_update_a_book()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrors(['title']);

        $this->patchJson(route('books.update', $book), [
            'title' => 'Book updated',
        ])->assertJsonFragment([
            'title' => 'Book updated',
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Book updated',
        ]);
    }
    function test_can_delete_a_book()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseMissing('books', [
            'title' => $book->title,
        ]);
    }
}
