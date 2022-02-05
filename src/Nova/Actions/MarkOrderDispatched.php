<?php

namespace Signalfire\Shopengine\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkOrderDispatched extends Action
{
  use InteractsWithQueue, Queueable;

  /**
   * Perform the action on the given models.
   *
   * @param  \Laravel\Nova\Fields\ActionFields  $fields
   * @param  \Illuminate\Support\Collection  $models
   * @return mixed
   */
  public function handle(ActionFields $fields, Collection $models)
  {
    foreach ($models as $model) {
      $model->dispatched_at = now();
      $model->status = (int)config('shopengine.order.status.DISPATCHED');
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
    return [];
  }
}