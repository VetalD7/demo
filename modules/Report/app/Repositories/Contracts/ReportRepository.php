<?php

namespace Modules\Report\Repositories\Contracts;

use App\Repositories\Contracts\Repository;

interface ReportRepository extends Repository
{
    /**
     * Filter reports by status (few may be passed).
     *
     * @param array $ids
     * @return ReportRepository
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function byStatus(array $ids): ReportRepository;
}
