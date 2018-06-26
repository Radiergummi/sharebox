<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Throwable;

/**
 * Template class
 *
 * @property int     $id
 * @property boolean $locked
 * @property string  $slot
 * @property string  $name
 * @property string  $language
 * @property string  $description
 * @property string  $head
 * @property string  $body
 * @property string  $footer
 * @property array   $downloads
 * @method static Template find(int $id)
 * @method static \Illuminate\Database\Query\Builder where(string $column, $value)
 * @method static int count
 * @package App
 */
class Template extends Model
{
    public const DOWNLOAD_EXPIRED_SLOT = 'expired';

    public const DOWNLOAD_LANDING_SLOT = 'landing';

    public const DOWNLOAD_MISSING_SLOT = 'missing';

    protected $fillable = [
        'name',
        'head',
        'body',
        'footer'
    ];

    protected $guarded  = [
        'locked'
    ];

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function allGroupedByLanguage()
    {
        $templates = Template::all();

        return collect($templates)
            ->map(function (Template $template) {
                return $template->language;
            })
            ->unique()
            ->map(function ($language) use ($templates) {
                return [
                    'language'  => $language,
                    'templates' => $templates->filter(function (Template $template) use ($language) {
                        return $template->language === $language;
                    })
                ];
            });
    }

    /**
     * @return \App\Template|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object
     */
    public static function downloadLandingTemplate()
    {
        return static::where('slot', static::DOWNLOAD_LANDING_SLOT)->first();
    }

    /**
     * @return \App\Template|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object
     */
    public static function downloadMissingTemplate()
    {
        return static::where('slot', static::DOWNLOAD_MISSING_SLOT)->first();
    }

    /**
     * @return \App\Template|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object
     */
    public static function downloadExpiredTemplate()
    {
        return static::where('slot', static::DOWNLOAD_EXPIRED_SLOT)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    /**
     * @param array $args
     *
     * @return string
     * @throws \Throwable
     */
    public function renderBody(array $args = [])
    {
        return $this->renderTemplate($this->body, $args);
    }

    /**
     * Renders a template string
     *
     * @param string $template
     * @param array  $args
     *
     * @return string
     * @throws \Throwable
     */
    protected function renderTemplate(string $template, array $args = [])
    {
        $generated = Blade::compileString($template);

        ob_start() and extract($args, EXTR_SKIP);

        // We'll include the view contents for parsing within a catcher
        // so we can avoid any WSOD errors. If an exception occurs we
        // will throw it out to the exception handler.
        try {
            eval('?>' . $generated);
        }

            // If we caught an exception, we'll silently flush the output
            // buffer so that no partially rendered views get thrown out
            // to the client and confuse the user with junk.
        catch (Throwable $exception) {
            ob_get_clean();
            throw $exception;
        }

        $content = ob_get_clean();

        return $content;
    }

    /**
     * @param array $args
     *
     * @return string
     * @throws \Throwable
     */
    public function renderHead(array $args = [])
    {
        return $this->renderTemplate($this->head, $args);
    }

    /**
     * @param array $args
     *
     * @return string
     * @throws \Throwable
     */
    public function renderFooter(array $args = [])
    {
        return $this->renderTemplate($this->footer, $args);
    }
}
