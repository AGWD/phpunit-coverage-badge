<?php
declare(strict_types=1);
namespace PhpUnitCoverageBadge\ReportParser;

interface ReportParserInterface
{
    /**
     * Returns the code coverage as a percentage (e.g. 65.12%)
     */
    public function getCodeCoverage(string $coverageReportPath): float;
}
