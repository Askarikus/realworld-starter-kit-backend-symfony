<?php

declare(strict_types=1);

namespace App\Helpers\Parser;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

trait PrepareParseExceptionTrait
{
    private static function prepareParseException(?string $message = null): BadRequestException
    {
        try {
            // return new BadRequestException(
            // 'Failed ' . var_export(debug_backtrace()[1]['function'], true) . ':' .
            // var_export(debug_backtrace()[1]['args'][0], true) . ' at ' .
            // var_export(debug_backtrace()[1]['file'], true) . ':' .
            // var_export(debug_backtrace()[1]['line'], true) . ' ' . $message);
            return new BadRequestException($message);
        } catch (\Exception) {
            return new BadRequestException('Error on cast types: '.var_export(debug_backtrace(), true));
        }
    }
}
