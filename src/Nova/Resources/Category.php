<?php

namespace Signalfire\Shopengine\Nova\Resources;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Signalfire\Shopengine\Models\Category as Model;

class Category extends Resource
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
    public static $title = 'name';

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
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:100'),
            Textarea::make(__('Description'), 'description')
                ->rules('nullable', 'max:4000')
                ->nullable()
                ->onlyOnForms(),
            Slug::make(__('Slug'), 'slug')
                ->sortable()
                ->from('name')
                ->creationRules('required', 'max:100', 'unique:categories,slug')
                ->updateRules('required', 'max:100', 'unique:categories,slug,{{resourceId}}'),
            Select::make('Status')
                ->options(function () {
                    $statuses = [];
                    foreach (config('shopengine.category.status') as $key => $value) {
                        $statuses[$value] = ucfirst(strtolower($key));
                    }

                    return $statuses;
                })
                ->displayUsingLabels()
                ->rules('required'),
            HasMany::make('Products'),
            DateTime::make('Created At')
                ->showOnIndex()
                ->showOnDetail()
                ->exceptOnForms(),

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
