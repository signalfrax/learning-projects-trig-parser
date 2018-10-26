<?php

/**
 * @param string $path
 * @param string $carry
 * @return string
 *
 * @see https://tools.ietf.org/html/rfc3986#section-5.2.4
 */
function remove_dots_from_path(string $path, string $carry = ""): string
{

    if (empty($path)) {
        return $carry;
    }

    if (preg_match('/^\.+$/', $path)) {
        return "";
    }

    // Find a '/' if not then return the iri back.
    $cursor = strpos($path, '/');
    if (false === $cursor) {
        return $carry . $path;
    }

    // We have an ending '/' so append it to carry and return.
    if (0 === $cursor && 1 === strlen($path)) {
        return $carry . $path;
    }

    // We have a slash at the start of the iri so find the next one so we can extract a segment.
    $cursor = (0 === $cursor) ? strpos($path, '/', $cursor + 1) : $cursor;

    // Cursor may be false so we use the entire iri.
    $segment = (false !== $cursor) ? substr($path, 0, $cursor + 1) : $path;

    if ('../' == $segment || './' == $segment) {

        // Check whether we need to substr from path or send an empty string for path
        return remove_dots_from_path(
            (strlen($segment) < strlen($path)) ? substr($path, $cursor) : "",
            $carry
        );

    } else if ('/./' == $segment || '/.' == $segment) {

        // Check whether we need to substr from path or send an empty string for path. We also prepend '/' to the path.
        return remove_dots_from_path(
            (strlen($segment) < strlen($path)) ? substr($path, $cursor) : "/" ,
            $carry
        );

    } else if ('/../' == $segment || '/..' == $segment) {

        // Check whether we need to substr from path or send an empty string for path.
        // We also prepend '/' to the path.
        // We also check if we need to remove the last segment from carry.
        return remove_dots_from_path(
            (strlen($segment) < strlen($path)) ? substr($path, $cursor) : "/",
            (false !== strrpos($carry, '/')) ? substr($carry, 0, strrpos($carry, '/')) : $carry
        );

    } else {

        // Check whether we need to substr from path or send an empty string.
        // For carry we check whether we need to exclude the ending '/' or just append to entire segment.
        return remove_dots_from_path(
            (strlen($segment) < strlen($path)) ? substr($path, $cursor) : "",
            (0 < $cursor && strlen($segment) < strlen($path)) ? $carry . substr($segment, 0, -1) : $carry . $segment
        );

    }

}

/**
 * @param string $base
 * @param string $relative
 * @return string
 *
 * @see https://tools.ietf.org/html/rfc3986#section-5.2.3
 */
function merge_iri_path(string $base, string $relative): string {

    // If base is empty our does not have '/' then just return the relative uri
    if (empty($base) || (false === strpos($base, '/')) && !empty($relative)) {

        return ( 0 < strpos($relative, '/') || false === strpos($relative, '/')) ? "/$relative" : $relative;

    } else if (empty($relative)) {

        return $base;

    } else if (1 < strlen($base)) {

        if (0 < strrpos($base, '/')) {
            return substr($base, 0, strrpos($base, '/')) . "/$relative";
        } else {
            return $relative;
        }

    } else {

        if (0 === strpos($relative, '/')) {

            return $relative;

        } else {

            return "/$relative";

        }

    }

}

/**
 * @param array $components. These are mostly the same parameters as parse_url except for user, password and port
 * e.g $components = [
 * 'scheme' => 'http',
 * 'host' => 'bogus',
 * 'path' => '/somepath'
 * 'query' => 'q=1&a=b',
 * 'fragment' => 'somefragment'
 * ]
 * @return string
 *
 * @see https://tools.ietf.org/html/rfc3986#section-5.3
 */
function compose_iri_path(array $components): string {

    $result = "";
    if (isset($components['scheme'])) {
        $result = sprintf("%s:", $components['scheme']);
    }
    if (isset($components['host'])) {
        $result = sprintf("%s//%s", $result, $components['host']);
    }

    if ('file' === $components['scheme']) {
        $result = sprintf("%s//", $result);
    }

    $result = (isset($components['path'])) ? $result . $components['path'] : $result;
    if (isset($components['query'])) {
        $result = sprintf("%s?%s", $result, $components['query']);
    }
    if (isset($components['fragment'])) {
        $result = sprintf("%s#%s", $result, $components['fragment']);
    }

    return $result;
}

/**
 * @param array $base_iri
 * @param string $relative_iri
 * @return string
 *
 * @see https://tools.ietf.org/html/rfc3986#section-5.2.1
 */
function resolve_relative_iri(array $base_iri, string $relative_iri): string {

    $r = parse_url($relative_iri);

    // Don't bother processing the rest if scheme is set.
    if (isset($r['scheme'])) {
        return $relative_iri;
    }

    if (!isset($r['path'])) $r['path'] = '';
    if (!isset($base_iri['path'])) $base_iri['path'] = '';
    if (!isset($base_iri['host'])) $base_iri['host'] = null;
    if (!isset($base_iri['query'])) $base_iri['query'] = null;

    $t = [];
    if (isset($r['scheme'])) {

        $t['scheme'] = $r['scheme'];
        if (isset($r['host'])) $t['host'] = $r['host'];
        $t['path'] = remove_dots_from_path($r['path']);
        if (isset($r['query'])) $t['query'] = $r['query'];

    } else {

        if (isset($r['host'])) {

            $t['host'] = $r['host'];
            $t['path'] = remove_dots_from_path($r['path']);
            if (isset($r['query'])) $t['query'] = $r['query'];

        } else {

            if (empty($r['path'])) {

                $t['path'] = $base_iri['path'];
                if (isset($r['query'])) {
                    $t['query'] = $r['query'];
                } else {
                    $t['query'] = $base_iri['query'];
                }

            } else {

                if (0 === strpos($r['path'], '/')) {
                    $t['path'] = remove_dots_from_path($r['path']);
                } else {
                    $t['path'] = remove_dots_from_path(merge_iri_path($base_iri['path'], $r['path']));
                }
                if (isset($r['query'])) $t['query'] = $r['query'];

            }

            $t['host'] = $base_iri['host'];

        }

        $t['scheme'] = $base_iri['scheme'];

    }

    if (isset($r['fragment'])) $t['fragment'] = $r['fragment'];

    return compose_iri_path($t);

}
