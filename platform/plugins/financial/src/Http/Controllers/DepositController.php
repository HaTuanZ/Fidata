<?php

namespace Botble\Financial\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Financial\Http\Requests\FinancialRequest;
use Botble\Financial\Repositories\Interfaces\DepositInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Financial\Tables\DepositTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Financial\Forms\FinancialForm;
use Botble\Base\Forms\FormBuilder;
use Assets;
use Botble\Analysis\Models\Balance;
use Botble\Analysis\Models\BalanceLog;

class DepositController extends BaseController
{
    /**
     * @var DepositInterface
     */
    protected $depositRepository;

    /**
     * @param DepositInterface $depositRepository
     */
    public function __construct(DepositInterface $depositRepository)
    {
        $this->depositRepository = $depositRepository;
    }

    /**
     * @param DepositTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(DepositTable $table)
    {
        page_title()->setTitle(trans('plugins/financial::financial.deposit'));

        Assets::addScriptsDirectly('https://cdn.jsdelivr.net/npm/sweetalert2@11')
            ->addScriptsDirectly('themes/unitok/js/confirm_alert.js?v=1');

        return $table->renderTable();
    }

    public function deposit(DepositTable $table) {
        page_title()->setTitle(trans('plugins/financial::financial.deposit'));

        Assets::addScriptsDirectly('https://cdn.jsdelivr.net/npm/sweetalert2@11')
            ->addScriptsDirectly('themes/unitok/js/confirm_alert.js?v=1');

        return $table->renderTable();
    }

    public function cancel($id, Request $request, BaseHttpResponse $response) {
        try {
            $deposit = $this->depositRepository->findOrFail($id);
            if($deposit->status == 2) {
                $updated = $this->depositRepository->update(['id' => $id], [
                    'status' => 3,
                ]);
                if($updated) {
                    return $response
                        ->setPreviousUrl(route('deposit.index'))
                        ->setMessage("Deposit successfully");
                }
            } else {
                return $response->setError()->setMessage("Something error. Please check.");
            }
        } catch (Exception $exception) {
            return $response->setError()->setMessage($exception->getMessage());
        }
    }

    public function confirm($id, Request $request, BaseHttpResponse $response) {
        try {
            $deposit = $this->depositRepository->findOrFail($id);
            if($deposit->status == 2) {
                $updated = $this->depositRepository->update(['id' => $id], [
                    'status' => 5,
                ]);
                if($updated) {
                    // Set balance
                    $operator = "+";
                    $user_id = $deposit->user_id;
                    $currency_symbol = $deposit->currency_symbol;
                    $amount = $deposit->amount;
                    $transaction_type = "DEPOSIT";
                    $fees_amount = 0;
                    $note = "Deposit #".$id;

                    $balance_row = Balance::where([['user_id','=',$user_id],['currency_symbol','=',$currency_symbol]])->first();
                    if($balance_row) {
                        $current_balance = $balance_row->balance;
                        $prev_balance = $current_balance;
                        if($operator === "-") {
                            $new_balance = $current_balance - $amount;
                        } else {
                            $new_balance = $current_balance + $amount;
                        }
                        Balance::where('id', $balance_row->id)->update(array("balance" => $new_balance, "updated_at" => date("Y-m-d H:i:s")));
                        $balance_id = $balance_row->id;
                    } else {
                        $prev_balance = 0;
                        $inserted = Balance::create(array("user_id" => $user_id, "currency_symbol" => $currency_symbol, "balance" => $amount, "created_at" => date("Y-m-d H:i:s")));
                        $balance_id = $inserted->id;
                    }

                    $data_log = array(
                        "ref_id"             => $id,
                        "balance_id"         => $balance_id,
                        "user_id"            => $user_id,
                        "prev_balance"       => $prev_balance,
                        "currency_symbol"    => $currency_symbol,
                        "operator"           => $operator,
                        "transaction_type"   => $transaction_type,
                        "transaction_amount" => $amount,
                        "transaction_fees"   => $fees_amount,
                        "ip_address"         => $_SERVER['REMOTE_ADDR'],
                        "created_at"         => date("Y-m-d H:i:s"),
                        "note"               => $note
                    );
                    BalanceLog::create($data_log);

                    return $response
                        ->setPreviousUrl(route('deposit.index'))
                        ->setMessage("Deposit successfully");
                }
            } else {
                return $response->setError()->setMessage("Something error. Please check.");
            }
        } catch (Exception $exception) {
            return $response->setError()->setMessage($exception->getMessage());
        }
    }
}
