<?php
declare(strict_types=1);

namespace PhpUnitCoverageBadge;

class GitService
{
    private Config $config;
    private BadgeGenerator $generator;
    public function __construct(Config $config, BadgeGenerator $generator)
    {
        $this->config = $config;
        $this->generator = $generator;
    }

    public function pushBadge(
        string $email,
        string $name,
        string $message,
        string $repoToken,
        string $githubWorkspace,
        string $destRepo,
        string $user,
        float $codeCoverage
    ): void {
        /* workspace now includes cloned repo path */
        $githubWorkspace = $this->clone($githubWorkspace, $destRepo);

        $this->generator->generateBadge($codeCoverage, $githubWorkspace . '/' . $this->config->getBadgePath());

        $this->addFile($this->config->getBadgePath(), $githubWorkspace);

        $this->setUserEmail($email, $githubWorkspace);
        $this->setUserName($name, $githubWorkspace);

        $this->commit($message, $githubWorkspace);

        $this->push(
            $user,
            $repoToken,
            $destRepo,
            '${GITHUB_REF#refs/heads/}',  //todo:
            $githubWorkspace
        );

        $this->cleanup($githubWorkspace);
    }

    /**
     * Checks out the default branch
     *
     * @param string $githubWorkspace
     * @param string $destRepo
     *
     * @return string
     */
    private function clone(string $githubWorkspace, string $destRepo): string
    {
        exec('cd ' . $githubWorkspace . ' && git clone --single-branch https://github.com/' . $destRepo . ' ' . $destRepo);

        /* workspace includes clone repo path */

        return rtrim($githubWorkspace, '/') . '/' . $destRepo;
    }

    private function addFile(string $fileName, string $githubWorkspace, string $renameTo = ''): void
    {
        exec('cd ' . $githubWorkspace . ' && git add "' . $fileName . '"');
    }

    private function setUserEmail(string $email, string $githubWorkspace): void
    {
        $this->setConfig('user.email', $email, $githubWorkspace);
    }

    private function setConfig(string $config, string $newSetting, string $githubWorkspace): void
    {
        exec('cd ' . $githubWorkspace . ' && git config ' . $config . ' "' . $newSetting . '"');
    }

    private function setUserName(string $name, string $githubWorkspace): void
    {
        $this->setConfig('user.name', $name, $githubWorkspace);
    }

    private function commit(string $commitMessage, string $githubWorkspace): void
    {
        exec('cd ' . $githubWorkspace . ' && git commit -m "' . $commitMessage . '"');
    }

    private function push(string $user, string $token, string $repo, string $headRef, string $githubWorkspace): void
    {
        exec(
            'cd ' . $githubWorkspace . ' && git push https://"' . $user . '":"' . $token . '"@github.com/"' . $repo . '".git'
        );
    }

    private function cleanup(string $dir, string $githubWorkspace): void
    {
        exec('cd ' . $githubWorkspace . ' && chmod -R a+w .git && rm -rf ' . $dir);
    }
}
