<?php

namespace Signalfire\Shopengine\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Signalfire\Shopengine\Models\Address as Model;

class Address extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Model::class;

    /**
     * The resource group.
     *
     * @var string
     */
    public static $group = 'Shopengine';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->title.' '.$this->forename.' '.$this->surname;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'forename', 'surname', 'postalcode', 'mobile', 'phone', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->hideFromIndex(),
            Select::make('Title')->options([
                'Mr'   => 'Mr',
                'Mrs'  => 'Mrs',
                'Ms'   => 'Ms',
                'Dr'   => 'Dr',
                'Prof' => 'Prof',
                'Sir'  => 'Sir',
            ]),
            Text::make(__('Forename'), 'forename')
                ->sortable()
                ->rules('required', 'max:50'),
            Text::make(__('Surname'), 'surname')
                ->sortable()
                ->rules('required', 'max:50'),
            Text::make(__('Address 1'), 'address1')
                ->sortable()
                ->rules('required', 'max:50'),
            Text::make(__('Address 2'), 'address2')
                ->hideFromIndex()
                ->rules('nullable', 'max:50'),
            Text::make(__('Address 3'), 'address3')
                ->hideFromIndex()
                ->rules('nullable', 'max:50'),
            Text::make(__('Town/City'), 'towncity')
                ->hideFromIndex()
                ->rules('required', 'max:50'),
            Text::make(__('County'), 'county')
                ->sortable()
                ->rules('required', 'max:50'),
            Text::make(__('Postal Code'), 'postalcode')
                ->sortable()
                ->rules('required', 'max:50'),
            Text::make(__('Country'), 'country')
                ->sortable()
                ->rules('required', 'max:50'),
            Text::make(__('Mobile'), 'mobile')
                ->sortable()
                ->rules('nullable', 'max:30'),
            Text::make(__('Phone'), 'phone')
                ->sortable()
                ->rules('nullable', 'max:30'),
            Text::make(__('Email'), 'email')
                ->sortable()
                ->rules('required', 'email', 'max:255'),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
