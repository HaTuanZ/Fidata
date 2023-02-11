<?php

namespace Botble\Financial\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Financial\Http\Requests\FinancialRequest;
use Botble\Financial\Repositories\Interfaces\FinancialInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Financial\Tables\FinancialTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Financial\Forms\FinancialForm;
use Botble\Base\Forms\FormBuilder;

use Botble\Financial\Tables\DepositTable;

class FinancialController extends BaseController
{
    /**
     * @var FinancialInterface
     */
    protected $financialRepository;

    /**
     * @param FinancialInterface $financialRepository
     */
    public function __construct(FinancialInterface $financialRepository)
    {
        $this->financialRepository = $financialRepository;
    }

    /**
     * @param FinancialTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(FinancialTable $table)
    {
        page_title()->setTitle(trans('plugins/financial::financial.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/financial::financial.create'));

        return $formBuilder->create(FinancialForm::class)->renderForm();
    }

    /**
     * @param FinancialRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(FinancialRequest $request, BaseHttpResponse $response)
    {
        $financial = $this->financialRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FINANCIAL_MODULE_SCREEN_NAME, $request, $financial));

        return $response
            ->setPreviousUrl(route('financial.index'))
            ->setNextUrl(route('financial.edit', $financial->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $financial = $this->financialRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $financial));

        page_title()->setTitle(trans('plugins/financial::financial.edit') . ' "' . $financial->name . '"');

        return $formBuilder->create(FinancialForm::class, ['model' => $financial])->renderForm();
    }

    /**
     * @param int $id
     * @param FinancialRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, FinancialRequest $request, BaseHttpResponse $response)
    {
        $financial = $this->financialRepository->findOrFail($id);

        $financial->fill($request->input());

        $financial = $this->financialRepository->createOrUpdate($financial);

        event(new UpdatedContentEvent(FINANCIAL_MODULE_SCREEN_NAME, $request, $financial));

        return $response
            ->setPreviousUrl(route('financial.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $financial = $this->financialRepository->findOrFail($id);

            $this->financialRepository->delete($financial);

            event(new DeletedContentEvent(FINANCIAL_MODULE_SCREEN_NAME, $request, $financial));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $financial = $this->financialRepository->findOrFail($id);
            $this->financialRepository->delete($financial);
            event(new DeletedContentEvent(FINANCIAL_MODULE_SCREEN_NAME, $request, $financial));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

}
