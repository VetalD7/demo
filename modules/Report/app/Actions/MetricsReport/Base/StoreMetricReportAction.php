<?php

namespace Modules\Report\Actions\MetricsReport\Base;

use Illuminate\Support\Arr;
use Modules\Report\Actions\StoreReportAction;
use Modules\Report\Models\Report;

abstract class StoreMetricReportAction extends StoreReportAction
{
    /**
     * @param array $data
     *
     * @return Report
     */
    protected function storeReport(array $data): Report
    {
        return $this->saveReportData(parent::storeReport($data), $data);
    }
}
