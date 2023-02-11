<?php

namespace Botble\ApiKeys\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\ApiKeys\Http\Requests\ApiKeysRequest;
use Botble\ApiKeys\Repositories\Interfaces\ApiKeysInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\ApiKeys\Tables\ApiKeysTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\ApiKeys\Forms\ApiKeysForm;
use Botble\Base\Forms\FormBuilder;

class ApiKeysController extends BaseController
{
    /**
     * @var ApiKeysInterface
     */
    protected $apiKeysRepository;

    /**
     * @param ApiKeysInterface $apiKeysRepository
     */
    public function __construct(ApiKeysInterface $apiKeysRepository)
    {
        $this->apiKeysRepository = $apiKeysRepository;
    }

    /**
     * @param ApiKeysTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ApiKeysTable $table)
    {
        page_title()->setTitle(trans('plugins/api-keys::api-keys.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/api-keys::api-keys.create'));

        return $formBuilder->create(ApiKeysForm::class)->renderForm();
    }

    /**
     * @param ApiKeysRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(ApiKeysRequest $request, BaseHttpResponse $response)
    {
        $apiKeys = $this->apiKeysRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(API_KEYS_MODULE_SCREEN_NAME, $request, $apiKeys));

        return $response
            ->setPreviousUrl(route('api-keys.index'))
            ->setNextUrl(route('api-keys.edit', $apiKeys->id))
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
        $apiKeys = $this->apiKeysRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $apiKeys));

        page_title()->setTitle(trans('plugins/api-keys::api-keys.edit') . ' "' . $apiKeys->name . '"');

        return $formBuilder->create(ApiKeysForm::class, ['model' => $apiKeys])->renderForm();
    }

    /**
     * @param int $id
     * @param ApiKeysRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, ApiKeysRequest $request, BaseHttpResponse $response)
    {
        $apiKeys = $this->apiKeysRepository->findOrFail($id);

        $apiKeys->fill($request->input());

        $apiKeys = $this->apiKeysRepository->createOrUpdate($apiKeys);

        event(new UpdatedContentEvent(API_KEYS_MODULE_SCREEN_NAME, $request, $apiKeys));

        return $response
            ->setPreviousUrl(route('api-keys.index'))
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
            $apiKeys = $this->apiKeysRepository->findOrFail($id);

            $this->apiKeysRepository->delete($apiKeys);

            event(new DeletedContentEvent(API_KEYS_MODULE_SCREEN_NAME, $request, $apiKeys));

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
            $apiKeys = $this->apiKeysRepository->findOrFail($id);
            $this->apiKeysRepository->delete($apiKeys);
            event(new DeletedContentEvent(API_KEYS_MODULE_SCREEN_NAME, $request, $apiKeys));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
