<?php

namespace App\Http\Livewire\Tables;

use App\Models\Package;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PackagesTable extends LivewireDatatable
{
    public function builder()
    {
    return Package::orderBy('order');
       // return Package::query();
    }

    public function columns()
    {
        return [
         Column::name('name')->searchable(),
            Column::callback(['paddle_id_monthly', 'amount_monthly'], function($paddle_id_monthly, $amount_monthly) {
                $lines = [];
                if ($paddle_id_monthly)
                    $lines[] = 'ID: '.$paddle_id_monthly;
                else if ($amount_monthly)
                    $lines[] = 'Amount: '.$amount_monthly;

                if (count($lines) > 0)
                    return implode('<br>', $lines);
                
                return 'N/A';
            })->label('Monthly'),
            Column::callback(['paddle_id_annually', 'amount_annually'], function($paddle_id_annually, $amount_annually) {
                $lines = [];
                if ($paddle_id_annually)
                    $lines[] = 'ID: '.$paddle_id_annually;
                else if ($amount_annually)
                    $lines[] = 'Amount: '.$amount_annually;

                if (count($lines) > 0)
                    return implode('<br>', $lines);

                return 'N/A';
            })->label('Annually'),
      //      Column::name('name')->label('Name'),
            BooleanColumn::name('active')->label('Active'),
            Column::callback(['id'], function($id) {
                return '<a href='.url('/').'/packages/edit?package_id=' . $id . '><i class="badge-circle badge-circle-light-secondary bx bx-edit font-medium-1"></i></a>'.'<a href='.url('/').'/packages/delete?package_id=' . $id . '><i class="badge-circle badge-circle-light-secondary bx bx-trash font-medium-1"></i></a>';
             })->label('Action'),
        ];
    }
}
