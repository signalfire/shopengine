<?php

namespace Signalfire\Shopengine\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class OrderPrinted extends Filter
{
    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Http\Request              $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed                                 $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('printed', (bool) $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Printed'   => 1,
            'Unprinted' => 0,
        ];
    }
}
