<?php

namespace Botble\Blog\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\Fields\TagField;
use Botble\Base\Forms\FormAbstract;
use Botble\Blog\Forms\Fields\CategoryMultiField;
use Botble\Blog\Http\Requests\PostRequest;
use Botble\Blog\Models\Post;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Repositories\Interfaces\AccountInterface;

class PostForm extends FormAbstract
{

    /**
     * @var string
     */
    protected $template = 'core/base::forms.form-tabs';

	protected $accountRepository;

	public function __construct(
		AccountInterface $accountRepository
	) {
		parent::__construct();
		$this->accountRepository = $accountRepository;
	}

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function buildForm()
    {
        $selectedCategories = [];
        if ($this->getModel()) {
            $selectedCategories = $this->getModel()->categories()->pluck('category_id')->all();
        }

        if (empty($selectedCategories)) {
            $selectedCategories = app(CategoryInterface::class)
                ->getModel()
                ->where('is_default', 1)
                ->pluck('id')
                ->all();
        }

        $tags = null;

        if ($this->getModel()) {
            $tags = $this->getModel()->tags()->pluck('name')->all();
            $tags = implode(',', $tags);
        }

        if (!$this->formHelper->hasCustomField('categoryMulti')) {
            $this->formHelper->addCustomField('categoryMulti', CategoryMultiField::class);
        }

	    $authors = $this->accountRepository->pluck('username', 'id');

        $this
            ->setupModel(new Post)
            ->setValidatorClass(PostRequest::class)
            ->withCustomFields()
            ->addCustomField('tags', TagField::class)
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label'      => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 4,
                    'placeholder'  => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('is_featured', 'onOff', [
                'label'         => trans('core/base::forms.is_featured'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('content', 'editor', [
                'label'      => trans('core/base::forms.content'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'            => 4,
                    'placeholder'     => trans('core/base::forms.description_placeholder'),
                    'with-short-code' => true,
                ],
            ])
	        ->add('pdf', 'mediaFile', [
		        'label'      => trans('plugins/blog::posts.form.pdf'),
		        'label_attr' => ['class' => 'control-label'],
	        ])
	        ->add('source', 'text', [
		        'label'      => __("Source"),
		        'label_attr' => ['class' => 'control-label'],
		        'attr'       => [
			        'data-counter' => 140,
		        ],
	        ])
	        ->add('source_link', 'text', [
		        'label'      => __("Source link"),
		        'label_attr' => ['class' => 'control-label'],
	        ])
	        ->add('link', 'text', [
		        'label'      => __("Link"),
		        'label_attr' => ['class' => 'control-label'],
	        ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => BaseStatusEnum::labels(),
            ])
	        ->add('published_at', 'text', [
		        'label'         => trans('plugins/blog::posts.form.published_at'),
		        'label_attr'    => ['class' => 'control-label'],
		        'attr'          => [
			        'class'            => 'form-control datepicker',
			        'data-date-format' => 'mm/dd/yyyy',
		        ],
		        'default_value' => now(config('app.timezone'))->format('m/d/Y'),
	        ])
	        ->add('type', 'customRadio', [
		        'label'      => trans('plugins/blog::posts.form.type'),
		        'label_attr' => ['class' => 'control-label'],
		        'choices'    => [
			        ['easy', 'Easy'],
			        ['moderate', 'Moderate'],
			        ['hard', 'Hard'],
			        ['pro', 'Pro'],
			        ['live', 'Live'],
			        ['club', 'Club'],
		        ],
	        ])
            ->add('categories[]', 'categoryMulti', [
                'label'      => trans('plugins/blog::posts.form.categories'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => get_categories_with_children(),
                'value'      => old('categories', $selectedCategories),
            ])
	        ->add('author_id', 'customSelect', [
		        'label'      => trans('plugins/blog::posts.author'),
		        'label_attr' => ['class' => 'control-label'],
		        'attr'       => [
			        'class' => 'form-control select-search-full',
		        ],
		        'choices'    => [0 => trans('plugins/blog::posts.author')] + $authors,
	        ])
	        ->add('stop_slide', 'text', [
		        'label'      => trans('plugins/blog::posts.form.stop_slide'),
		        'label_attr' => ['class' => 'control-label'],
		        'attr'       => [
			        'id'    => 'stop_slide',
			        'class' => 'form-control',
		        ],
	        ])
	        ->add('price', 'text', [
		        'label'      => trans('plugins/blog::posts.form.price'),
		        'label_attr' => ['class' => 'control-label'],
		        'attr'       => [
			        'id'    => 'price',
			        'class' => 'form-control input-mask-number',
		        ],
	        ])
	        ->add('sold_out', 'onOff', [
		        'label'         => trans('plugins/blog::posts.form.sold_out'),
		        'label_attr'    => ['class' => 'control-label'],
		        'default_value' => false,
	        ])
	        ->add('supply', 'text', [
		        'label'      => trans('plugins/blog::posts.form.supply'),
		        'label_attr' => ['class' => 'control-label'],
		        'attr'       => [
			        'id'    => 'supply',
			        'class' => 'form-control input-mask-number',
		        ],
	        ])
            ->add('image', 'mediaImage', [
                'label'      => trans('core/base::forms.image'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->add('tag', 'tags', [
                'label'      => trans('plugins/blog::posts.form.tags'),
                'label_attr' => ['class' => 'control-label'],
                'value'      => $tags,
                'attr'       => [
                    'placeholder' => trans('plugins/blog::base.write_some_tags'),
                    'data-url'    => route('tags.all'),
                ],
            ])
            ->setBreakFieldPoint('status');

        $postFormats = get_post_formats(true);

        if (count($postFormats) > 1) {
            $this->addAfter('status', 'format_type', 'customRadio', [
                'label'      => trans('plugins/blog::posts.form.format_type'),
                'label_attr' => ['class' => 'control-label'],
                'choices'    => get_post_formats(true),
            ]);
        }
    }
}
