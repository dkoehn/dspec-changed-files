<?php

namespace DKoehn\DSpec\ChangedFiles;

class Options
{
    private $lastCommit;
    private $withAncestor;
    private $changedSince;
    private $includePaths;

    public function __construct(
        bool $lastCommit = null,
        bool $withAncestor = null,
        string $changedSince = null,
        array $includePaths = null
    ) {
        $this->lastCommit = $lastCommit;
        $this->withAncestor = $withAncestor;
        $this->changedSince = $changedSince;
        $this->includePaths = $includePaths;
    }

    public function getLastCommit(): ?bool
    {
        return $this->lastCommit;
    }

    public function setLastCommit(bool $lastCommit = null): Options
    {
        $this->lastCommit = $lastCommit;
        return $this;
    }

    public function getWithAncestor(): ?bool
    {
        return $this->withAncestor;
    }

    public function setWithAncestor(bool $withAncestor = null): Options
    {
        $this->withAncestor = $withAncestor;
        return $this;
    }

    public function getChangedSince(): ?string
    {
        return $this->changedSince;
    }

    public function setChangedSince(string $changedSince = null): Options
    {
        $this->changedSince = $changedSince;
        return $this;
    }

    public function getIncludePaths(): ?array
    {
        return $this->includePaths;
    }

    public function setIncludePaths(array $includePaths = null): Options
    {
        $this->includePaths = $includePaths;
        return $this;
    }
}