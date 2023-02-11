<?php

namespace Botble\Financial\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Financial\Repositories\Interfaces\DepositInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;

class DepositTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = false;

    /**
     * @var bool
     */
    protected $hasFilter = true;


    /**
     * FinancialTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param DepositInterface $depositRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, DepositInterface $depositRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $depositRepository;

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
            ->editColumn('amount', function ($item) {
                return '<strong>'.$item->amount.' </strong> '.$item->currency_symbol;
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDateTime($item->created_at);
            })
            ->editColumn('status', function ($item) {
                switch ($item->status) {
                    case 3:
                        $status = '<span class="label-danger status-label">Cancelled</span>';
                        break;
                    case 5:
                        $status = '<span class="label-success status-label">Confirmed</span>';
                        break;
                    default:
                        $status = '<span class="label-warning status-label">Pending</span>';
                        break;
                }
                return $status;
            })
            ->addColumn('operations', function ($item) {
                if($item->status == 2) {
                    $cancel_link = route('deposit.cancel', $item->id);
                    $confirm_link = route('deposit.confirm', $item->id);
                    $html = '<div class="table-actions"><button onclick="confirm_alert(\''.$cancel_link.'\')" type="button" class="btn btn-secondary">Cancel</button> <button onclick="confirm_alert(\''.$confirm_link.'\')" type="button" class="btn btn-primary">Confirm</button></div>';
                    return $html;
                }
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
                'email_account',
                'user_id',
                'amount',
                'currency_symbol',
                'address',
                'note',
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
            'email_account' => [
                'title' => trans('core/base::tables.email'),
                'class' => 'text-start',
            ],
            'user_id' => [
                'title' => trans('plugins/financial::financial.user_id'),
                'class' => 'text-start',
            ],
            'amount' => [
                'title' => trans('plugins/financial::financial.amount'),
                'class' => 'text-start',
            ],
            'address' => [
                'title' => trans('plugins/financial::financial.address'),
                'class' => 'text-start',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'note' => [
                'title' => trans('plugins/financial::financial.note'),
                'class' => 'text-start',
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
    public function getBulkChanges(): array
    {
        return [
            'user_id' => [
                'title'    => trans('core/base::tables.user_id'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            /*
            'status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],*/
            'email_account' => [
                'title' => trans('core/base::tables.email'),
                'type'  => 'email',
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
