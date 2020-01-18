<?php

namespace DKoehn\DSpec\ChangedFiles;

class Path
{
    // TODO: Win32 support

    public static function isAbsolute(string $path): bool
    {
        $path = trim($path);

        return strpos($path, '/') === 0;
    }

    public static function resolve(string ...$paths): string
    {
        return array_reduce($paths, function($result, $path) {
            if (self::isAbsolute($path)) return $path;

            return self::normalize("{$result}/{$path}");
        }, getcwd());
    }

    public static function normalize($path): string
    {
        $path = preg_replace('~([\\/]+)~', '/', $path);
        return realpath($path);
    }

    public static function relative(string $from, string $to): string
    {
        if ($from === '') $from = getcwd();
        if ($to === '') $to = getcwd();

        if (self::resolve($from) === self::resolve($to)) {
            return '';
        }

        $fromParts = explode(DIRECTORY_SEPARATOR, $from);
        $toParts = explode(DIRECTORY_SEPARATOR, $to);

        $fromPartsCopy = $fromParts;
        $toPartsCopy = $toParts;
        for ($i = 0; $i < count($fromParts) && $i < count($toParts); $i++) {
            if ($fromParts[$i] === $toParts[$i]) {
                unset($fromPartsCopy[$i]);
                unset($fromPartsCopy[$i]);
            }
        }

        return str_repeat('../', count($fromPartsCopy))
            . implode('/', $toPartsCopy);
    }
}