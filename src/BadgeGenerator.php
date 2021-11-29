<?php
declare(strict_types=1);

namespace PhpUnitCoverageBadge;

class BadgeGenerator
{
    public const COLORS
        = [
            0  => "#e05d44",
            60 => "#fe7d37",
            70 => "#dfb317",
            80 => "#97ca00",
            90 => "#4c1",
        ];

    public function generateBadge(float $codeCoverage, string $badgePath): void
    {
        $template = \file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '../template.svg');

        $formattedCoverage = $this->formatCoverageNumber($codeCoverage);
        $color             = $this->matchCoverageColor($codeCoverage);

        $badge = \str_replace(array('$cov$', '$color$'), array($formattedCoverage, $color), $template);

        $this->saveBadge($badge, $badgePath);
    }

    private function formatCoverageNumber(float $coverage): string
    {
        return \floor($coverage) . ' %';
    }

    private function matchCoverageColor(float $coverage): string
    {
        $coverageColor = self::COLORS[0];
        foreach (\array_reverse(self::COLORS, true) as $threshold => $color) {
            if ($coverage >= $threshold) {
                return $color;
            }
        }

        return $coverageColor;
    }

    public function saveBadge(string $badge, string $badgePath): void
    {
        $targetDirectory = \dirname($badgePath);

        if (!\is_dir($targetDirectory) && !\mkdir($targetDirectory, 0777, true) && !\is_dir($targetDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $targetDirectory));
        }

        \file_put_contents($badgePath, $badge);
    }
}
