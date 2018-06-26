<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Download class
 *
 * @property int           $id
 * @property string        $uuid
 * @property string        $path
 * @property int           $size
 * @property string        $name
 * @property \DateTime     $expires
 * @property string        $password
 * @property int           $download_count
 * @property \App\Template $template
 * @property \App\Ticket[] $tickets
 * @method static Download find(int $id)
 * @method static \Illuminate\Database\Query\Builder where(string $column, $value)
 * @method static int count
 * @package App
 */
class Download extends Model
{
    protected $fillable = [
        'path',
        'name',
        'expires',
        'password',
        'download_count'
    ];

    protected $guarded  = [
        'uuid',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Retrieves all tickets issued for this download
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('tickets');
    }

    /**
     * @return string
     */
    public function readableSize()
    {
        if ( ! $this->size) {
            return '0B';
        }

        $i = floor(log($this->size, 1024));

        return round($this->size / pow(1024, $i), [0, 0, 2, 2, 3][$i]) . ['B', 'KB', 'MB', 'GB', 'TB'][$i];
    }

    public function increaseCount()
    {
        $this->download_count = $this->download_count + 1;

        $this->save();
    }
}
