<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.tailwind.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.tailwindcss.min.css">
</head>

<body class="bg-slate-50 font-[figtree]">
    <nav class="bg-white border-b border-slate-200 px-8 py-4 sticky top-0 z-50 shadow-sm">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-red-500 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Dashboard</h1>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="grid grid-cols-2 gap-5">
                    <div class="text-sm bg-red-300 text-black font-medium  px-4 py-1.5 rounded-full">
                        <a href="{{ route('home') }}">Home</a>
                    </div>
                    <div class="text-sm text-slate-500 font-medium bg-slate-100 px-4 py-1.5 rounded-full">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </div>
                </div>

                <div class="text-sm text-slate-500 font-medium bg-slate-100 px-4 py-1.5 rounded-full">
                    Last Sync: {{ now() }}
                </div>

            </div>
        </div>
    </nav>

    <main class="p-8 max-w-[1600px] mx-auto space-y-8">

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">

            <section
                class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Prediction Engine</h2>
                    <span class="flex items-center gap-2 text-xs text-green-600 font-bold">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> LIVE SYSTEM
                    </span>
                </div>
                <div class="flex-grow">
                    <iframe src="http://localhost:8502/?embed=true" class="w-full h-[750px] border-none"></iframe>
                </div>
            </section>


        </div>

    </main>


    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- TailwindCSS + Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>



</body>

</html>
