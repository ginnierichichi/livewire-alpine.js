<div class="p-4">
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day --}}
    <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
    <div class="py-4 space-y-4 ">
        <div class="w-1/4">
            <x-input.text wire:model="search" placeholder="Search Transactions..."></x-input.text>
        </div>
        <x-table>
            <x-slot name="head">
                <x-table.heading sortable>Title</x-table.heading>
                <x-table.heading sortable>Amount</x-table.heading>
                <x-table.heading sortable>Status</x-table.heading>
                <x-table.heading sortable>Date</x-table.heading>
            </x-slot>

            <x-slot name="body">
                @forelse ($transactions as $transaction)
                    <x-table.row wire:loading.class.delay="opacity-50">
                        <x-table.cell>
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
                            {{$transaction->date->format('d, M, Y')}}
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
</div>
