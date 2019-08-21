<?php namespace Arcanedev\LaravelNotes\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait     HasOneNote
 *
 * @package  Arcanedev\LaravelNotes\Traits
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  \Arcanedev\LaravelNotes\Models\Note  note
 */
trait HasOneNote
{
    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    /**
     * Relation to ONE note.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function note()
    {
        return $this->morphOne(config('notes.notes.model'), 'noteable');
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a note.
     *
     * @param  string                                    $content
     * @param  \Illuminate\Database\Eloquent\Model|null  $author
     * @param  bool                                      $reload
     * @param  string|null                               $title
     *
     * @return \Arcanedev\LaravelNotes\Models\Note
     */
    public function createNote($content, $author = null, $reload = true, $title = null)
    {
        if ($this->note)
            $this->note->delete();

        /** @var \Arcanedev\LaravelNotes\Models\Note $note */
        $note = $this->note()->create(
            $this->prepareNoteAttributes($content, $author, $title)
        );

        if ($reload)
            $this->load(['note']);

        return $note;
    }

    /**
     * Update a note.
     *
     * @param  string                                    $content
     * @param  \Illuminate\Database\Eloquent\Model|null  $author
     * @param  bool                                      $reload
     * @param  string|null                               $title
     *
     * @return bool
     */
    public function updateNote($content, Model $author = null, $reload = true, $title = null)
    {
        $updated = $this->note->update(
            $this->prepareNoteAttributes($content, $author, $title)
        );

        if ($reload) $this->load(['note']);

        return $updated;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Prepare note attributes.
     *
     * @param  string                                    $content
     * @param  \Illuminate\Database\Eloquent\Model|null  $author
     * @param  string|null                               $title
     *
     * @return array
     */
    protected function prepareNoteAttributes($content, Model $author = null, $title)
    {
        return [
            'author_id' => is_null($author) ? $this->getCurrentAuthorId() : $author->getKey(),
            'content'   => $content,
            'title'     => $title,
        ];
    }

    /**
     * Get the current author's id.
     *
     * @return int|null
     */
    protected function getCurrentAuthorId()
    {
        return null;
    }
}
