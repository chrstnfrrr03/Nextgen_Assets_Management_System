<x-app-layout>

    <div class="space-y-10">

        <!-- HEADER -->
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Reports & Analytics
            </h1>
            <p class="text-sm text-gray-500">
                Overview of your asset data and system insights
            </p>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            <div class="p-6 bg-white shadow rounded-2xl">
                <p class="text-sm text-gray-500">Total Assets</p>
                <h2 class="text-2xl font-bold text-slate-800">
                    {{ \App\Models\Item::count() }}
                </h2>
            </div>

            <div class="p-6 bg-white shadow rounded-2xl">
                <p class="text-sm text-gray-500">Total Brands</p>
                <h2 class="text-2xl font-bold text-slate-800">
                    {{ \App\Models\Item::distinct('brand')->count('brand') }}
                </h2>
            </div>

            <div class="p-6 bg-white shadow rounded-2xl">
                <p class="text-sm text-gray-500">Users</p>
                <h2 class="text-2xl font-bold text-slate-800">
                    {{ \App\Models\User::count() }}
                </h2>
            </div>

        </div>

        <!-- RECENT ACTIVITY -->
        <div class="p-6 bg-white shadow rounded-2xl">

            <h3 class="mb-4 font-semibold text-gray-700">
                Recent Assets Activity
            </h3>

            <div class="space-y-3">

                @foreach(\App\Models\Item::latest()->take(5)->get() as $item)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">

                        <div>
                            <p class="font-medium text-gray-800">
                                {{ $item->part_name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $item->brand }}
                            </p>
                        </div>

                        <span class="text-xs text-gray-400">
                            {{ $item->created_at->diffForHumans() }}
                        </span>

                    </div>
                @endforeach

            </div>

        </div>

    </div>

</x-app-layout>