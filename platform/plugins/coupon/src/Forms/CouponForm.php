<?php

namespace Botble\Coupon\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Coupon\Http\Requests\CouponRequest;
use Botble\Coupon\Models\Coupon;

class CouponForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
	    $this
            ->setupModel(new Coupon)
            ->setValidatorClass(CouponRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('plugins/coupon::coupon.code'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/coupon::coupon.code'),
                    'data-counter' => 25,
                ],
            ])
	        ->add('discount_type', 'select', [ // Change "select" to "customSelect" for better UI
		        'label'      => __('Discount type'),
		        'label_attr' => ['class' => 'control-label required'], // Add class "required" if that is mandatory field
		        'choices'    => [
			        'percent' => __('Percentage discount'),
			        'fixed_product' => __('Fixed product discount'),
		        ],
	        ])
	        ->add('coupon_amount', 'text', [
		        'label'      => __('Coupon amount'),
		        'label_attr' => ['class' => 'control-label'],
		        'attr'       => [
			        'id'    => 'coupon_amount',
			        'class' => 'form-control input-mask-number',
		        ],
	        ])
	        ->add('expiry_date', 'text', [
		        'label'         => __('Coupon expiry date'),
		        'label_attr'    => ['class' => 'control-label'],
		        'attr'          => [
			        'class'            => 'form-control datepicker',
			        'data-date-format' => 'yyyy-mm-dd',
		        ],
		        'default_value' => now(config('app.timezone'))->format('Y-m-d'),
	        ])
		    ->add('usage_limit_per_user', 'text', [
			    'label'      => __('Usage limit per user'),
			    'label_attr' => ['class' => 'control-label'],
			    'attr'       => [
				    'id'    => 'usage_limit_per_user',
				    'class' => 'form-control input-mask-number',
			    ],
		    ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
