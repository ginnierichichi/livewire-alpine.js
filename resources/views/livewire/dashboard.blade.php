<div class="p-4">
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day --}}
    <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
    <div class="py-4 space-y-4 ">
        <!---------- Top Bar ---------->
        <div class="flex justify-between">
            <div class="flex items-center">
                <x-input.text wire:model="filters.search" placeholder="Search Transactions..."></x-input.text>
                <x-button.link wire:click="$toggle('showFilters')" class="pl-4">@if($showFilters) Hide @endif Advanced Search...</x-button.link>
            </div>
            <div >
                <x-dropdown label="Bulk Actions">
                    <x-dropdown.item type="button" wire:click="exportSelected" class="space-x-2 flex justify-start">
                        <i class="fas fa-download text-gray-400"></i><span>Export</span>
                    </x-dropdown.item>

                    <x-dropdown.item type="button" wire:click="$toggle('showDeleteModal')" class="space-x-2 flex justify-start">
                        <i class="far fa-trash-alt text-gray-400"></i><span>Delete</span>
                    </x-dropdown.item>
                </x-dropdown>
                <x-button.primary wire:click="create"><i class="fas fa-plus-circle pr-2"></i>New</x-button.primary>
            </div>
        </div>
        <!---------- Advanced Search ---------->
        <div>
            <div>
                @if($showFilters)
                    <div class="bg-cool-gray-200 p-4 rounded shadow-inner flex relative">
                        <div class="w-1/2 pr-2 space-y-4">
                            <x-input.group inline for="filter-status" label="Status">
                                <x-input.select wire:model="filters.status" id="filter-status">
                                    <option value="" disabled>Select Status...</option>

                                    @foreach(App\Models\Transaction::STATUSES as $value => $label)
                                        <option value="{{ $value  }}">{{ $label }}</option>
                                    @endforeach
                                </x-input.select>
                            </x-input.group>

                            <x-input.group inline for="filter-amount-min" label="Minimum Amount">
                                <x-input.money wire:model.lazy="filters.amount-min" id="filter-amount-min" />
                            </x-input.group>

                            <x-input.group inline for="filter-amount-max" label="Maximum Amount">
                                <x-input.money wire:model.lazy="filters.amount-max" id="filter-amount-max" />
                            </x-input.group>
                        </div>
                        <div class="w-1/2 pl-2 space-y-4">
                            <x-input.group inline for="filter-date-min" label="Minimum Date">
                                <x-input.date wire:model="filters.date-max" id="filter-date-min" placeholder="DD/MM/YYYY" />
                            </x-input.group>

                            <x-input.group inline for="filter-date-max" label="Maximum Date">
                                <x-input.date wire:model="filters.date-min" id="filter-date-max" placeholder="DD/MM/YYYY" />
                            </x-input.group>

                            <x-button.link wire:click="resetFilters" class="absolute right-0 bottom-0 p-4">Reset Filters</x-button.link>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!---------- Transactions Table ---------->
        <x-table>
            <x-slot name="head">
                <x-table.heading class="pr-0 w-8"><x-input.checkbox  wire:model="selectPage"/></x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('title')" :direction="$sorts['title' ]?? null" class="pl-0">Title</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('amount')" :direction="$sorts['amount'] ?? null" >Amount</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('status')" :direction="$sorts['status'] ?? null" >Status</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('date')" :direction="$sorts['date'] ?? null" >Date</x-table.heading>
                <x-table.heading />
            </x-slot>

            <x-slot name="body">

                @if($selectPage)
                <x-table.row class="bg-gray-100" wire:key="row-message">
                    <x-table.cell colspan="6">
                        @unless($selectAll)
                            <span>You have selected <strong>{{ $transactions->count() }}</strong> transactions, do you want to select all <strong>{{ $transactions->total() }}</strong>?</span>
                            <x-button.link wire:click="selectAll" class="ml-1 hover:text-blue-700">Select All</x-button.link>
                        @else
                           <span>You have selected all <strong>{{ $transactions->total() }}</strong> transactions.</span>
                        @endif
                    </x-table.cell>
                </x-table.row>
                @endif
                @forelse ($transactions as $transaction)
                    <x-table.row wire:loading.class.delay="opacity-50" wire:key="row-{{ $transaction->id }}" >
                        <x-table.cell class="p-0 m-0 shadow-none" >
                            <x-input.checkbox wire:model="selected" value="{{ $transaction->id }}"/>
                        </x-table.cell>
                        <x-table.cell class="pl-0">
                            <div class="flex">
                                <a href="#" class="group inline-flex space-x-2 truncate text-sm">
                                    <!-- Heroicon name: cash -->
                                    <x-icon.cash class="text-cool-gray-400" />

                                    <p class="text-gray-700 truncate group-hover:text-gray-900 transition ease-in-out duration-150">
                                        {{$transaction->title}}
                                    </p>
                                </a>
                            </div>
                        </x-table.cell>

                        <x-table.cell>
                            <span class="text-gray-800 font-medium">£{{$transaction->amount}}</span> <span class="text-gray-600">GBP</span>
                        </x-table.cell>

                        <x-table.cell>
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $transaction->status_color }}-100 text-{{ $transaction->status_color }}-800 capitalize">
                               {{$transaction->status}}
                            </span>
                        </x-table.cell>

                        <x-table.cell>
                            {{$transaction->date_for_humans}}
                        </x-table.cell>

                        <x-table.cell>
                            <x-button.link wire:click="edit({{$transaction->id}})">Edit</x-button.link>
                        </x-table.cell>

                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell>
                            <x-icon.inbox />
                            <span> No transactions match your search.</span>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table>

        {{ $transactions->links()  }}
    </div>

    <!-------- DELETE TRANSACTIONS MODAL --------->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete Transaction</x-slot>
            <x-slot name="content">
                Are you sure you want to delete?
            </x-slot>
            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showDeleteModal', false)">Cancel</x-button.secondary>
                <x-button.primary type="submit">Delete</x-button.primary>
            </x-slot>
        </x-modal.confirmation>
    </form>

    <!-------- EDIT TRANSACTIONS MODAL --------->
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="showEditModal">
            <x-slot name="title">Edit Transaction</x-slot>

            <x-slot name="content">
                <x-input.group for="title" label="Title" :error="$errors->first('editing.title')">
                    <x-input.text wire:model="editing.title" id="title" placeholder="Title" />
                </x-input.group>

                <x-input.group for="amount" label="Amount" :error="$errors->first('editing.amount')">
                    <x-input.money wire:model="editing.amount" id="amount" />
                </x-input.group>

                <x-input.group for="status" label="Status" :error="$errors->first('editing.status')">
                    <x-input.select wire:model="editing.status" id="status" >
                        @foreach(App\Models\Transaction::STATUSES as $value=>$label)
                            <option value="{{$value}}">{{$label}}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>

                <x-input.group for="date_for_editing" label="Date" :error="$errors->first('editing.date_for_editing')">
                    <x-input.date wire:model="editing.date_for_editing" id="date_for_editing" />
                </x-input.group>
            </x-slot>
            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>
                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>
