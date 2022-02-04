<?php

namespace Signalfire\Shopengine\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Signalfire\Shopengine\Models\Order as Model;

class Order extends Resource
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
        return $this->cardholder->title.' '.$this->cardholder->forename.' '.$this->cardholder->surname.' ('.$this->id.')';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'total',
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
            BelongsTo::make('Cardholder Address', 'cardholder', 'Signalfire\Shopengine\Nova\Address'),
            BelongsTo::make('Delivery Address', 'delivery', 'Signalfire\Shopengine\Nova\Address'),
            Currency::make(__('Total'), 'total')->sortable()->rules('required', 'numeric'),
            Boolean::make(__('Gift'), 'gift'),
            Boolean::make(__('Terms'), 'terms')->rules('required'),
            Boolean::make(__('Printed'), 'printed'),
            Select::make('Status')->options(function () {
                $statuses = [];
                foreach (config('shopengine.order.status') as $key => $value) {
                    $statuses[$value] = ucfirst(strtolower($key));
                }

                return $statuses;
            })->displayUsingLabels()->rules('required'),
            DateTime::make('Dispatched At')->rules('nullable'),
            HasMany::make('Items'),
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
