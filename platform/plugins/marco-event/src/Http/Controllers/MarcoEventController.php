<?php

namespace Botble\MarcoEvent\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\MarcoEvent\Http\Requests\MarcoEventRequest;
use Botble\MarcoEvent\Repositories\Interfaces\MarcoEventInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\MarcoEvent\Tables\MarcoEventTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\MarcoEvent\Forms\MarcoEventForm;
use Botble\Base\Forms\FormBuilder;

class MarcoEventController extends BaseController
{
    /**
     * @var MarcoEventInterface
     */
    protected $marcoEventRepository;

    /**
     * @param MarcoEventInterface $marcoEventRepository
     */
    public function __construct(MarcoEventInterface $marcoEventRepository)
    {
        $this->marcoEventRepository = $marcoEventRepository;
    }

    /**
     * @param MarcoEventTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(MarcoEventTable $table)
    {
        page_title()->setTitle(trans('plugins/marco-event::marco-event.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/marco-event::marco-event.create'));

        return $formBuilder->create(MarcoEventForm::class)->renderForm();
    }

    /**
     * @param MarcoEventRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(MarcoEventRequest $request, BaseHttpResponse $response)
    {
        $marcoEvent = $this->marcoEventRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(MARCO_EVENT_MODULE_SCREEN_NAME, $request, $marcoEvent));

        return $response
            ->setPreviousUrl(route('marco-event.index'))
            ->setNextUrl(route('marco-event.edit', $marcoEvent->id))
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
        $marcoEvent = $this->marcoEventRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $marcoEvent));

        page_title()->setTitle(trans('plugins/marco-event::marco-event.edit') . ' "' . $marcoEvent->name . '"');

        return $formBuilder->create(MarcoEventForm::class, ['model' => $marcoEvent])->renderForm();
    }

    /**
     * @param int $id
     * @param MarcoEventRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, MarcoEventRequest $request, BaseHttpResponse $response)
    {
        $marcoEvent = $this->marcoEventRepository->findOrFail($id);

        $marcoEvent->fill($request->input());

        $marcoEvent = $this->marcoEventRepository->createOrUpdate($marcoEvent);

        event(new UpdatedContentEvent(MARCO_EVENT_MODULE_SCREEN_NAME, $request, $marcoEvent));

        return $response
            ->setPreviousUrl(route('marco-event.index'))
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
            $marcoEvent = $this->marcoEventRepository->findOrFail($id);

            $this->marcoEventRepository->delete($marcoEvent);

            event(new DeletedContentEvent(MARCO_EVENT_MODULE_SCREEN_NAME, $request, $marcoEvent));

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
            $marcoEvent = $this->marcoEventRepository->findOrFail($id);
            $this->marcoEventRepository->delete($marcoEvent);
            event(new DeletedContentEvent(MARCO_EVENT_MODULE_SCREEN_NAME, $request, $marcoEvent));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
