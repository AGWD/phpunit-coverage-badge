<?php
declare(strict_types=1);

namespace PhpUnitCoverageBadge\ReportParser;

class CloverReportParser implements ReportParserInterface
{
    public const NO_METRICS_IN_CLOVER_FILE_EXCEPTION = 'Could not parse metrics from clover file.
        Please check that xml node <metrics> does exist as a child of <project>';

    public function getCodeCoverage(string $coverageReportPath): float
    {
        $reportMetrics = $this->getReportMetrics($coverageReportPath);

        $coveredElements = $reportMetrics['coveredElements'];
        $elements        = $reportMetrics['elements'];

        //Prevent divide by zero errors
        $elements = $elements === 0 ? 1 : $elements;

        return $coveredElements / $elements * 100;
    }

    /**
     * Extracts the report metrics from a phpunit clover report
     * Inspired by:
     * https://ocramius.github.io/blog/automated-code-coverage-check-for-github-pull-requests-with-travis/
     *
     * @return int[]
     *
     * @psalm-suppress UndefinedPropertyFetch
     * @throws \Exception
     */
    private function getReportMetrics(string $coverageReportPath): array
    {
        $xmlElement        = new \SimpleXMLElement(\file_get_contents($coverageReportPath));
        $metricsAttributes = ($xmlElement->xPath('project/metrics')[0] ?? null)->attributes();
        if (!$metricsAttributes) {
            return [];
        }

        return [
            'elements'        => (int)$metricsAttributes->elements,
            'coveredElements' => (int)$metricsAttributes->coveredelements,
        ];
    }
}
