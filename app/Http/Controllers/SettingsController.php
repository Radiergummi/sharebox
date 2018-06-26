<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;
use function array_key_exists;
use function compileCss;
use function parseCss;
use function redirect;

class SettingsController extends Controller
{
    public function index()
    {
        try {
            $customStylesSource = Storage::disk('public')->get('custom.css');
            $customStyles       = parseCss($customStylesSource);

            if ( ! array_key_exists('body', $customStyles)) {
                $customStyles['body'] = [];
            }
        } catch (Throwable $exception) {
            $customStyles = [
                'body' => [
                    '--primary' => '#007bff'
                ]
            ];
        }

        return view('settings', [
            'primaryColor'        => $customStyles['body']['--primary'],
            'defaultPrimaryColor' => '#007bff'
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \RuntimeException
     */
    public function update(Request $request)
    {
        // validate the request - we only accept png, jpg or svg files for the logo. Colors
        // are validated using a special color validation rule that matches hex and rgb(a) colors.
        $this->validate($request, [
            'logo'          => 'nullable|file|mimetypes:image/png,image/jpg,image/jpeg,image/svg+xml',
            'primary_color' => 'nullable|color',
        ]);

        // retrieve the logo file from the request
        $logo = $request->file('logo');

        // if we have a logo, store it in the public folder as logo.<extension>
        if ($logo) {
            $logo->storeAs('public', 'logo.' . $logo->getClientOriginalExtension());
        }

        if ($request->has('primary_color')) {

            // check if we have a custom stylesheet already, and parse it if there is one
            try {
                $customStylesSource = Storage::disk('public')->get('custom.css');
                $customStyles       = parseCss($customStylesSource);

                // if there are no rules for body, create an empty entry for it now
                if ( ! array_key_exists('body', $customStyles)) {
                    $customStyles['body'] = [];
                }
            } catch (Throwable $exception) {

                // create a new, empty CSS stylesheet representation in lieu of the existing one.
                // it will be saved to disk later on.
                $customStyles = [
                    'body' => []
                ];
            }

            $primaryColor = $request->get('primary_color');

            // check if we're working with a hex or rgba color
            if ($primaryColor[0] === '#') {

                // set the primary color to the hex color
                $customStyles['body']['--primary']     = $primaryColor;

                // use a little hex magic to parse the hex color values into decimal numbers
                $customStyles['body']['--primary-rgb'] = implode(',',
                    array_map('hexdec', str_split(ltrim($primaryColor, '#'), 2))
                );
            } elseif (substr($primaryColor, 0, 4) === 'rgb') {

                // regular expression to match RGBA color channels
                $re  = '/rgb\((\d{1,3}), ?(\d{1,3}), ?(\d{1,3})\);?/i';

                // match the regex
                preg_match_all($re, $primaryColor, $matches, PREG_SET_ORDER, 0);

                // destructure the regex matches into the individual color channels
                // while skipping the first match (namely the full string)
                [, $r, $g, $b] = $matches[0];

                // use a little sprintf magic to convert the decimal color values into hex numbers
                $customStyles['body']['--primary']     = sprintf("#%02x%02x%02x", $r, $g, $b);
                $customStyles['body']['--primary-rgb'] = "$r,$g,$b";
            }  elseif (substr($primaryColor, 0, 4) === 'rgba') {

                // regular expression to match RGBA color channels
                $re  = '/rgba\((\d{1,3}), ?(\d{1,3}), ?(\d{1,3}), ?((?:0|1)(?:.\d*)?)\);?/i';

                // match the regex
                preg_match_all($re, $primaryColor, $matches, PREG_SET_ORDER, 0);

                // destructure the regex matches into the individual color channels
                // while skipping the first match (namely the full string)
                [, $r, $g, $b, $a] = $matches[0];

                // use a little sprintf magic to convert the decimal color values into hex numbers
                $customStyles['body']['--primary']     = sprintf("#%02x%02x%02x", $r, $g, $b);
                $customStyles['body']['--primary-rgb'] = "$r,$g,$b";
            } else {
                throw new RuntimeException('Unsupported color format: Use either hex or RGBA.');
            }

            // save the re-compiled custom styles CSS file to the public directory
            Storage::disk('public')->put('custom.css', compileCss($customStyles));
        }

        return redirect()->route('settings.index', []);
    }
}
