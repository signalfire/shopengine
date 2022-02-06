<?php

namespace Signalfire\Shopengine\Nova\Resources;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Signalfire\Shopengine\Nova\Actions\ChangePricesByPercentage;
use Signalfire\Shopengine\Nova\Actions\ChangePricesToSameAmount;
use Signalfire\Shopengine\Nova\Actions\ChangeVariantQuantity;
use Signalfire\Shopengine\Nova\Actions\ChangeVariantStatus;

class Variant extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Signalfire\Shopengine\Models\ProductVariant::class;

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
        return ucfirst($this->name);
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'barcode', 'name', 'slug', 'price',
    ];

    /**
     * Hide in navigation.
     *
     * @var string
     */
    public static $displayInNavigation = false;

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
            ID::make(__('ID'), 'id')
                ->hideFromIndex(),
            Images::make('Images', 'images'),
            Text::make(__('Barcode'), 'barcode')
                ->sortable()
                ->rules('required', 'min:13'),
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:200'),
            Slug::make(__('Slug'), 'slug')
                ->from('name')
                ->sortable()
                ->creationRules('required', 'max:200', 'unique:product_variants,slug')
                ->updateRules('required', 'max:200', 'unique:product_variants,slug,{{resourceId}}'),
            Number::make(__('Stock'), 'stock')
                ->sortable()
                ->rules('required', 'numeric'),
            Currency::make(__('Price'), 'price')
                ->sortable()
                ->rules('required', 'numeric'),
            Select::make('Status')->options(function () {
                $statuses = [];
                foreach (config('shopengine.variant.status') as $key => $value) {
                    $statuses[$value] = ucfirst(strtolower($key));
                }

                return $statuses;
            })
            ->displayUsingLabels()
            ->rules('required'),

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
        return [
            new ChangePricesByPercentage(),
            new ChangePricesToSameAmount(),
            new ChangeVariantStatus(),
            new ChangeVariantQuantity(),
        ];
    }
}
