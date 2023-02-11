<?php

namespace Botble\Package\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Package\Http\Requests\PackageRequest;
use Botble\Package\Repositories\Interfaces\PackageInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Package\Tables\PackageTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Package\Forms\PackageForm;
use Botble\Base\Forms\FormBuilder;

class PackageController extends BaseController
{
    /**
     * @var PackageInterface
     */
    protected $packageRepository;

    /**
     * @param PackageInterface $packageRepository
     */
    public function __construct(PackageInterface $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    /**
     * @param PackageTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(PackageTable $table)
    {
        page_title()->setTitle(trans('plugins/package::package.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/package::package.create'));

        return $formBuilder->create(PackageForm::class)->renderForm();
    }

    /**
     * @param PackageRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(PackageRequest $request, BaseHttpResponse $response)
    {
    	$post_data = $request->input();
		$post_data['slug'] = strtolower($post_data['name']);
		$access_length = $post_data['access_length'];
		if($access_length == "unlimited") {
			$post_data['access_length_amount'] = NULL;
			$post_data['access_length_period'] = NULL;
			$post_data['access_start_date'] = NULL;
			$post_data['access_end_date'] = NULL;
		} else if($access_length == "specific") {
			$post_data['access_start_date'] = NULL;
			$post_data['access_end_date'] = NULL;
		} else {
			$post_data['access_length_amount'] = NULL;
			$post_data['access_length_period'] = NULL;
		}

        $package = $this->packageRepository->createOrUpdate($post_data);

        event(new CreatedContentEvent(PACKAGE_MODULE_SCREEN_NAME, $request, $package));

        return $response
            ->setPreviousUrl(route('package.index'))
            ->setNextUrl(route('package.edit', $package->id))
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
        $package = $this->packageRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $package));

        page_title()->setTitle(trans('plugins/package::package.edit') . ' "' . $package->name . '"');

        return $formBuilder->create(PackageForm::class, ['model' => $package])->renderForm();
    }

    /**
     * @param int $id
     * @param PackageRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, PackageRequest $request, BaseHttpResponse $response)
    {
        $package = $this->packageRepository->findOrFail($id);

	    $post_data = $request->input();
	    $post_data['slug'] = strtolower($post_data['name']);
	    $access_length = $post_data['access_length'];
	    if($access_length == "unlimited") {
		    $post_data['access_length_amount'] = NULL;
		    $post_data['access_length_period'] = NULL;
		    $post_data['access_start_date'] = NULL;
		    $post_data['access_end_date'] = NULL;
	    } else if($access_length == "specific") {
		    $post_data['access_start_date'] = NULL;
		    $post_data['access_end_date'] = NULL;
	    } else {
		    $post_data['access_length_amount'] = NULL;
		    $post_data['access_length_period'] = NULL;
	    }

        $package->fill($post_data);

        $package = $this->packageRepository->createOrUpdate($package);

        event(new UpdatedContentEvent(PACKAGE_MODULE_SCREEN_NAME, $request, $package));

        return $response
            ->setPreviousUrl(route('package.index'))
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
            $package = $this->packageRepository->findOrFail($id);

            $this->packageRepository->delete($package);

            event(new DeletedContentEvent(PACKAGE_MODULE_SCREEN_NAME, $request, $package));

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
            $package = $this->packageRepository->findOrFail($id);
            $this->packageRepository->delete($package);
            event(new DeletedContentEvent(PACKAGE_MODULE_SCREEN_NAME, $request, $package));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
