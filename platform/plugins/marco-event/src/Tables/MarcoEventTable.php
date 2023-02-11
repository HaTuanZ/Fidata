<?php

namespace Botble\MarcoEvent\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\MarcoEvent\Repositories\Interfaces\MarcoEventInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;

class MarcoEventTable extends TableAbstract
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
     * MarcoEventTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param MarcoEventInterface $marcoEventRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, MarcoEventInterface $marcoEventRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $marcoEventRepository;

        if (!Auth::user()->hasAnyPermission(['marco-event.edit', 'marco-event.destroy'])) {
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
                if (!Auth::user()->hasPermission('marco-event.edit')) {
                    return $item->name;
                }
                return Html::link(route('marco-event.edit', $item->id), $item->name);
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
                return $this->getOperations('marco-event.edit', 'marco-event.destroy', $item);
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
                'color',
                'event_date',
                'event_time',
                'actual',
                'forecast',
                'previous',
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
            'event_time' => [
                'title' => trans('plugins/marco-event::marco-event.event_time'),
                'class' => 'text-start',
            ],
            'color' => [
                'title' => __('Color'),
                'class' => 'text-start',
            ],
            'name' => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'actual' => [
                'title' => trans('plugins/marco-event::marco-event.actual'),
                'class' => 'text-start',
            ],
            'forecast' => [
                'title' => trans('plugins/marco-event::marco-event.forecast'),
                'class' => 'text-start',
            ],
            'previous' => [
                'title' => trans('plugins/marco-event::marco-event.previous'),
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
        return $this->addCreateButton(route('marco-event.create'), 'marco-event.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('marco-event.deletes'), 'marco-event.destroy', parent::bulkActions());
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
