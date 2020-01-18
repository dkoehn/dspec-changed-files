<?php

namespace DKoehn\DSpec\ChangedFiles;

class Utils
{
    public static function exec($cmd, array $args, string $cwd): array
    {
        $arguments = $args ? ' ' . implode(' ', $args) : '';

        $command = "cd {$cwd} ; {$cmd}{$arguments}";
        exec($command, $output, $exitCode);

        return [
            'command' => $command,
            'exitCode' => $exitCode,
            'stdout' => implode("\n", $output),
        ];
    }
}