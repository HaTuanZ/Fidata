<?php

namespace Botble\Livestream\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Livestream\Http\Requests\LivestreamRequest;
use Botble\Livestream\Models\Livestream;
use Botble\Blog\Repositories\Interfaces\AccountInterface;

class LivestreamForm extends FormAbstract
{
	protected $accountRepository;

	public function __construct(
		AccountInterface $accountRepository
	) {
		parent::__construct();
		$this->accountRepository = $accountRepository;
	}

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
	    $authors = $this->accountRepository->pluck('username', 'id');

        $this
            ->setupModel(new Livestream)
            ->setValidatorClass(LivestreamRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
	        ->add('description', 'editor', [
		        'label'      => __('Description'),
		        'label_attr' => ['class' => 'control-label'],
		        'attr'       => [
			        'with-short-code' => false,
			        'without-buttons' => false,
		        ],
	        ])
	        ->add('user_id', 'customSelect', [
		        'label'      => trans('plugins/blog::posts.author'),
		        'label_attr' => ['class' => 'control-label'],
		        'attr'       => [
			        'class' => 'form-control select-search-full',
		        ],
		        'choices'    => [0 => trans('plugins/blog::posts.author')] + $authors,
	        ])
	        ->add('embled', 'textarea', [
		        'label'      => __('Embled code'),
		        'label_attr' => ['class' => 'control-label'],
		        'attr'       => [
			        'rows' => 6,
		        ],
	        ])
	        ->add('video_url', 'text', [
		        'label'      => __('Video URL'),
		        'label_attr' => ['class' => 'control-label'],
		        'attr'       => [
			        'class' => 'form-control',
		        ],
	        ])
	        ->add('gem', 'text', [
		        'label'      => __('GEM'),
		        'label_attr' => ['class' => 'control-label'],
		        'attr'       => [
			        'class' => 'form-control',
		        ],
	        ])
	        ->add('rowOpen1', 'html', [
		        'html' => '<div class="row">',
	        ])
	        ->add('event_date', 'text', [
		        'label'      => __('Date start'),
		        'label_attr' => ['class' => 'control-label'],
		        'wrapper'    => [
			        'class' => 'form-group mb-3 col-md-6',
		        ],
		        'attr'          => [
			        'class'            => 'form-control datepicker',
			        'data-date-format' => 'yyyy-mm-dd',
		        ],
		        'default_value' => now(config('app.timezone'))->format('Y-m-d'),
	        ])
	        ->add('event_time', 'time', [
		        'label'      => __('Time start (UTC+0)'),
		        'label_attr' => ['class' => 'control-label'],
		        'wrapper'    => [
			        'class' => 'form-group mb-3 col-md-6',
		        ],
	        ])
	        ->add('rowClose1', 'html', [
		        'html' => '</div>',
	        ])
	        ->add('rowOpen2', 'html', [
		        'html' => '<div class="row">',
	        ])
	        ->add('end_date', 'text', [
		        'label'      => __('Date end'),
		        'label_attr' => ['class' => 'control-label'],
		        'wrapper'    => [
			        'class' => 'form-group mb-3 col-md-6',
		        ],
		        'attr'          => [
			        'class'            => 'form-control datepicker',
			        'data-date-format' => 'yyyy-mm-dd',
		        ],
		        'default_value' => now(config('app.timezone'))->format('Y-m-d'),
	        ])
	        ->add('end_time', 'time', [
		        'label'      => __('Time end (UTC+0)'),
		        'label_attr' => ['class' => 'control-label'],
		        'wrapper'    => [
			        'class' => 'form-group mb-3 col-md-6',
		        ],
	        ])
	        ->add('rowClose2', 'html', [
		        'html' => '</div>',
	        ])
	        ->add('thumbnail', 'mediaImage', [
		        'label'      => __('Poster'),
		        'label_attr' => ['class' => 'control-label'],
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
