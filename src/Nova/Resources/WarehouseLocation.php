<?php

namespace Signalfire\Shopengine\Nova\Resources;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;

use Signalfire\Shopengine\Models\WarehouseLocation as Model;

class WarehouseLocation extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Model::class;

    /**
     * Hide in navigation.
     *
     * @var string
     */
    public static $displayInNavigation = false;

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
    public function title(){
        return $this->name . ' (' . $this->warehouse->name . ')';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'notes',
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
            BelongsTo::make('Warehouse'),
            Text::make(__('Name'), 'name')
                ->rules('required', 'max:100'),
            Textarea::make(__('Notes'), 'notes')
                ->nullable()
                ->rules('nullable', 'max:4000'),
            BelongsToMany::make('Variants')
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
