<?php
declare(strict_types=1);

namespace PhpUnitCoverageBadge;

use PhpUnitCoverageBadge\ReportParser\ReportParserInterface;

class Command
{
    private ReportParserInterface $reportParser;
    private BadgeGenerator $badgeGenerator;


    public function __construct(
        ReportParserInterface $reportParser,
        BadgeGenerator $badgeGenerator,

    ) {
        $this->reportParser = $reportParser;
        $this->badgeGenerator = $badgeGenerator;
    }

    public function run(): void
    {
        $config = new Config();

        $codeCoverage = $this->reportParser->getCodeCoverage($config->getReportFilePath());

        $this->badgeGenerator->generateBadge($codeCoverage, $config->getBadgePath());

        (new GitService())->pushBadge(
            $config->getCommitEmail(),
            $config->getCommitName(),
            $config->getCommitMessage(),
            $config->getRepoToken(),
            $config->getGithubWorkspace(),
            $config->getGithubDestRepo(),
            $config->getCommitUser()
        );
    }
}
