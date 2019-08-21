<?php

declare(strict_types=1);

namespace Arcanedev\LaravelNotes\Tests\Models;

use Arcanedev\LaravelNotes\Models\Note;
use Arcanedev\LaravelNotes\Tests\Stubs\Factories\{PostFactory, UserFactory, UserWithAuthorIdFactory};
use Arcanedev\LaravelNotes\Tests\Stubs\Models\User;
use Arcanedev\LaravelNotes\Tests\TestCase;

/**
 * Class     NoteTest
 *
 * @author   David Palomares <david@mainsip.com>
 */
class NoteWithTitleTest extends TestCase
{

    public function it_can_create_a_note_with_title()
    {
        /** @var  Post  $post */
        $post = $this->factory->create(Post::class);

        static::assertNull($post->note);

        $note = $post->createNote($content = 'Hello world #1', null, true, $title = 'Title #1');

        static::assertInstanceOf(Note::class, $post->note);

        static::assertSame($note->id,      $post->note->id);
        static::assertSame($content,       $post->note->content);
        static::assertSame($note->content, $post->note->content);
        static::assertSame($note->title,   $post->note->title);

        static::assertNull($post->note->author);
    }

    public function it_can_create_with_author_and_title()
    {
        /**
         * @var  User  $user
         * @var  Post  $post
         */
        $user = $this->factory->create(User::class);
        $post = $this->factory->create(Post::class);

        $note = $post->createNote($content = 'Hello world #1', $user, true, $title = 'Title #1');

        static::assertSame($content,          $note->content);
        static::assertSame($content,          $post->note->content);

        static::assertSame($title,            $note->title);
        static::assertSame($title,            $post->note->title);

        static::assertInstanceOf(User::class, $note->author);
        static::assertInstanceOf(User::class, $post->note->author);

        static::assertEquals($user->id, $note->author->id);
        static::assertEquals($user->id, $post->note->author->id);
    }

    public function it_can_add_note_with_title()
    {
        /** @var  User  $user */
        $user = $this->factory->create(User::class);

        static::assertCount(0, $user->notes);

        $note = $user->createNote($content = 'Hello world #1', null, true, $title = 'Title #1');

        static::assertCount(1, $user->notes);
        static::assertNull($note->author);
    }

    /** @test */
    public function it_can_update_note_with_title(): void
    {
        $post = $this->createPost();

        static::assertNull($post->note);

        $note = $post->createNote($content = 'Hello world #1');

        static::assertInstanceOf(Note::class, $post->note);

        static::assertSame($note->id, $post->note->id);
        static::assertSame($content, $note->content);
        static::assertSame($note->content, $post->note->content);

        $post->updateNote($content = 'Hello world #2', null, true, $title = 'Title #2');

        static::assertInstanceOf(Note::class, $post->note);

        static::assertSame($note->id, $post->note->id);
        static::assertSame($content, $post->note->content);
        static::assertSame($title, $post->note->title);
        static::assertNotSame($note->content, $post->note->content);
        static::assertNotSame($note->title, $post->note->title);

        static::assertCount(1, Note::all());
    }

}
