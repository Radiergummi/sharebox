<?php

/**
 * Map a CSS string to an associative array.
 *
 * @param string $css CSS string.
 *
 * @return array $styles An array of rules.
 */
function parseCss(string $css): array
{

    $styles = [];

    // Match rule sets.
    preg_match_all('/[^{]+\s*\{\s*[^}]+\s*}/', $css, $matches);

    foreach ($matches[0] as $set) {

        // Match selector.
        preg_match('/(?P<selector>[.#a-z0-9-]+)\s*\{/', $set, $matches);

        $selector          = $matches['selector'];
        $styles[$selector] = [];

        // Match rules.
        preg_match_all('/[a-z0-9-]+:\s*[^;]+;/', $set, $matches);

        foreach ($matches[0] as $rule) {

            // Match property.
            preg_match('/(?P<property>[a-z0-9-]+)\s*:/', $rule, $matches);

            $prop = $matches['property'];

            // Match value.
            preg_match('/:\s*(?P<value>[^;]+)\s*;/', $rule, $matches);

            $val = $matches['value'];

            // Add to array.
            $styles[$selector][$prop] = $val;
        }
    }

    return $styles;
}

/**
 * Dump an associative array to a CSS string.
 *
 * @param array $styles An array of rules.
 * @param int   $breaks Number of empty lines between rule sets.
 * @param int   $indent Number of spaces to indent rules.
 *
 * @return string $css CSS string.
 */
function compileCss(array $styles, int $breaks = 1, int $indent = 2): string
{
    $css = '';
    $len = count($styles);
    $itr = 0;

    foreach ($styles as $selector => $rules) {

        // selector {
        $css .= $selector . " {\n";

        // property: value;
        foreach ($rules as $prop => $val) {
            $css .= str_repeat(' ', $indent) . $prop . ': ' . $val . ";\n";
        }

        $css .= '}';

        // If not last selector, add line break(s).
        if ($itr != $len - 1) {
            $css .= str_repeat("\n", $breaks + 1);
        }

        $itr++;
    }

    return $css;
}
