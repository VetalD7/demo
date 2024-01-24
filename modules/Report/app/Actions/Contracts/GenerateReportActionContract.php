<?php

namespace Modules\Report\Actions\Contracts;

use Modules\Report\Models\Report;

interface GenerateReportActionContract
{
    /**
     * @param Report $report
     * @param int    $offset
     * @param string $path
     *
     * @return int|null
     */
    public function handle(Report $report, int $offset, string $path): ?int;
}
