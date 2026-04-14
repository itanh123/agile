<?php

namespace Database\Seeders\Concerns;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

trait BuildsTableRows
{
    protected function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }

    protected function now(): Carbon
    {
        return Carbon::now();
    }

    protected function hasColumn(string $table, string $column): bool
    {
        return Schema::hasColumn($table, $column);
    }

    protected function fitToTable(string $table, array $row): array
    {
        $columns = Schema::getColumnListing($table);

        return array_filter(
            $row,
            static fn ($value, $column) => in_array($column, $columns, true),
            ARRAY_FILTER_USE_BOTH
        );
    }

    protected function withTimestamps(string $table, array $row, ?Carbon $time = null): array
    {
        $time ??= $this->now();

        return $this->fitToTable($table, array_merge([
            'created_at' => $time,
            'updated_at' => $time,
        ], $row));
    }
}
