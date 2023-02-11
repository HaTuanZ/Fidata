<?php

namespace Botble\Coin\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Coin\Http\Requests\CoinRequest;
use Botble\Coin\Models\Coin;
use Assets;

class CoinForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        Assets::addScriptsDirectly([
                'themes/unitok/js/coins-scroll-to.js?v=4',
            ])->addStylesDirectly('themes/unitok/css/coins-scroll-to.css?v=2');

        $this
            ->setupModel(new Coin)
            ->setValidatorClass(CoinRequest::class)
            ->withCustomFields()

            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
                'wrapper'    => [
                    'class' => 'form-group col-lg-3 col-md-4 col-xs-6',
                ],
            ])
            ->add('symbol', 'text', [
                'label'      => __("Symbol"),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-lg-3 col-md-4 col-xs-6',
                ],
            ])
            ->add('coin_id', 'text', [
                'label'      => __('Coin ID'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-lg-3 col-md-4 col-xs-6',
                ],
            ])
            ->add('stake', 'number', [
                'label'      => __('Stake'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-lg-3 col-md-4 col-xs-6',
                ],
            ])
            ->add('token_release', 'number', [
                'label'      => __('Token release'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-lg-3 col-md-4 col-xs-6',
                ],
            ])
            ->add('block_rewards', 'number', [
                'label'      => __('Block rewards'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-lg-3 col-md-4 col-xs-6',
                ],
            ])
            ->add('burn', 'number', [
                'label'      => __('Burn'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-lg-3 col-md-4 col-xs-6',
                ],
            ])
            ->add('rebated', 'number', [
                'label'      => __('Rebated'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-lg-3 col-md-4 col-xs-6',
                ],
            ])
            ->add('initial_supply', 'text', [
                'label'      => __('Initial supply'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-lg-3 col-md-4 col-xs-6',
                ],
            ])
            ->add('investors1', 'text', [
                'label'      => __('Investors'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-lg-3 col-md-4 col-xs-6',
                ],
            ])
            ->add('investors2', 'text', [
                'label'      => __('Organization or founders	'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-lg-3 col-md-4 col-xs-6',
                ],
            ])
            ->add('investors3', 'text', [
                'label'      => __('Premined rewards or airdrops_percentage	'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-lg-3 col-md-4 col-xs-6',
                ],
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])

            ->add('allocation', 'editor', [
                'label'      => __('Allocation'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'with-short-code' => false, // if true, it will add a button to select shortcode
                    'without-buttons' => false, // if true, all buttons will be hidden
                ],
            ])

            ->add('launch_style', 'text', [
                'label'      => __('Launch style'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-lg-3 col-md-4 col-xs-6',
                ],
            ])
            ->add('launch_details', 'editor', [
                'label'      => __('Launch details'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'with-short-code' => false, // if true, it will add a button to select shortcode
                    'without-buttons' => false, // if true, all buttons will be hidden
                ],
            ])
            ->add('performance', 'editor', [
                'label'      => __('Performance'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'with-short-code' => false, // if true, it will add a button to select shortcode
                    'without-buttons' => false, // if true, all buttons will be hidden
                ],
            ])
            ->add('release_schedule', 'editor', [
                'label'      => __('Release schedule'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'with-short-code' => false, // if true, it will add a button to select shortcode
                    'without-buttons' => false, // if true, all buttons will be hidden
                ],
            ])
            ->add('fund_raising', 'repeater', [
                'label'      => __('Fund raising'),
                'label_attr' => ['class' => 'control-label'],
                'attr'          => [
                    'class'  => 'fund-raising',
                ],
                'fields' => [
                    [
                        'type'       => 'text',
                        'label'      => __('Title'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'title',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 140,
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Date'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'date',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control datepicker',
                                'data-date-format' => 'yyyy-mm-dd',
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Price'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'price',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 25,
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Raised'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'raised',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 25,
                            ],
                        ],
                    ],
                ],
            ])

            ->add('technology', 'editor', [
                'label'      => __('Technology'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'with-short-code' => false, // if true, it will add a button to select shortcode
                    'without-buttons' => false, // if true, all buttons will be hidden
                ],
            ])
            ->add('ecosystem', 'repeater', [
                'label'      => __('Ecosystem'),
                'label_attr' => ['class' => 'control-label'],
                'attr'          => [
                    'class'  => 'ecosystem',
                ],
                'fields' => [
                    [
                        'type' => 'mediaImage',
                        'label'      => __('Image'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'image',
                            'value'   => null,
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Text'),
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
                    [
                        'type'       => 'text',
                        'label'      => __('Address'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'address',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 255,
                            ],
                        ],
                    ],
                ],
            ])
            ->add('team_backers', 'repeater', [
                'label'      => __('Team backers'),
                'label_attr' => ['class' => 'control-label'],
                'attr'          => [
                    'class'  => 'ecosystem',
                ],
                'fields' => [
                    [
                        'type' => 'mediaImage',
                        'label'      => __('Image'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'image',
                            'value'   => null,
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Name'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'name',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 140,
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Position'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'position',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 255,
                            ],
                        ],
                    ],
                ],
            ])
            ->add('investors', 'repeater', [
                'label'      => __('Investors'),
                'label_attr' => ['class' => 'control-label'],
                'attr'          => [
                    'class'  => 'ecosystem',
                ],
                'fields' => [
                    [
                        'type' => 'mediaImage',
                        'label'      => __('Image'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'image',
                            'value'   => null,
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Name'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'name',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 140,
                            ],
                        ],
                    ],
                    [
                        'type'       => 'textarea',
                        'label'      => __('Description'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'description',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 255,
                                'rows' => 2,
                            ],
                        ],
                    ],
                ],
            ])
            ->add('community', 'repeater', [
                'label'      => __('Community'),
                'label_attr' => ['class' => 'control-label'],
                'attr'          => [
                    'class'  => 'ecosystem',
                ],
                'fields' => [
                    [
                        'type'       => 'text',
                        'label'      => __('Name'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'name',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 25,
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Value'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'value',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 25,
                            ],
                        ],
                    ],
                ],
            ])
            ->add('orientation', 'editor', [
                'label'      => __('Orientation'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'with-short-code' => false, // if true, it will add a button to select shortcode
                    'without-buttons' => false, // if true, all buttons will be hidden
                ],
            ])
            ->add('roadmap', 'repeater', [
                'label'      => __('Roadmap'),
                'label_attr' => ['class' => 'control-label'],
                'attr'          => [
                    'class'  => 'roadmap',
                ],
                'fields' => [
                    [
                        'type'       => 'text',
                        'label'      => __('Title'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'title',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 140,
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Date'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'date',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 25,
                            ],
                        ],
                    ],
                    [
                        'type'       => 'text',
                        'label'      => __('Type'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'type',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 25,
                            ],
                        ],
                    ],
                    [
                        'type'       => 'textarea',
                        'label'      => __('Details'),
                        'label_attr' => ['class' => 'control-label'],
                        'attributes' => [
                            'name'    => 'details',
                            'value'   => null,
                            'options' => [
                                'class'        => 'form-control',
                                'data-counter' => 255,
                                'rows' => 3,
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
            ->add('scroll_to', 'static', [
                'tag' => 'div', // Tag to be used for holding static data,
                'attr' => ['class' => 'form-control-static scroll-to'], // This is the default
                'value' => '' // If nothing is passed, data is pulled from model if any
            ])
            ->setBreakFieldPoint('status');
    }
}
