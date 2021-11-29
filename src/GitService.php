<?php
declare(strict_types=1);

namespace PhpUnitCoverageBadge;

class GitService
{
    public function pushBadge(
        string $email,
        string $name,
        string $message,
        string $repoToken,
        string $githubWorkspace,
        string $destRepo = '${GITHUB_REPOSITORY}',
        string $user = '${GITHUB_ACTOR}'
    ): void {
        /* workspace now includes cloned repo path */
        $githubWorkspace = $this->clone($githubWorkspace, $destRepo);

        $this->addFile('${INPUT_COVERAGE_BADGE_PATH}', $githubWorkspace);
        $this->addFile('${INPUT_REPORT}', $githubWorkspace);

        $this->setUserEmail($email, $githubWorkspace);
        $this->setUserName($name, $githubWorkspace);

        $this->commit($message, $githubWorkspace);

        $this->push(
            $user,
            $repoToken,
            $destRepo,
            '${GITHUB_REF#refs/heads/}',
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

    private function addFile(string $fileName, string $githubWorkspace): void
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
            'cd ' . $githubWorkspace . ' && git push https://"' . $user . '":"' . $token . '"@github.com/"' . $repo
            . '".git HEAD:"' . $headRef . '";'
        );
    }

    private function cleanup($dir): void
    {
        exec('rm -rf ' . $dir);
    }
}
