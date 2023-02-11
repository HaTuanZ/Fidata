<?php

namespace Botble\MarcoEvent\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\MarcoEvent\Http\Requests\MarcoEventRequest;
use Botble\MarcoEvent\Models\MarcoEvent;

class MarcoEventForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new MarcoEvent)
            ->setValidatorClass(MarcoEventRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('event_date', 'text', [
                'label'      => trans('plugins/marco-event::marco-event.event_date'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-3',
                ],
                'attr'          => [
                    'class'            => 'form-control datepicker',
                    'data-date-format' => 'yyyy-mm-dd',
                ],
            ])
            ->add('event_time', 'time', [
                'label'      => trans('plugins/marco-event::marco-event.event_time'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-3',
                ],
            ])
            ->add('color', 'color', [
                'label'      => __('Color'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-3',
                ],
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('actual', 'number', [
                'label'      => trans('plugins/marco-event::marco-event.actual'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-3',
                ],
            ])
            ->add('forecast', 'number', [
                'label'      => trans('plugins/marco-event::marco-event.forecast'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-3',
                ],
            ])
            ->add('previous', 'number', [
                'label'      => trans('plugins/marco-event::marco-event.previous'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-3',
                ],
            ])
            ->add('type', 'select', [
                'label'      => trans('plugins/marco-event::marco-event.type'),
                'label_attr' => ['class' => 'control-label'],
                'choices'    => [
                    "%" => __('Percentage'),
                    "" => __('Number'),
                ],
                'wrapper'    => [
                    'class' => 'form-group mb-3 col-md-3',
                ],
            ])
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])
            ->add('image', 'mediaImage', [
                'label'      => __('Image'),
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
            ->setBreakFieldPoint('image');
    }
}
