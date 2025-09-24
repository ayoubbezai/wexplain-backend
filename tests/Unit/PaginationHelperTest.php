<?php
namespace Tests\Unit\Helpers;

use Tests\TestCase;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationHelperTest extends TestCase
{
    public function test_paginate_data_structure()
    {
        $items = collect([['id' => 1], ['id' => 2]]);
        $paginator = new LengthAwarePaginator($items, $items->count(), 1, 1);

        $result = formatPaginatedData($paginator);

        // Top-level data exists
        $this->assertArrayHasKey('data', $result);

        // Pagination key exists
        $this->assertArrayHasKey('pagination', $result);

        // Pagination subkeys exist
        $this->assertArrayHasKey('total_pages', $result['pagination']);
        $this->assertArrayHasKey('current_page', $result['pagination']);
        $this->assertArrayHasKey('total_items', $result['pagination']);
    }
}
