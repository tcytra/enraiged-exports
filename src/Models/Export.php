<?php

namespace Enraiged\Exports\Models;

use Enraiged\Database\Track\Created;
use Enraiged\Files\Models\File;
use Enraiged\Files\Traits\Attachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Export extends Model
{
    use Attachable, Created;

    /** @var  string  The avatars storage directory. */
    protected $directory = 'exports';

    /** @var  string  The database table name. */
    protected $table = 'exports';

    /** @var  array  The attributes that aren't mass assignable. */
    protected $guarded = ['id', 'status'];

    /** @var  string  The morphable name. */
    protected $morphable = 'avatarable';

    /**
     *  Register the Export events.
     *
     *  @return void
     *  @static
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($export) {
            if (!$export->rows) {
                $export->rows = 0;
            }

            if (!$export->status) {
                $export->status = -1;
            }

            return $export;
        });

        self::deleting(function ($export) {
            if ($export->file->exists) {
                $export->file->delete();
            }

            return $export;
        });
    }

    /**
     *  @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'attachable')->withDefault();
    }

    /**
     *  Return the export storage directory name.
     *
     *  @return string
     */
    public function folder(): string
    {
        return config('enraiged.tables.storage') ?? 'exports';
    }

    /**
     *  Return the universal resource location for the attachable file.
     *
     *  @return string
     */
    public function url(): string
    {
        return route('files.download', ['file' => $this->file->id], config('enraiged.app.absolute_uris'));
    }
}
