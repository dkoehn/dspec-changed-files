<?php

namespace DKoehn\DSpec\ChangedFiles;

class ChangedFiles
{
    private $git;

    public function __construct()
    {
        $this->git = new Git();
    }

    public function getChangedFilesForRoots(array $roots, ?Options $options = null)
    {
        $repos = $this->findRepos($roots);

        $changedFiles = array_reduce(
            $repos['git'],
            function(array $allFiles, string $repo) use ($options) {
                return array_merge($allFiles, $this->git->findChangedFiles($repo, $options));
            },
            []
        );

        return [
            'changedFiles' => $changedFiles,
            'repos' => $repos,
        ];
    }

    public function findRepos(array $roots)
    {
        $notEmpty = function($value) {
            return $value != null;
        };

        $gitRoots = array_map(function($root) {
            return $this->git->getRoot($root);
        }, $roots);

        return [
            'git' => array_filter($gitRoots, $notEmpty),
        ];
    }
}
