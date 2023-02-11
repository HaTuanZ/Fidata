<?php

namespace Botble\Gem\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Gem\Http\Requests\GemRequest;
use Botble\Gem\Repositories\Interfaces\GemInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Gem\Tables\GemTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Gem\Forms\GemForm;
use Botble\Base\Forms\FormBuilder;

class GemController extends BaseController
{
    /**
     * @var GemInterface
     */
    protected $gemRepository;

    /**
     * @param GemInterface $gemRepository
     */
    public function __construct(GemInterface $gemRepository)
    {
        $this->gemRepository = $gemRepository;
    }

    /**
     * @param GemTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(GemTable $table)
    {
        page_title()->setTitle(trans('plugins/gem::gem.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/gem::gem.create'));

        return $formBuilder->create(GemForm::class)->renderForm();
    }

    /**
     * @param GemRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(GemRequest $request, BaseHttpResponse $response)
    {
        $gem = $this->gemRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(GEM_MODULE_SCREEN_NAME, $request, $gem));

        return $response
            ->setPreviousUrl(route('gem.index'))
            ->setNextUrl(route('gem.edit', $gem->id))
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
        $gem = $this->gemRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $gem));

        page_title()->setTitle(trans('plugins/gem::gem.edit') . ' "' . $gem->name . '"');

        return $formBuilder->create(GemForm::class, ['model' => $gem])->renderForm();
    }

    /**
     * @param int $id
     * @param GemRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, GemRequest $request, BaseHttpResponse $response)
    {
        $gem = $this->gemRepository->findOrFail($id);

        $gem->fill($request->input());

        $gem = $this->gemRepository->createOrUpdate($gem);

        event(new UpdatedContentEvent(GEM_MODULE_SCREEN_NAME, $request, $gem));

        return $response
            ->setPreviousUrl(route('gem.index'))
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
            $gem = $this->gemRepository->findOrFail($id);

            $this->gemRepository->delete($gem);

            event(new DeletedContentEvent(GEM_MODULE_SCREEN_NAME, $request, $gem));

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
            $gem = $this->gemRepository->findOrFail($id);
            $this->gemRepository->delete($gem);
            event(new DeletedContentEvent(GEM_MODULE_SCREEN_NAME, $request, $gem));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
