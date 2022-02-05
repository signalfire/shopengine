<?php

namespace Signalfire\Shopengine\Nova\Resources;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Signalfire\Shopengine\Models\Product as Model;
use Signalfire\Shopengine\Nova\Actions\ChangeProductStatus;

class Product extends Resource
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
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'slug',
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
            ID::make(__('ID'), 'id')
                ->hideFromIndex(),
            Images::make('Images', 'images')
                ->conversionOnIndexView('thumb'),
            Text::make(__('Name'), 'name')
                ->rules('required', 'max:200')
                ->sortable(),
            Slug::make(__('Slug'), 'slug')
                ->sortable()
                ->creationRules('required', 'max:200', 'unique:products,slug')
                ->updateRules('required', 'max:200', 'unique:products,slug,{{resourceId}}'),
            Select::make('Status')->options(function () {
                $statuses = [];
                foreach (config('shopengine.product.status') as $key => $value) {
                    $statuses[$value] = ucfirst(strtolower($key));
                }

                return $statuses;
            })
            ->displayUsingLabels()
            ->rules('required'),
            HasMany::make('Categories'),
            HasMany::make('Variants'),
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
            new ChangeProductStatus
        ];
    }
}
