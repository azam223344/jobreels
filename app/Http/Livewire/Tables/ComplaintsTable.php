<?php

namespace App\Http\Livewire\Tables;

use App\Models\Complaint;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ComplaintsTable extends LivewireDatatable
{
    public function builder()
    {
        return Complaint::query();
    }

    public function columns()
    {
        return [
            Column::name('user.name'),
            Column::name('description'),
            DateColumn::name('created_at')->label('Date')
        ];
    }
}
