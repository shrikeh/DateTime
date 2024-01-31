<?php

declare(strict_types=1);

/*
 *
 * (c) Barney Hanlon <barney@shrikeh.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!function_exists('xdebug_set_filter')) {
    return;
}

xdebug_set_filter(
    XDEBUG_FILTER_CODE_COVERAGE,
    XDEBUG_PATH_INCLUDE,
    [dirname(__DIR__) . '/src'],
);
