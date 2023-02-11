<?php

namespace Botble\Financial\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Financial\Http\Requests\FinancialRequest;
use Botble\Financial\Repositories\Interfaces\BalanceInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Financial\Tables\BalanceTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Financial\Forms\FinancialForm;
use Botble\Base\Forms\FormBuilder;
use Assets;
use Botble\Analysis\Models\Balance;
use Botble\Analysis\Models\BalanceLog;

class BalanceController extends BaseController
{
    /**
     * @var BalanceInterface
     */
    protected $balanceRepository;

    /**
     * @param BalanceInterface $balanceRepository
     */
    public function __construct(BalanceInterface $balanceRepository)
    {
        $this->balanceRepository = $balanceRepository;
    }

    /**
     * @param BalanceTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(BalanceTable $table)
    {
        page_title()->setTitle(trans('plugins/financial::financial.balance'));

        return $table->renderTable();
    }

    public function deposit(BalanceTable $table) {
        page_title()->setTitle(trans('plugins/financial::financial.balance'));

        return $table->renderTable();
    }

}
