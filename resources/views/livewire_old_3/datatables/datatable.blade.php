<div>
    @if($beforeTableSlot)
        <div class="mt-8">
            @include($beforeTableSlot)
        </div>
    @endif
    <div class="relative">
        <div class="sm:flex items-center justify-between mb-1">
            <div class="flex-1 mr-2 items-center h-10">
                @if($this->searchableColumns()->count())
                <div class="rounded-lg shadow-sm w-96">
                    <div class="relative focus-within:z-10">
                        <input wire:model.debounce.500ms="search" class="block w-full transition duration-150 ease-in-out rounded-md form-input form-control bg-gray-50 focus:bg-white sm:text-sm sm:leading-5" placeholder="{{__('Search in')}} {{ $this->searchableColumns()->map->label->join(', ') }}" />
                    </div>
                </div>
                @endif
            </div>
            <div class="flex justify-end text-gray-600">
                {{__('Results')}} {{ $this->results->firstItem() }} - {{ $this->results->lastItem() }} {{__('of')}}
                {{ $this->results->total() }}
            </div>
            <div class="flex items-center space-x-1">
                <x-icons.cog wire:loading class="text-gray-400 h-9 w-9 animate-spin" />

                @if($exportable)
                <div x-data="{ init() {
                    window.livewire.on('startDownload', link => window.open(link,'_blank'))
                } }" x-init="init">
                    <button wire:click="export" class="flex items-center px-3 space-x-2 text-xs font-medium leading-4 tracking-wider text-green-500 uppercase bg-white border border-green-400 rounded-md hover:bg-green-200 focus:outline-none"><span>{{ __('Export') }}</span>
                        <x-icons.excel class="m-2" /></button>
                </div>
                @endif

                @if($hideable === 'select')
                @include('datatables::hide-column-multiselect')
                @endif
            </div>
        </div>

        @if($hideable === 'buttons')
        <div class="grid grid-cols-8 gap-2 p-2">
            @foreach($this->columns as $index => $column)
            <button wire:click.prefetch="toggle('{{ $index }}')" class="px-3 py-2 rounded text-white text-xs focus:outline-none
            {{ $column['hidden'] ? 'bg-blue-100 hover:bg-blue-300 text-blue-600' : 'bg-blue-500 hover:bg-blue-800' }}">
                {{ $column['label'] }}
            </button>
            @endforeach
        </div>
        @endif

        <div class="overflow-x-auto  ">
            <div class="rounded-lg @unless($this->hidePagination) rounded-b-none @endif">
                <div class="table min-w-full align-middle">
                    @unless($this->hideHeader)
                    <div class="table-row divide-x divide-gray-200">
                        @foreach($this->columns as $index => $column)
                            @if($hideable === 'inline')
                                @include('datatables::header-inline-hide', ['column' => $column, 'sort' => $sort])
                            @elseif($column['type'] === 'checkbox')
                            <div class="flex justify-center w-32 px-6 py-4 overflow-hidden text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase align-top border-b border-gray-200 bg-gray-50 focus:outline-none">
                                <div class="px-3 py-1 rounded @if(count($selected)) bg-orange-400 @else bg-gray-200 @endif text-white text-center">
                                    {{ count($selected) }}
                                </div>
                            </div>
                            @else
                                @include('datatables::header-no-hide', ['column' => $column, 'sort' => $sort])
                            @endif
                        @endforeach
                    </div>

                    <div class="table-row bg-blue-100 divide-x divide-blue-200">
                        @foreach($this->columns as $index => $column)
                            @if($column['hidden'])
                                @if($hideable === 'inline')
                                    <div class="table-cell w-5 overflow-hidden align-top bg-blue-100"></div>
                                @endif
                            @elseif($column['type'] === 'checkbox')
                                <div class="flex flex-col items-center h-full px-6 py-5 space-y-2 overflow-hidden text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase align-top bg-blue-100 border-b border-gray-200 focus:outline-none">
                                    <div>SELECT ALL</div>
                                    <div>
                                        <input type="checkbox" wire:click="toggleSelectAll" @if(count($selected) === $this->results->total()) checked @endif class="w-4 h-4 mt-1 text-blue-600 transition duration-150 ease-in-out form-checkbox" />
                                    </div>
                                </div>
                            @else
                                <div class="table-cell overflow-hidden align-top">
                                    @isset($column['filterable'])
                                        @if( is_iterable($column['filterable']) )
                                            <div wire:key="{{ $index }}">
                                                @include('datatables::filters.select', ['index' => $index, 'name' => $column['label'], 'options' => $column['filterable']])
                                            </div>
                                        @else
                                            <div wire:key="{{ $index }}">
                                                @include('datatables::filters.' . ($column['filterView'] ?? $column['type']), ['index' => $index, 'name' => $column['label']])
                                            </div>
                                        @endif
                                    @endisset
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @endif
                    @forelse($this->results as $result)
                        <div class="table-row p-1 divide-x divide-gray-100 {{ isset($result->checkbox_attribute) && in_array($result->checkbox_attribute, $selected) ? 'bg-orange-100' : ($loop->even ? 'bg-gray-100' : 'bg-gray-50') }}">
                            @foreach($this->columns as $column)
                                @if($column['hidden'])
                                    @if($hideable === 'inline')
                                    <div class="table-cell w-5 overflow-hidden align-top"></div>
                                    @endif
                                @elseif($column['type'] === 'checkbox')
                                    @include('datatables::checkbox', ['value' => $result->checkbox_attribute])
                                @else
                                    <div class="px-6 py-2 whitespace-no-wrap text-sm leading-5 text-gray-900 table-cell @if($column['align'] === 'right') text-right @elseif($column['align'] === 'center') text-center @else text-left @endif">
                                        {!! $result->{$column['name']} !!}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @empty
                        <p class="p-3 text-lg text-teal-600">
                            {{ __("There's Nothing to show at the moment") }}
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
            @unless($this->hidePagination)
            <div class="bg-white border-b border-gray-200 rounded-lg rounded-t-none max-w-screen">
                <div class=" sticky bottom-0">
                    {{-- check if there is any data --}}
                    @if(count($this->results))
                        <div class="my-4 sm:my-0">
                            <div class="">
                                <span class="space-x-2">{{ $this->results->links('datatables::tailwind-simple-pagination') }}</span>
                            </div>

                            <div class="justify-center hidden lg:flex">
                                <span>{{ $this->results->links('datatables::tailwind-pagination') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-end text-gray-600">
                            {{__('Results')}} {{ $this->results->firstItem() }} - {{ $this->results->lastItem() }} {{__('of')}}
                            {{ $this->results->total() }}
                        </div>
                    @endif
                </div>
            </div>
            @endif
        
    </div>
    @if($afterTableSlot)
    <div class="mt-8">
        @include($afterTableSlot)
    </div>
    @endif
</div>
