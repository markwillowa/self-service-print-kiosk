@php
    use Illuminate\Support\Facades\Storage;
@endphp

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Maintenance Report</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        main,
        section,
        header,
        footer {
            break-inside: auto;
        }

        section,
        .print-block {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .print-text-box {
            break-inside: avoid;
            page-break-inside: avoid;
            white-space: pre-wrap;
            overflow-wrap: break-word;
            word-break: break-word;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            html,
            body {
                background: white !important;
                overflow: visible !important;
                height: auto !important;
            }

            body {
                padding: 0 !important;
            }

            main {
                max-width: none !important;
                width: 100% !important;
                box-shadow: none !important;
                padding: 0 !important;
            }

            section,
            footer,
            .print-block,
            .print-text-box {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            @page {
                size: letter;
                margin: 14mm;
            }
        }
    </style>
</head>

<body class="bg-slate-100 p-6">
<div class="no-print max-w-4xl mx-auto mb-4 flex justify-end">
    <button
        onclick="window.print()"
        class="rounded-xl bg-slate-950 text-white px-5 py-3 text-sm font-black"
    >
        Print Report
    </button>
</div>

<main class="max-w-4xl mx-auto bg-white p-8 shadow-xl text-slate-950">
    <header class="flex items-start justify-between border-b border-slate-300 pb-5 mb-6 gap-6">
        <div class="flex items-start gap-4 flex-1 min-w-0">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-black leading-tight">
                    {{ $maintenance->company?->name ?? 'Company Name' }}
                </h1>

                <p class="text-sm font-bold text-slate-700 leading-snug">
                    {{ $maintenance->company?->address ?? 'Company Address' }}
                </p>

                <p class="text-sm font-bold text-slate-700">
                    {{ $maintenance->company?->email ?? 'No email' }}
                </p>

                <p class="text-sm font-bold text-slate-700">
                    {{ $maintenance->company?->contact_number ?? 'No contact number' }}
                </p>
            </div>

            <div class="flex flex-col items-center shrink-0">
                @if ($maintenance->company?->avatar)
                    <img
                        src="{{ Storage::url($maintenance->company->avatar) }}"
                        alt="Company Logo"
                        class="w-20 h-20 object-contain"
                    >
                @else
                    <div class="w-20 h-20 flex items-center justify-center">
                        <x-heroicon-o-building-office-2 class="w-10 h-10 text-slate-500" />
                    </div>
                @endif

                <div class="text-center mt-2">
                    <div class="text-[10px] font-black uppercase text-slate-500">
                        Date
                    </div>

                    <div class="text-sm font-black">
                        {{ now()->format('M d, Y') }}
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="text-center mb-6 print-block">
        <h2 class="text-3xl font-black tracking-wide">
            MAINTENANCE REPORT
        </h2>
    </section>

    <section class="mb-6 print-block">
        <h3 class="text-lg font-black border-b border-slate-300 pb-2 mb-3">
            Organization Information
        </h3>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    School Name
                </div>

                <div class="font-bold">
                    {{ $maintenance->organization?->school_name ?? '-' }}
                </div>
            </div>

            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Contact Person
                </div>

                <div class="font-bold">
                    {{ $maintenance->organization?->contact_person ?? '-' }}
                </div>
            </div>

            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Email
                </div>

                <div class="font-bold">
                    {{ $maintenance->organization?->email ?? '-' }}
                </div>
            </div>

            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Contact Number
                </div>

                <div class="font-bold">
                    {{ $maintenance->organization?->contact_number ?? '-' }}
                </div>
            </div>
        </div>
    </section>

    <section class="mb-8 print-block">
        <h3 class="text-lg font-black border-b border-slate-300 pb-2 mb-3">
            Maintenance Information
        </h3>

        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Type
                </div>

                <div class="font-bold">
                    {{ $maintenance->maintenance_type }}
                </div>
            </div>

            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Status
                </div>

                <div class="font-bold">
                    {{ $maintenance->status }}
                </div>
            </div>

            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Performed At
                </div>

                <div class="font-bold">
                    {{ $maintenance->performed_at?->format('M d, Y') ?? '-' }}
                </div>
            </div>

            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Next Maintenance
                </div>

                <div class="font-bold">
                    {{ $maintenance->next_maintenance_at?->format('M d, Y') ?? '-' }}
                </div>
            </div>

            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Cost
                </div>

                <div class="font-bold">
                    ₱{{ $maintenance->cost }}
                </div>
            </div>

            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Staff
                </div>

                <div class="font-bold">
                    {{ session('admin_name') ?? $maintenance->admin?->name ?? 'Maintenance Staff' }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Printer Status
                </div>

                <div class="font-bold">
                    {{ $maintenance->printer_status ?? '-' }}
                </div>
            </div>

            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Coin Acceptor Status
                </div>

                <div class="font-bold">
                    {{ $maintenance->coin_acceptor_status ?? '-' }}
                </div>
            </div>

            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Paper Stock
                </div>

                <div class="font-bold">
                    {{ $maintenance->paper_stock ?? '-' }}
                </div>
            </div>

            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Ink Status
                </div>

                <div class="font-bold">
                    {{ $maintenance->ink_status ?? '-' }}
                </div>
            </div>

            <div>
                <div class="font-black text-slate-500 uppercase text-xs">
                    Network Status
                </div>

                <div class="font-bold">
                    {{ $maintenance->network_status ?? '-' }}
                </div>
            </div>
        </div>

        <div class="space-y-4 text-sm">
            <div class="print-block">
                <div class="font-black text-slate-500 uppercase text-xs mb-1">
                    Issue Reported
                </div>

                <div class="border rounded-xl p-3 min-h-[50px] font-bold print-text-box">
                    {{ $maintenance->issue_reported ?? '-' }}
                </div>
            </div>

            <div class="print-block">
                <div class="font-black text-slate-500 uppercase text-xs mb-1">
                    Action Taken
                </div>

                <div class="border rounded-xl p-3 min-h-[50px] font-bold print-text-box">
                    {{ $maintenance->action_taken ?? '-' }}
                </div>
            </div>

            <div class="print-block">
                <div class="font-black text-slate-500 uppercase text-xs mb-1">
                    Parts Replaced
                </div>

                <div class="border rounded-xl p-3 min-h-[45px] font-bold print-text-box">
                    {{ $maintenance->parts_replaced ?? '-' }}
                </div>
            </div>

            <div class="print-block">
                <div class="font-black text-slate-500 uppercase text-xs mb-1">
                    Notes
                </div>

                <div class="border rounded-xl p-3 min-h-[45px] font-bold print-text-box">
                    {{ $maintenance->notes ?? '-' }}
                </div>
            </div>
        </div>
    </section>

    <footer class="grid grid-cols-2 gap-16 pt-12 print-block">
        <div class="text-center">
            <div class="border-t border-slate-900 pt-2 font-black">
                Maintenance
            </div>
        </div>

        <div class="text-center">
            <div class="border-t border-slate-900 pt-2 font-black">
                School Representative
            </div>
        </div>
    </footer>
</main>
</body>
</html>
