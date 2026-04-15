@extends('layouts.backend.app')

@section('content')
    @if (session('success'))
        <div id="floating-alert" class="toast-custom">
            <div class="toast-content">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Selamat Datang, {{ Auth::user()->name }}!</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Admin</a></div>
                    <div class="breadcrumb-item">Dashboard</div>
                </div>
            </div>

            <div class="section-body">
                {{-- <h2 class="section-title">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <p class="section-lead">Anda masuk sebagai <strong>{{ strtoupper(Auth::user()->role) }}</strong>. Kelola
                    data perpustakaan dengan mudah di sini.</p> --}}

                {{-- STATISTIK RINGKAS --}}
                <div class="row mt-4" style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">

                    {{-- Total Anggota --}}
                    <div
                        style="background:#16213e;border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:1.25rem;display:flex;flex-direction:column;gap:10px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <span
                                style="font-size:11px;color:rgba(255,255,255,0.38);text-transform:uppercase;letter-spacing:.06em;font-weight:600;">Total
                                Anggota</span>
                            <div
                                style="width:32px;height:32px;border-radius:8px;background:rgba(55,138,221,0.15);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-users" style="font-size:13px;color:#378ADD;"></i>
                            </div>
                        </div>
                        <div style="font-size:36px;font-weight:600;color:#fff;line-height:1;">{{ $totalAnggota }}</div>
                        <div style="height:3px;background:rgba(255,255,255,0.07);border-radius:2px;overflow:hidden;">
                            <div style="width:60%;height:100%;background:#378ADD;border-radius:2px;"></div>
                        </div>
                        <span style="font-size:12px;color:rgba(255,255,255,0.28);">Anggota aktif terdaftar</span>
                    </div>

                    {{-- Total Petugas --}}
                    <div
                        style="background:#16213e;border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:1.25rem;display:flex;flex-direction:column;gap:10px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <span
                                style="font-size:11px;color:rgba(255,255,255,0.38);text-transform:uppercase;letter-spacing:.06em;font-weight:600;">Total
                                Petugas</span>
                            <div
                                style="width:32px;height:32px;border-radius:8px;background:rgba(29,158,117,0.15);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-user-shield" style="font-size:13px;color:#1D9E75;"></i>
                            </div>
                        </div>
                        <div style="font-size:36px;font-weight:600;color:#fff;line-height:1;">{{ $totalPetugas }}</div>
                        <div style="height:3px;background:rgba(255,255,255,0.07);border-radius:2px;overflow:hidden;">
                            <div style="width:25%;height:100%;background:#1D9E75;border-radius:2px;"></div>
                        </div>
                        <span style="font-size:12px;color:rgba(255,255,255,0.28);">Petugas yang bertugas</span>
                    </div>

                    {{-- Total Denda --}}
                    <div
                        style="background:#16213e;border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:1.25rem;display:flex;flex-direction:column;gap:10px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <span
                                style="font-size:11px;color:rgba(255,255,255,0.38);text-transform:uppercase;letter-spacing:.06em;font-weight:600;">Total
                                Denda</span>
                            <div
                                style="width:32px;height:32px;border-radius:8px;background:rgba(239,159,39,0.15);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-money-bill-wave" style="font-size:13px;color:#EF9F27;"></i>
                            </div>
                        </div>
                        <div style="font-size:20px;font-weight:600;color:#fff;line-height:1.3;">Rp
                            {{ number_format($totalDenda, 0, ',', '.') }}</div>
                        <div style="height:3px;background:rgba(255,255,255,0.07);border-radius:2px;overflow:hidden;">
                            <div style="width:70%;height:100%;background:#EF9F27;border-radius:2px;"></div>
                        </div>
                        <span style="font-size:12px;color:rgba(255,255,255,0.28);">Total denda terkumpul</span>
                    </div>

                    {{-- Buku Hampir Habis --}}
                    <div
                        style="background:#16213e;border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:1.25rem;display:flex;flex-direction:column;gap:10px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <span
                                style="font-size:11px;color:rgba(255,255,255,0.38);text-transform:uppercase;letter-spacing:.06em;font-weight:600;">Stok
                                Hampir Habis</span>
                            <div
                                style="width:32px;height:32px;border-radius:8px;background:rgba(226,75,74,0.15);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-exclamation-triangle" style="font-size:13px;color:#f87171;"></i>
                            </div>
                        </div>
                        <div style="display:flex;align-items:baseline;gap:8px;">
                            <span
                                style="font-size:36px;font-weight:600;color:#fff;line-height:1;">{{ $bukuHampirHabis }}</span>
                            <span
                                style="font-size:11px;background:rgba(226,75,74,0.18);color:#f87171;padding:2px 8px;border-radius:20px;font-weight:600;">Stok
                                ≤ 5</span>
                        </div>
                        <div style="height:3px;background:rgba(255,255,255,0.07);border-radius:2px;overflow:hidden;">
                            <div style="width:40%;height:100%;background:#f87171;border-radius:2px;"></div>
                        </div>
                        <span style="font-size:12px;color:rgba(255,255,255,0.28);">Buku perlu restok segera</span>
                    </div>

                </div>

                {{-- CHARTS ROW --}}
                <div class="row mt-3">
                    {{-- LINE CHART --}}
                    <div class="col-lg-8 col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-0">Aktivitas Peminjaman</h4>
                                    <small class="text-muted">7 hari terakhir</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="loanChart" height="100"></canvas>
                            </div>
                            <div class="card-footer" style="display:flex; gap:20px; font-size:13px;">
                                <span>
                                    <span
                                        style="display:inline-block;width:14px;height:3px;background:#6777ef;border-radius:2px;vertical-align:middle;margin-right:5px;"></span>
                                    Dipinjam
                                </span>
                                <span>
                                    <span
                                        style="display:inline-block;width:14px;height:3px;background:#54ca68;border-radius:2px;vertical-align:middle;margin-right:5px;"></span>
                                    Dikembalikan
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- DONUT CHART --}}
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Status Peminjaman</h4>
                                <small class="text-muted">Bulan ini</small>
                            </div>
                            <div class="card-body text-center">
                                <canvas id="statusChart" height="180"></canvas>
                                <div class="mt-3" style="font-size:12px; text-align:left;">
                                    <div><span
                                            style="display:inline-block;width:10px;height:10px;background:#6777ef;border-radius:50%;margin-right:6px;"></span>Dipinjam
                                        — <strong>{{ $pctDipinjam }}%</strong></div>
                                    <div><span
                                            style="display:inline-block;width:10px;height:10px;background:#54ca68;border-radius:50%;margin-right:6px;"></span>Dikembalikan
                                        — <strong>{{ $pctDikembalikan }}%</strong></div>
                                    <div><span
                                            style="display:inline-block;width:10px;height:10px;background:#fc544b;border-radius:50%;margin-right:6px;"></span>Terlambat
                                        — <strong>{{ $pctTerlambat }}%</strong></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TABEL TERBARU --}}
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Peminjaman Terbaru</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Anggota</th>
                                                <th>Judul Buku</th>
                                                <th>Tgl Pinjam</th>
                                                <th>Jatuh Tempo</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($peminjamanterbaru as $p)
                                                <tr>
                                                    <td>{{ $p->user->name ?? '-' }}</td>
                                                    <td>{{ $p->book->title ?? '-' }}</td>
                                                    <td>{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') : '-' }}
                                                    </td>
                                                    <td>{{ $p->jatuh_tempo ? \Carbon\Carbon::parse($p->jatuh_tempo)->format('d M Y') : '-' }}
                                                    </td>
                                                    <td>
                                                        @if ($p->status === 'approve')
                                                            @if ($p->jatuh_tempo && \Carbon\Carbon::parse($p->jatuh_tempo)->isPast())
                                                                <span
                                                                    style="background:rgba(248,113,113,0.15);color:#f87171;padding:4px 12px;border-radius:6px;font-size:12px;font-weight:600;letter-spacing:.04em;">OVERDUE</span>
                                                            @else
                                                                <span
                                                                    style="background:rgba(108,142,255,0.15);color:#6c8eff;padding:4px 12px;border-radius:6px;font-size:12px;font-weight:600;letter-spacing:.04em;">BORROWED</span>
                                                            @endif
                                                        @elseif($p->status === 'returned')
                                                            <span
                                                                style="background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.55);padding:4px 12px;border-radius:6px;font-size:12px;font-weight:600;letter-spacing:.04em;">RETURNED</span>
                                                        @elseif($p->status === 'pending')
                                                            <span
                                                                style="background:rgba(239,159,39,0.15);color:#EF9F27;padding:4px 12px;border-radius:6px;font-size:12px;font-weight:600;letter-spacing:.04em;">PENDING</span>
                                                        @elseif($p->status === 'rejected')
                                                            <span
                                                                style="background:rgba(248,113,113,0.15);color:#f87171;padding:4px 12px;border-radius:6px;font-size:12px;font-weight:600;letter-spacing:.04em;">REJECTED</span>
                                                        @elseif($p->status === 'verifikasi')
                                                            <span
                                                                style="background:rgba(34,211,238,0.15);color:#22d3ee;padding:4px 12px;border-radius:6px;font-size:12px;font-weight:600;letter-spacing:.04em;">VERIFIKASI</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-3">Belum ada data
                                                        peminjaman.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Chart.js --}}
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    // LINE CHART — data dari controller
                    const loanCtx = document.getElementById('loanChart').getContext('2d');
                    new Chart(loanCtx, {
                        type: 'line',
                        data: {
                            labels: {!! $chartLabels !!},
                            datasets: [{
                                    label: 'Dipinjam',
                                    data: {!! $chartDipinjam !!},
                                    borderColor: '#6777ef',
                                    backgroundColor: 'rgba(103,119,239,0.12)',
                                    borderWidth: 2.5,
                                    fill: true,
                                    tension: 0.4,
                                    pointBackgroundColor: '#6777ef',
                                    pointRadius: 4,
                                },
                                {
                                    label: 'Dikembalikan',
                                    data: {!! $chartDikembalikan !!},
                                    borderColor: '#54ca68',
                                    backgroundColor: 'rgba(84,202,104,0.08)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.4,
                                    borderDash: [5, 3],
                                    pointRadius: 3,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    },
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                }
                            }
                        }
                    });

                    // DONUT CHART — data dari controller
                    const statusCtx = document.getElementById('statusChart').getContext('2d');
                    new Chart(statusCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Dipinjam', 'Dikembalikan', 'Terlambat'],
                            datasets: [{
                                data: {!! $donutData !!},
                                backgroundColor: ['#6777ef', '#54ca68', '#fc544b'],
                                borderWidth: 0,
                                hoverOffset: 6
                            }]
                        },
                        options: {
                            cutout: '70%',
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                </script>

            </div>
        </section>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // LINE CHART
        const loanCtx = document.getElementById('loanChart').getContext('2d');
        new Chart(loanCtx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                        label: 'Dipinjam',
                        data: [5, 7, 4, 9, 6, 10, 8],
                        borderColor: '#6777ef',
                        backgroundColor: 'rgba(103,119,239,0.12)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#6777ef',
                        pointRadius: 4,
                    },
                    {
                        label: 'Dikembalikan',
                        data: [3, 5, 3, 6, 4, 7, 5],
                        borderColor: '#54ca68',
                        backgroundColor: 'rgba(84,202,104,0.08)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        borderDash: [5, 3],
                        pointRadius: 3,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255,255,255,0.05)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255,255,255,0.05)'
                        }
                    }
                }
            }
        });

        // DONUT CHART
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Dipinjam', 'Dikembalikan', 'Terlambat'],
                datasets: [{
                    data: [45, 40, 15],
                    backgroundColor: ['#6777ef', '#54ca68', '#fc544b'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>

    {{-- Toast CSS & JS --}}
    <style>
        .toast-custom {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 9999;
            animation: slideIn 0.5s ease-out, fadeOut 0.5s ease-in 2.5s forwards;
        }

        .toast-content {
            background: #28a745;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
                visibility: hidden;
            }
        }
    </style>
    <script>
        setTimeout(function() {
            var a = document.getElementById('floating-alert');
            if (a) a.remove();
        }, 3000);
    </script>
@endsection
