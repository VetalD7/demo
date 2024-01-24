<?php

namespace Modules\Report\Repositories\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class TypeIdCriteria implements CriteriaInterface
{
    /**
     * @var int
     */
    protected int $typeId;

    /**
     * @param int $typeId
     */
    public function __construct(int $typeId)
    {
        $this->typeId = $typeId;
    }

    /**
     * Apply criteria in query repository
     *
     * @param \Prettus\Repository\Contracts\RepositoryInterface $model
     * @param \Prettus\Repository\Contracts\RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('type_id', $this->typeId);
    }
}
