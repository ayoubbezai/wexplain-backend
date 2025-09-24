<?php

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

if (! function_exists('formatPaginatedData')) {
    /**
     * Format a paginated Laravel collection for API response.
     *
     * @param LengthAwarePaginator $paginator
     * @param string|null $resourceClass Optional JSON resource class
     * @return array
     */
    function formatPaginatedData(LengthAwarePaginator $paginator, ?string $resourceClass = null): array
    {
        return [
            'data'         => $resourceClass ? $resourceClass::collection($paginator->items()) : $paginator->items(),
            'pagination'=>[
                'total_pages'  => $paginator->lastPage(),
                'current_page' => $paginator->currentPage(),
                'total_items'  => $paginator->total(),
            ]
        ];
    }
}

