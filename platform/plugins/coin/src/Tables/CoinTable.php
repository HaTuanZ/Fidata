<?php

namespace Botble\Coin\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Coin\Repositories\Interfaces\CoinInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;

class CoinTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * CoinTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param CoinInterface $coinRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, CoinInterface $coinRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $coinRepository;

        if (!Auth::user()->hasAnyPermission(['coin.edit', 'coin.destroy'])) {
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
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('coin.edit')) {
                    return $item->name;
                }
                return Html::link(route('coin.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('coin.edit', 'coin.destroy', $item);
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
               'name',
	           'coin_id',
               'symbol',
               'current_price',
               'total_volume',
               'created_at',
               'status',
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
            'name' => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'coin_id' => [
	            'title' => trans('plugins/coin::coin.coin_id'),
	            'class' => 'text-start',
            ],
            'symbol' => [
	            'title' => trans('plugins/coin::coin.symbol'),
	            'class' => 'text-start',
            ],
            'current_price' => [
	            'title' => trans('plugins/coin::coin.price'),
	            'class' => 'text-start',
            ],
            'total_volume' => [
	            'title' => trans('plugins/coin::coin.volume'),
	            'class' => 'text-start',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        return $this->addCreateButton(route('coin.create'), 'coin.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('coin.deletes'), 'coin.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'symbol' => [
                'title'    => __('Symbol'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'coin_id' => [
                'title'    => __('Coin ID'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
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
