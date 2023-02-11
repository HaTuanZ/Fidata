<?php

namespace Botble\Gem\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Gem\Http\Requests\GemRequest;
use Botble\Gem\Models\Gem;

class GemForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Gem)
            ->setValidatorClass(GemRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
	        ->add('paid', 'number', [
		        'label'      => trans('plugins/gem::gem.paid'),
		        'label_attr' => ['class' => 'control-label required']
	        ])
	        ->add('bonus', 'number', [
		        'label'      => trans('plugins/gem::gem.bonus'),
		        'label_attr' => ['class' => 'control-label']
	        ])
	        ->add('usdt', 'number', [
		        'label'      => trans('plugins/gem::gem.usdt'),
		        'label_attr' => ['class' => 'control-label']
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
