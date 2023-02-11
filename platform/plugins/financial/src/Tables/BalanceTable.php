<?php

namespace Botble\Financial\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Financial\Repositories\Interfaces\BalanceInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;

class BalanceTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = false;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    protected $hasOperations = false;


    public function __construct(DataTables $table, UrlGenerator $urlGenerator, BalanceInterface $balanceRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $balanceRepository;

        if (!Auth::user()->hasAnyPermission(['financial.edit', 'financial.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }

    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('updated_at', function ($item) {
                return BaseHelper::formatDateTime($item->updated_at);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'user_id',
                'balance',
                'currency_symbol',
                'updated_at'
            ]);

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'user_id' => [
                'title' => trans('plugins/financial::financial.user_id'),
                'class' => 'text-start',
            ],
            'balance' => [
                'title' => trans('plugins/financial::financial.amount'),
                'class' => 'text-start',
            ],
            'currency_symbol' => [
                'title' => trans('plugins/financial::financial.currency_symbol'),
                'class' => 'text-start',
            ],
            'updated_at' => [
                'title' => trans('core/base::tables.updated_at'),
                'class' => 'text-start',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'user_id' => [
                'title'    => trans('core/base::tables.user_id'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'currency_symbol' => [
                'title' => trans('core/base::tables.currency_symbol'),
                'type'  => 'text',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->getBulkChanges();
    }
}
