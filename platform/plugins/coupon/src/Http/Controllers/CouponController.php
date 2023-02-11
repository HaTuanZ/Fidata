<?php

namespace Botble\Coupon\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Coupon\Http\Requests\CouponRequest;
use Botble\Coupon\Repositories\Interfaces\CouponInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Coupon\Tables\CouponTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Coupon\Forms\CouponForm;
use Botble\Base\Forms\FormBuilder;

class CouponController extends BaseController
{
    /**
     * @var CouponInterface
     */
    protected $couponRepository;

    /**
     * @param CouponInterface $couponRepository
     */
    public function __construct(CouponInterface $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }

    /**
     * @param CouponTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(CouponTable $table)
    {
        page_title()->setTitle(trans('plugins/coupon::coupon.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/coupon::coupon.create'));

        return $formBuilder->create(CouponForm::class)->renderForm();
    }

    /**
     * @param CouponRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(CouponRequest $request, BaseHttpResponse $response)
    {
        $coupon = $this->couponRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(COUPON_MODULE_SCREEN_NAME, $request, $coupon));

        return $response
            ->setPreviousUrl(route('coupon.index'))
            ->setNextUrl(route('coupon.edit', $coupon->id))
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
        $coupon = $this->couponRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $coupon));

        page_title()->setTitle(trans('plugins/coupon::coupon.edit') . ' "' . $coupon->name . '"');

        return $formBuilder->create(CouponForm::class, ['model' => $coupon])->renderForm();
    }

    /**
     * @param int $id
     * @param CouponRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, CouponRequest $request, BaseHttpResponse $response)
    {
        $coupon = $this->couponRepository->findOrFail($id);

        $coupon->fill($request->input());

        $coupon = $this->couponRepository->createOrUpdate($coupon);

        event(new UpdatedContentEvent(COUPON_MODULE_SCREEN_NAME, $request, $coupon));

        return $response
            ->setPreviousUrl(route('coupon.index'))
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
            $coupon = $this->couponRepository->findOrFail($id);

            $this->couponRepository->delete($coupon);

            event(new DeletedContentEvent(COUPON_MODULE_SCREEN_NAME, $request, $coupon));

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
            $coupon = $this->couponRepository->findOrFail($id);
            $this->couponRepository->delete($coupon);
            event(new DeletedContentEvent(COUPON_MODULE_SCREEN_NAME, $request, $coupon));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getApplied(Request $request) {
	    $curl = curl_init();

	    curl_setopt_array($curl, array(
		    CURLOPT_URL => 'https://coingen.net/api/getGemRedeemCoupon',
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_ENCODING => '',
		    CURLOPT_MAXREDIRS => 10,
		    CURLOPT_TIMEOUT => 0,
		    CURLOPT_FOLLOWLOCATION => true,
		    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		    CURLOPT_CUSTOMREQUEST => 'GET',
		    CURLOPT_POSTFIELDS => array('per_page' => '1'),
		    CURLOPT_HTTPHEADER => array(
			    'Cookie: defi_cookie=b4ejo5ebelaiig5h07jdus925u86jc32; web_cookie=02d96cbe648103b8b10b22421636cbd3'
		    ),
	    ));

	    $response = curl_exec($curl);

	    curl_close($curl);

	    $applied_coupons = json_decode($response);

	    return view('plugins/coupon::applied', compact('applied_coupons'));
    }
}
