<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
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
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">{{ $title }}</h1>
            </div>
            <div class="text-sm text-slate-500 font-medium bg-slate-100 px-4 py-1.5 rounded-full">
                Last Sync: {{ now()->format('H:i') }}
            </div>
        </div>
    </nav>

    <main class="p-8 max-w-[1600px] mx-auto space-y-8">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <section
                class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Prediction Engine</h2>
                    <span class="flex items-center gap-2 text-xs text-green-600 font-bold">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> LIVE SYSTEM
                    </span>
                </div>
                <div class="flex-grow">
                    <iframe src="http://localhost:8501/?embed=true" class="w-full h-[600px] border-none"></iframe>
                </div>
            </section>

            <section
                class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Analytics</h2>
                </div>

                <div class="grid grid-cols-1 p-3 lg:grid-cols-3 gap-6">
                    <div class="flex items-center gap-2 bg-blue-500/20 p-3 rounded-lg border border-blue-500/50">
                        
                        <span id="tenure-container" class="text-black text-sm font-medium">
                            Tenure Terakhir: <b id="tenure-value">{{ $tenure ?? 0 }}</b>
                        </span>
                    </div>

                    <div class="flex items-center gap-2 bg-blue-500/20 p-3 rounded-lg border border-blue-500/50">
                        
                        <span id="tenure-container" class="text-black text-sm font-medium">
                            online secure Terakhir: <b id="onsec-value">{{ $online_security ?? "" }}</b>
                        </span>
                    </div>

                     <div class="flex items-center gap-2 bg-blue-500/20 p-3 rounded-lg border border-blue-500/50">
                        
                        <span id="tenure-container" class="text-black text-sm font-medium">
                            Tech Support Terakhir: <b id="tech-value">{{ $tech_support ?? "" }}</b>
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 p-3 lg:grid-cols-2 gap-6">
                    <div class="flex items-center gap-2 bg-blue-500/20 p-3 rounded-lg border border-blue-500/50">
                        
                        <span id="tenure-container" class="text-black text-sm font-medium">
                            Hasil Prediksi: <b id="predict-value">{{ $prediction ?? "" }}</b>
                        </span>
                    </div>

                    <div class="flex items-center gap-2 bg-blue-500/20 p-3 rounded-lg border border-blue-500/50">
                        
                        <span id="tenure-container" class="text-black text-sm font-medium">
                            Hasil Klaster <b id="claster-value">{{ $cluster ?? "" }}</b>
                        </span>
                    </div>

                  
                </div>

                <div class="p-6 bg-gray-100 flex-grow flex flex-col justify-center min-h-[300px]">
                    <h3 class=" mb-4 text-center text-sm text-black font-bold">Distribusi Pelanggan per Cluster</h3>
                    <div class="relative h-[250px] w-full">
                        <canvas id="clusterChart"></canvas>
                    </div>

                    @if (!isset($clusters) || count($clusters) == 0)
                        <div class="text-gray-400 text-xs mt-4 text-center italic">
                            Data cluster belum tersedia. Selesaikan prediksi di samping.
                        </div>
                    @endif

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                    @if (isset($clusters) && count($clusters) > 0)
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Mengambil data dari variabel PHP $clusters
                                const clusterData = @json($clusters);

                                const labels = Object.keys(clusterData).map(c => 'Cluster ' + c);
                                const values = Object.values(clusterData);

                                const ctx = document.getElementById('clusterChart').getContext('2d');

                                new Chart(ctx, {
                                    type: 'pie', // Menggunakan Pie Chart sesuai keinginan Anda
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            label: 'Jumlah Pelanggan',
                                            data: values,
                                            backgroundColor: [
                                                '#dc2626', // Cluster 0
                                                '#ad1d1d', // Cluster 1
                                                '#921818', // Cluster 2
                                                '#5e0f0f' // Cluster 3
                                            ],
                                            borderWidth: 2,
                                            borderColor: '#111827'
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                display: true,
                                                position: 'bottom',
                                                labels: {
                                                    color: '#111827',
                                                    usePointStyle: true,
                                                    padding: 20,
                                                    font: {
                                                        size: 11
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            });
                        </script>
                    @endif
                </div>

            </section>

        </div>


        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Riwayat Prediksi</h2>
                    <p class="text-sm text-slate-500">Daftar pelanggan dan hasil analisis churn terbaru.</p>
                </div>
                <div id="customControls" class="flex flex-wrap gap-3"></div>
            </div>

            <div class="overflow-hidden">
                <table id="tblProduct" class="min-w-full text-sm text-left text-gray-700 dark:text-gray-100">
                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Tenure</th>
                            <th scope="col" class="px-6 py-3">Online Security</th>
                            <th scope="col" class="px-6 py-3">Tech Support</th>
                            <th scope="col" class="px-6 py-3">Cluster</th>
                            <th scope="col" class="px-6 py-3">Prediction</th>
                            <th scope="col" class="px-6 py-3">Probability No Churn</th>
                            <th scope="col" class="px-6 py-3">Probability Churn</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($dataPredict as $index => $items)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-3 font-medium text-gray-800 dark:text-gray-200">{{ $index + 1 }}
                                </td>

                                <td class="px-6 py-3 font-semibold">{{ $items['tenure'] }}</td>

                                <td class="px-6 py-3 font-semibold">{{ $items['online_security'] }}</td>
                                <td class="px-6 py-3 font-semibold">{{ $items['tech_support'] }}</td>
                                @foreach ($items->predicted_result as $result)
                                    <td class="px-6 py-3 font-semibold">{{ $result->cluster }}</td>
                                    <td class="px-6 py-3 font-semibold">{{ $result->prediction }}</td>
                                    <td class="px-4 py-4 text-right">

                                        @php
                                            $prob = $result->probability_no_churn;
                                            // Logika Hue Custom
                                            if ($prob <= 30) {
                                                $s = 0;
                                                $l = 20;
                                                $h = 0;
                                            } elseif ($prob <= 75) {
                                                // Hijau ke Biru
                                                $s = 70;
                                                $l = 45;
                                                // Kita geser hue dari 120 (Hijau) ke 180 (Biru)
                                                $h = 120 + ($prob - 30) * 1.5;
                                            } else {
                                                // Merah
                                                $s = 80;
                                                $l = 50;
                                                $h = 0; // Merah murni
                                            }
                                        @endphp

                                        <div class="flex flex-col items-end group">
                                            <span class="font-mono font-bold transition-all duration-500"
                                                style="color: hsl({{ $h }}, {{ $s }}%, {{ $l - 10 }}%);">
                                                {{ number_format($prob, 1) }}%
                                            </span>

                                            <div
                                                class="w-24 h-2 bg-slate-100 rounded-full mt-1 overflow-hidden shadow-inner">
                                                <div class="h-full transition-all duration-1000 ease-out"
                                                    style="width: {{ $prob }}%; 
                    background-color: hsl({{ $h }}, {{ $s }}%, {{ $l }}%);
                    box-shadow: 0 0 10px hsl({{ $h }}, {{ $s }}%, {{ $l }}%, 0.3);">
                                                </div>
                                            </div>

                                            @if ($prob > 75)
                                                <span
                                                    class="text-[9px] font-black text-red-500 mt-1 animate-pulse tracking-tighter">CRITICAL
                                                    RISK</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        @php
                                            $prob = $result->probability_churn;

                                            // Logika Hue Custom
                                            if ($prob <= 30) {
                                                // Hitam (Saturation 0% membuat warna apapun jadi abu-abu/hitam)
                                                $s = 0;
                                                $l = 20; // Gelap (Hitam)
                                                $h = 0;
                                            } elseif ($prob <= 75) {
                                                // Hijau ke Biru
                                                $s = 70;
                                                $l = 45;
                                                // Kita geser hue dari 120 (Hijau) ke 180 (Biru)
                                                $h = 120 + ($prob - 30) * 1.5;
                                            } else {
                                                // Merah
                                                $s = 80;
                                                $l = 50;
                                                $h = 0; // Merah murni
                                            }
                                        @endphp

                                        <div class="flex flex-col items-end group">
                                            <span class="font-mono font-bold transition-all duration-500"
                                                style="color: hsl({{ $h }}, {{ $s }}%, {{ $l - 10 }}%);">
                                                {{ number_format($prob, 1) }}%
                                            </span>

                                            <div
                                                class="w-24 h-2 bg-slate-100 rounded-full mt-1 overflow-hidden shadow-inner">
                                                <div class="h-full transition-all duration-1000 ease-out"
                                                    style="width: {{ $prob }}%; 
                    background-color: hsl({{ $h }}, {{ $s }}%, {{ $l }}%);
                    box-shadow: 0 0 10px hsl({{ $h }}, {{ $s }}%, {{ $l }}%, 0.3);">
                                                </div>
                                            </div>

                                            @if ($prob > 75)
                                                <span
                                                    class="text-[9px] font-black text-red-500 mt-1 animate-pulse tracking-tighter">CRITICAL
                                                    RISK</span>
                                            @endif
                                        </div>
                                    </td>
                                @endforeach

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </main>


    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- TailwindCSS + Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.tailwindcss.js"></script>


    <script src="https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.print.min.js"></script>

    <!-- Export dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>




    <script>

         setInterval(() => {
                fetch('/api/cluster-summary2')
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('tenure-value').innerText = data.tenure;
                        document.getElementById('onsec-value').innerText = data.online_security;
                        document.getElementById('tech-value').innerText = data.tech_support;
                        document.getElementById('prediction').innerText = data.prediction;
                        document.getElementById('claster').innerText = data.cluster;

                      
                    });
            }, 2000);
        $(document).ready(function() {


            let table = new DataTable('#tblProduct', {
                pageLength: 5,
                responsive: true,
                lengthMenu: [5, 10, 25, 50],
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'csvHtml5',
                                text: '<i class="fa-solid fa-file-csv mr-2"></i> CSV',
                                className: 'export-btn bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-md text-sm shadow-sm'
                            },
                            {
                                extend: 'excelHtml5',
                                text: '<i class="fa-solid fa-file-excel mr-2"></i> Excel',
                                className: 'export-btn bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-md text-sm shadow-sm'
                            },
                            {
                                extend: 'print',
                                text: '<i class="fa-solid fa-print mr-2"></i> Print',
                                className: 'export-btn bg-gray-700 hover:bg-gray-800 text-white px-3 py-1.5 rounded-md text-sm shadow-sm',
                                title: '', // kosongin biar gak dobel judul default
                                customize: function(win) {
                                    // Tambahkan logo dan heading di atas
                                    $(win.document.body)
                                        .css('font-family', 'Poppins, sans-serif')
                                        .prepend(`
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <h1 style="font-size: 20px; margin: 0;">Churn Predictor</h1>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 14px; margin: 0;">Daftar Produk Netafarm</p>
                        <p style="font-size: 12px; margin: 0;">Dicetak pada: ${new Date().toLocaleDateString()}</p>
                    </div>

                    </div>
                <hr style="border: 1px solid #ccc; margin-bottom: 20px;">
            `);

                                    // Styling tambahan (opsional)
                                    $(win.document.body).find('table')
                                        .addClass('compact')
                                        .css({
                                            'font-size': '12px',
                                            'width': '100%',
                                            'border-collapse': 'collapse'
                                        });

                                    $(win.document.body).find('table th')
                                        .css({
                                            'background-color': '#f1f1f1',
                                            'color': '#333',
                                            'padding': '6px',
                                            'border': '1px solid #ddd'
                                        });

                                    $(win.document.body).find('table td')
                                        .css({
                                            'padding': '6px',
                                            'border': '1px solid #ddd'
                                        });
                                }
                            }
                        ]
                    },
                    topEnd: function() {
                        return $(`
<div class="lg:flex justify-end hidden items-center gap-4">
    <!-- Pencarian -->
    <div class="flex items-center gap-2">
        <label for="tableSearch" class="text-sm text-gray-700 dark:text-gray-300">Cari:</label>
        <input id="tableSearch" type="text" 
            class="border dark:border-gray-600 rounded-md px-2 py-1 text-sm 
                   bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100"
            placeholder="Ketik untuk mencari...">
    </div>

<!-- Filter kategori -->
    <div class="flex items-center gap-2 relative">
        <label for="kategoriFilter" class="text-sm text-gray-700 dark:text-gray-300">Kategori:</label>
        <select id="kategoriFilter"
            class="appearance-none border dark:border-gray-600 rounded-md pl-2 pr-6 py-1 text-sm
                   bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100">
            <option value="">Semua</option>
            <option value="Churn">Churn</option>
            <option value="Tidak Churn">Tidak Churn</option>
        </select>
       
    </div>

    <!-- Page Length -->
    <div class="flex items-center gap-2 relative">
        <label for="pageLength" class="text-sm text-gray-700 dark:text-gray-300">Tampilkan:</label>
        <select id="pageLength"
            class="appearance-none border dark:border-gray-600 rounded-md pl-2 pr-6 py-1 text-sm
                   bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
        </select>
        
    </div>
</div>


                `)[0];
                    },
                    bottomStart: 'info',
                    bottomEnd: 'paging'
                },
                language: {
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "← Sebelumnya",
                        next: "Berikutnya →"
                    },
                    emptyTable: "Tidak ada data tersedia"
                }
            });


            // 🔍 Search custom
            $('#tableSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('#pageLength').on('change', function() {
                table.page.len(parseInt(this.value)).draw();
            });
            // Filter kategori
            $('#kategoriFilter').on('change', function() {
                table.column(4).search(this.value).draw(); // kolom kategori index 4
            });

            // === Conditional formatting: highlight baris dengan harga tertinggi ===
            function highlightMaxRow() {
                let max = 0,
                    maxIndex = -1;
                table.rows({
                    search: 'applied'
                }).every(function(rowIdx, tableLoop, rowLoop) {
                    const harga = parseFloat(this.data()[3]);
                    if (harga > max) {
                        max = harga;
                        maxIndex = rowIdx;
                    }
                });
                // Hapus highlight sebelumnya
                $('#userTable tbody tr').removeClass('highlight-max');
                if (maxIndex >= 0) {
                    $(table.row(maxIndex).node()).addClass('highlight-max');
                }
            }

            // Jalankan pertama kali & setiap update
            highlightMaxRow();
            table.on('draw', highlightMaxRow);


        });

        function filterTable(category) {
            const rows = document.querySelectorAll("#table-body tr");
            rows.forEach(row => {
                const rowCategory = row.getAttribute("data-kategori");
                if (category === "Semua" || rowCategory === category) {
                    row.style.display = ""; // Tampilkan baris
                } else {
                    row.style.display = "none"; // Sembunyikan baris
                }
            });

            // Update tombol aktif
            const buttons = document.querySelectorAll("button");
            buttons.forEach(button => button.classList.remove("btn-success"));
            buttons.forEach(button => button.classList.add("btn-outline-secondary"));

            const activeButton = [...buttons].find(btn => btn.textContent === category);
            if (activeButton) {
                activeButton.classList.remove("btn-outline-secondary");
                activeButton.classList.add("btn-success");
            }
        }

        const items = json($items);

        // Prepare data for Pie Chart
        const labels = items.map(item => item.item);
        const data = items.map(item => item.item_sold);

        const ctx = document.getElementById('pieChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Item Sold',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const value = tooltipItem.raw;
                                return `${tooltipItem.label}: ${value} items`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
