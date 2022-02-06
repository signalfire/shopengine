<?php

namespace Signalfire\Shopengine\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Currency;

class ChangePricesToSameAmount extends Action implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection    $models
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $model->price = $fields->amount;
            $model->save();
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Currency::make('Amount')
                ->rules('required', 'numeric'),
        ];
    }
}
