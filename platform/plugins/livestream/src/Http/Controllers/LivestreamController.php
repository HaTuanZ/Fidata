<?php

namespace Botble\Livestream\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Livestream\Http\Requests\LivestreamRequest;
use Botble\Livestream\Repositories\Interfaces\LivestreamInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Livestream\Tables\LivestreamTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Livestream\Forms\LivestreamForm;
use Botble\Base\Forms\FormBuilder;

class LivestreamController extends BaseController
{
    /**
     * @var LivestreamInterface
     */
    protected $livestreamRepository;

    /**
     * @param LivestreamInterface $livestreamRepository
     */
    public function __construct(LivestreamInterface $livestreamRepository)
    {
        $this->livestreamRepository = $livestreamRepository;
    }

    /**
     * @param LivestreamTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(LivestreamTable $table)
    {
        page_title()->setTitle(trans('plugins/livestream::livestream.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/livestream::livestream.create'));

        return $formBuilder->create(LivestreamForm::class)->renderForm();
    }

    /**
     * @param LivestreamRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(LivestreamRequest $request, BaseHttpResponse $response)
    {
	    $post_data = $request->input();
	    $event_time = $post_data['event_time'] ? explode(":", $post_data['event_time']) : array();
	    if($event_time[0] && $event_time[1]) {
		    $event_time = sprintf('%02d', $event_time[0]).":".$event_time[1].":00";
	    }
	    $post_data['event_datetime'] = strtotime($post_data['event_date']." ".$event_time);
	    $post_data['event_date_time'] = $post_data['event_date']." ".$event_time;

        $livestream = $this->livestreamRepository->createOrUpdate($post_data);

        event(new CreatedContentEvent(LIVESTREAM_MODULE_SCREEN_NAME, $request, $livestream));

        return $response
            ->setPreviousUrl(route('livestream.index'))
            ->setNextUrl(route('livestream.edit', $livestream->id))
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
        $livestream = $this->livestreamRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $livestream));

        page_title()->setTitle(trans('plugins/livestream::livestream.edit') . ' "' . $livestream->name . '"');

        return $formBuilder->create(LivestreamForm::class, ['model' => $livestream])->renderForm();
    }

    /**
     * @param int $id
     * @param LivestreamRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, LivestreamRequest $request, BaseHttpResponse $response)
    {
        $livestream = $this->livestreamRepository->findOrFail($id);

        $post_data = $request->input();
        $event_time = $post_data['event_time'] ? explode(":", $post_data['event_time']) : array();
        if($event_time[0] && $event_time[1]) {
	        $event_time = sprintf('%02d', $event_time[0]).":".$event_time[1].":00";
        }
        $post_data['event_datetime'] = strtotime($post_data['event_date']." ".$event_time);
	    $post_data['event_date_time'] = $post_data['event_date']." ".$event_time;

        $livestream->fill($post_data);

        $livestream = $this->livestreamRepository->createOrUpdate($livestream);

        event(new UpdatedContentEvent(LIVESTREAM_MODULE_SCREEN_NAME, $request, $livestream));

        return $response
            ->setPreviousUrl(route('livestream.index'))
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
            $livestream = $this->livestreamRepository->findOrFail($id);

            $this->livestreamRepository->delete($livestream);

            event(new DeletedContentEvent(LIVESTREAM_MODULE_SCREEN_NAME, $request, $livestream));

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
            $livestream = $this->livestreamRepository->findOrFail($id);
            $this->livestreamRepository->delete($livestream);
            event(new DeletedContentEvent(LIVESTREAM_MODULE_SCREEN_NAME, $request, $livestream));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
