<?php

namespace Botble\ApiKeys\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\ApiKeys\Repositories\Interfaces\ApiKeysInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;

class ApiKeysTable extends TableAbstract
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
     * ApiKeysTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param ApiKeysInterface $apiKeysRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ApiKeysInterface $apiKeysRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $apiKeysRepository;

        if (!Auth::user()->hasAnyPermission(['api-keys.edit', 'api-keys.destroy'])) {
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
                if (!Auth::user()->hasPermission('api-keys.edit')) {
                    return $item->name;
                }
                return Html::link(route('api-keys.edit', $item->id), $item->name);
            })
            ->editColumn('api_key', function ($item) {
                return $item->api_key;
            })
            ->editColumn('api_key_secret', function ($item) {
                return $item->api_key_secret;
            })
            ->editColumn('created_by', function ($item) {
                return $item->createdBy ? $item->createdBy->name : null;
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
                return $this->getOperations('api-keys.edit', 'api-keys.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
            ->with('createdBy')
            ->select([
                'id',
                'name',
                'api_key',
                'api_key_secret',
                'user_id',
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
            'api_key' => [
                'title' => trans('core/base::tables.api_key'),
                'class' => 'text-start',
            ],
            'api_key_secret' => [
                'title' => trans('core/base::tables.api_key_secret'),
                'class' => 'text-start',
            ],
            "created_by" => [
                'title' => trans('core/base::tables.created_by'),
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
        return $this->addCreateButton(route('api-keys.create'), 'api-keys.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('api-keys.deletes'), 'api-keys.destroy', parent::bulkActions());
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
