<?php

namespace App\Http\Livewire\Tables;

use App\Models\Color;
use App\Models\Transaction;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class AdoptionsTable extends LivewireDatatable
{
    public function builder()
    {
        return Color::query();
    }

    public function columns()
    {
        return [
            Column::name('created_at')
        ];
    }
}
