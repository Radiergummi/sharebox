<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

/**
 * Ticket model
 * Represents a download ticket. Tickets are issued on visiting a download landing page and stay valid
 * for a specified amount of time.
 *
 * @property string        $token
 * @property int           $download_id
 * @property boolean       $redeemed
 * @property \DateTime     $created_at
 * @property \App\Download $download
 * @method static \Illuminate\Database\Query\Builder where(string $column, $value)
 * @package App
 */
class Ticket extends Model
{
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'download_id',
        'redeemed'
    ];

    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [
        'token'
    ];

    public static function boot()
    {
        parent::boot();

        // auto-generate UUID on creation
        self::creating(function (Ticket $ticket) {
            $ticket->token = Uuid::generate(4)->string;
        });
    }

    /**
     * Shortcut method to create a new ticket and associate it with this download
     *
     * @param \App\Download $download
     *
     * @return \App\Ticket
     */
    public static function for(Download $download)
    {
        $ticket = new Ticket();

        $ticket->download()->associate($download);

        return $ticket;
    }

    /**
     * Retrieves the download this ticket belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function download()
    {
        return $this->belongsTo(Download::class);
    }
}
