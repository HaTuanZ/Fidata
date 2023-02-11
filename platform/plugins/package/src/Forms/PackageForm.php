<?php

namespace Botble\Package\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Package\Http\Requests\PackageRequest;
use Botble\Package\Models\Package;

class PackageForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Package)
            ->setValidatorClass(PackageRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
	        ->add('price', 'number', [
		        'label'      => __('Price'),
		        'label_attr' => ['class' => 'control-label required'],
	        ])
	        ->add('description', 'textarea', [
		        'label'      => __('Description'),
		        'label_attr' => ['class' => 'control-label'],
		        'attr'       => [
			        'rows' => 4,
		        ],
	        ])
	        ->add('access_length', 'customRadio', [
		        'label'      => __('Membership length'),
		        'label_attr' => ['class' => 'control-label'],
		        'choices'    => [
			        ['unlimited', 'Unlimited'],
			        ['specific', 'Specific length'],
			        ['fixed', 'Fixed dates']
		        ],
		        'default_value' => 'specific'
	        ])
	        ->add('rowOpen1', 'html', [
		        'html' => '<div class="row">',
	        ])
	        ->add('access_length_amount', 'number', [
		        'label'      => 'Amount (Specific length)',
		        'label_attr' => ['class' => 'control-label'],
		        'wrapper'    => [
			        'class' => 'form-group col-md-6',
		        ],
	        ])
	        ->add('access_length_period', 'select', [
		        'label'      => 'Period (Specific length)',
		        'label_attr' => ['class' => 'control-label'],
		        'wrapper'    => [
			        'class' => 'form-group col-md-6',
		        ],
		        'choices'    => [
			        'days' => __('day(s)'),
			        'weeks' => __('week(s)'),
			        'months' => __('month(s)'),
			        'years' => __('year(s)')
		        ],
	        ])
	        ->add('rowClose1', 'html', [
		        'html' => '</div>',
	        ])

	        ->add('rowOpen2', 'html', [
		        'html' => '<div class="row">',
	        ])
	        ->add('access_start_date', 'text', [
		        'label'         => __('Start date (Fixed dates)'),
		        'label_attr'    => ['class' => 'control-label'],
		        'attr'          => [
			        'class'            => 'form-control datepicker',
			        'data-date-format' => 'yyyy-mm-dd',
		        ],
		        'default_value' => now(config('app.timezone'))->format('Y-m-d'),
	        ])
	        ->add('access_end_date', 'text', [
		        'label'         => __('End date (Fixed dates)'),
		        'label_attr'    => ['class' => 'control-label'],
		        'attr'          => [
			        'class'            => 'form-control datepicker',
			        'data-date-format' => 'yyyy-mm-dd',
		        ],
	        ])
	        ->add('rowClose2', 'html', [
		        'html' => '</div>',
	        ])

	        ->add('content', 'repeater', [
		        'label'      => __('Functions'),
		        'label_attr' => ['class' => 'control-label'],
		        'fields' => [
			        [
				        'type'       => 'text',
				        'label'      => __('Function'),
				        'label_attr' => ['class' => 'control-label'],
				        'attributes' => [
					        'name'    => 'text',
					        'value'   => null,
					        'options' => [
						        'class'        => 'form-control',
						        'data-counter' => 140,
					        ],
				        ],
			        ],
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
	        ->add('image', 'mediaImage', [
		        'label'      => trans('core/base::forms.image'),
		        'label_attr' => ['class' => 'control-label'],
	        ])
            ->setBreakFieldPoint('status');
    }
}
