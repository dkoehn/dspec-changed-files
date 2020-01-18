<?php

namespace DKoehn\DSpec\ChangedFiles;

class Git
{
    public function getRoot(string $dir)
    {
        $args = ['rev-parse', '--show-cdup'];
        try {
            ['stdout' => $stdout] = Utils::exec('git', $args, $dir);

            return Path::resolve($dir, $stdout);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function findChangedFiles(string $cwd, ?Options $options)
    {
        $changedSince = $options
            ? ($options->getWithAncestor() ? 'HEAD^' : $options->getChangedSince())
            : null;

        $includePaths = $options
            ? $options->getIncludePaths() ?: []
            : [];

        $includePaths = array_map(function(string $absolutePath) use ($cwd) {
            return Path::normalize(Path::relative($cwd, $absolutePath));
        }, $includePaths);

        if ($options && $options->getLastCommit()) {
            return $this->findChangedFilesUsingCommand(
                array_merge(['show', '--name-only', '--pretty=format:', 'HEAD'], $includePaths),
                $cwd
            );
        } elseif ($changedSince) {
            $committed = $this->findChangedFilesUsingCommand(
                array_merge([
                    'log',
                    '--name-only',
                    '--pretty=format:',
                    'HEAD',
                    "^{$changedSince}",
                ], $includePaths),
                $cwd
            );
            $staged = $this->findChangedFilesUsingCommand(
                array_merge(['diff', '--cached', '--name-only'], $includePaths),
                $cwd
            );
            $unstaged = $this->findChangedFilesUsingCommand(
                array_merge(['ls-files', '--other', '--modified', '--exclude-standard'], $includePaths),
                $cwd
            );

            return array_merge(
                $committed,
                $staged,
                $unstaged
            );
        }

        return $this->findChangedFilesUsingCommand(
            array_merge(['ls-files', '--other', '--modified', '--exclude-standard'], $includePaths),
            $cwd
        );
    }

    private function findChangedFilesUsingCommand(array $args, string $cwd): array
    {
        $notEmpty = function(string $s): bool{
            return trim($s) !== '';
        };

        try {
            ['stdout' => $stdout] = Utils::exec('git', $args, $cwd);
        } catch (\Exception $e) {
            throw $e;
        }

        $lines = array_filter(explode("\n", $stdout), $notEmpty);

        return array_map(function($changedPath) use ($cwd) {
            return Path::resolve($cwd, $changedPath);
        }, $lines);
    }
}