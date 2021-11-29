<?php

declare(strict_types=1);

namespace PhpUnitCoverageBadge;

use function getenv;
use function realpath;

class Config
{
    public const REPO_TOKEN_DEFAULT      = 'NOT_SUPPLIED';
    public const NO_REPO_TOKEN_EXCEPTION = 'Pushing the badge was activated but no Github Repo token has been supplied. Please add it to your workflow.';

    private string $reportFilePath;
    private string $badgePath;
    private string $destRepo;
    private string $repoToken;
    private string $commitMessage;
    private string $commitEmail;
    private string $commitName;
    private string $githubWorkspace;

    public function __construct()
    {
        $githubWorkspace       = getenv('GITHUB_WORKSPACE', true);
        $this->githubWorkspace = $githubWorkspace;

        $this->reportFilePath = realpath($githubWorkspace . '/' . (getenv('INPUT_REPORT', true) ?? 'clover.xml'));

        $this->badgePath = realpath($githubWorkspace . '/' . getenv('INPUT_COVERAGE_BADGE_PATH', true));

        $this->repoToken = getenv('INPUT_REPO_TOKEN', true);

        $this->destRepo = getenv('INPUT_REPO', true); /* REPO to push to ex: AGWD/badges */

        $this->commitMessage = getenv('INPUT_COMMIT_MESSAGE', true);

        $this->commitEmail = getenv('INPUT_COMMIT_EMAIL', true);

        $this->commitName = getenv('INPUT_COMMIT_NAME', true);

    }

    public function getReportFilePath(): string
    {
        return $this->reportFilePath;
    }

    public function getBadgePath(): string
    {
        return $this->badgePath;
    }

    public function getRepoToken(): string
    {
        return $this->repoToken;
    }

    public function getGithubDestRepo(): string
    {
        return $this->destRepo;
    }

    public function getCommitMessage(): string
    {
        return $this->commitMessage;
    }

    public function getCommitEmail(): string
    {
        return $this->commitEmail;
    }

    public function getCommitName(): string
    {
        return $this->commitName;
    }

    public function getGithubWorkspace(): string
    {
        return $this->githubWorkspace;
    }
}
