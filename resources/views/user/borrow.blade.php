@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
<style>
    /* ── Item Card ── */
    .item-card {
        border-radius: 16px;
        border: 2px solid rgba(0, 0, 0, 0.07);
        transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.2s ease;
        overflow: hidden;
        background: #fff;
    }
    .item-card:hover {
        border-color: rgba(220, 53, 69, 0.3);
        box-shadow: 0 8px 24px rgba(220, 53, 69, 0.08);
        transform: translateY(-2px);
    }
    .item-card.has-qty {
        border-color: #dc3545;
        background: linear-gradient(135deg, #fff 80%, #fff5f5 100%);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.12);
    }

    /* ── Icon Box ── */
    .item-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: linear-gradient(135deg, #fff3f3, #ffd6d6);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* ── Quantity Controls ── */
    .qty-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8f9fa;
        border-radius: 50px;
        padding: 5px;
        border: 1.5px solid #e9ecef;
        margin-top: 14px;
        transition: border-color 0.2s;
    }
    .item-card.has-qty .qty-wrap {
        border-color: rgba(220, 53, 69, 0.25);
        background: #fff9f9;
    }
    .btn-qty {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: none;
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        background: #fff;
        color: #495057;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        flex-shrink: 0;
    }
    .btn-qty:hover:not(:disabled) {
        background: #dc3545;
        color: #fff;
        box-shadow: 0 3px 10px rgba(220, 53, 69, 0.35);
        transform: scale(1.08);
    }
    .btn-qty:active:not(:disabled) {
        transform: scale(0.95);
    }
    .btn-qty:disabled {
        opacity: 0.28;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }
    .qty-display {
        display: flex;
        align-items: baseline;
        gap: 2px;
        min-width: 0;
    }
    .qty-num {
        font-size: 1.2rem;
        font-weight: 800;
        color: #212529;
        min-width: 28px;
        text-align: center;
        line-height: 1;
    }
    .qty-max {
        font-size: 0.78rem;
        color: #adb5bd;
        font-weight: 500;
    }

    /* ── Stock Warning ── */
    .stock-warning {
        display: none;
        align-items: center;
        gap: 5px;
        font-size: 0.78rem;
        color: #dc3545;
        margin-top: 8px;
        padding: 5px 10px;
        background: rgba(220, 53, 69, 0.06);
        border-radius: 8px;
        border-left: 3px solid #dc3545;
    }
    .stock-warning.show {
        display: flex;
    }

    /* ── Search ── */
    .search-box {
        background: #fff;
        border-radius: 50px;
        border: 1.5px solid #e9ecef;
        padding: 10px 20px 10px 44px;
        font-size: 0.9rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        width: 100%;
        outline: none;
        color: #495057;
    }
    .search-box:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }

    /* ── Sticky Submit Bar ── */
    .sticky-bar {
        position: sticky;
        bottom: 16px;
        z-index: 200;
        margin-top: 28px;
    }
    .sticky-inner {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.14), 0 2px 8px rgba(0, 0, 0, 0.06);
        padding: 16px 24px;
        border: 1px solid rgba(0, 0, 0, 0.07);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .submit-btn {
        background: linear-gradient(135deg, #dc3545, #c41e2e);
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 12px 32px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 200px;
        box-shadow: 0 4px 14px rgba(220, 53, 69, 0.35);
    }
    .submit-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.45);
    }
    .submit-btn:disabled {
        background: linear-gradient(135deg, #adb5bd, #9aa0a6);
        box-shadow: none;
        cursor: not-allowed;
        transform: none;
    }

    /* ── Empty State ── */
    .no-results {
        display: none;
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    .no-results.show { display: block; }

    /* ── Date Card ── */
    .date-card {
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, 0.07);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }
    .date-card .form-control {
        border: 1.5px solid #e9ecef;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.95rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .date-card .form-control:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }
</style>

{{-- ── Page Header ── --}}
<div class="row mb-4 fade-in">
    <div class="col-12">
        <div style="background: #fff; border-radius: 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.07);">
            <div class="d-flex justify-content-between align-items-center p-4 flex-wrap gap-3">
                <div>
                    <h4 class="mb-1 fw-bold text-dark">
                        <i class="fas fa-hand-holding me-2 text-danger"></i>Pinjam Barang
                    </h4>
                    <p class="text-muted mb-0 small">
                        Masukkan jumlah barang yang ingin dipinjam untuk setiap jenis
                    </p>
                </div>
                <a href="{{ route('user.dashboard') }}"
                   class="btn btn-light border shadow-sm rounded-pill px-4 fw-medium text-dark">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ── Flash Messages ── --}}
@if(session('success'))
    <div class="alert border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#d4edda,#c3e6cb);color:#155724;">
        <i class="fas fa-check-circle fs-5"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif
@if(session('error'))
    <div class="alert border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#f8d7da,#f5c6cb);color:#721c24;">
        <i class="fas fa-times-circle fs-5"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif
@if(session('warning'))
    <div class="alert border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#fff3cd,#ffeeba);color:#856404;">
        <i class="fas fa-exclamation-triangle fs-5"></i>
        <span>{{ session('warning') }}</span>
    </div>
@endif

@if($availableItems->count() > 0)

<form method="POST" action="{{ route('user.borrow.quantity') }}" id="borrowForm" novalidate>
    @csrf

    {{-- ── Jadwal Peminjaman ── --}}
    <div class="card date-card border-0 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold text-dark mb-3">
                <i class="far fa-calendar-alt me-2 text-danger"></i>Jadwal Peminjaman
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted mb-1">
                        TANGGAL MULAI PINJAM <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="borrow_date" id="borrow_date"
                           class="form-control" required>
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>
                        Kapan Anda berencana mengambil barang.
                    </small>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted mb-1">
                        TENGGAT PENGEMBALIAN <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="expected_return_date" id="expected_return_date"
                           class="form-control" required>
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>
                        Berlaku serentak untuk semua barang yang dipinjam.
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Header + Search ── --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-0 text-dark">
                <i class="fas fa-boxes me-2 text-success"></i>Daftar Inventaris Tersedia
            </h5>
            <small class="text-muted">{{ $availableItems->count() }} jenis barang dapat dipinjam</small>
        </div>
        <div class="position-relative" style="max-width: 290px; width: 100%;">
            <i class="fas fa-search position-absolute text-muted"
               style="top:50%;left:15px;transform:translateY(-50%);pointer-events:none;font-size:0.85rem;"></i>
            <input type="text" id="searchInput" class="search-box"
                   placeholder="Cari nama atau kategori...">
        </div>
    </div>

    {{-- ── Items Grid ── --}}
    <div class="row g-3" id="itemsGrid">
        @foreach($availableItems as $index => $item)
        <div class="col-md-6 col-xl-4 item-wrapper"
             data-search="{{ strtolower($item->name . ' ' . ($item->category->name ?? '') . ' ' . ($item->location->name ?? '')) }}">
            <div class="card item-card h-100 border-0" id="card-{{ $index }}">
                <div class="card-body p-4">

                    {{-- Item header --}}
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="item-icon">
                            <i class="fas fa-cube text-danger" style="font-size:1.1rem;"></i>
                        </div>
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="fw-bold text-dark mb-1"
                                 style="font-size:0.92rem;line-height:1.35;word-break:break-word;">
                                {{ $item->name }}
                            </div>
                            <span class="badge bg-light text-secondary border"
                                  style="font-size:0.7rem;font-weight:500;">
                                {{ $item->category->name ?? '-' }}
                            </span>
                        </div>
                    </div>

                    {{-- Meta info --}}
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <small class="text-muted" style="font-size:0.8rem;">
                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                            {{ $item->location->name ?? '-' }}
                        </small>
                        <span class="badge rounded-pill px-3"
                              style="font-size:0.72rem;background:rgba(25,135,84,0.1);color:#198754;border:1px solid rgba(25,135,84,0.2);">
                            <i class="fas fa-layer-group me-1"></i>{{ $item->stock }} unit tersedia
                        </span>
                    </div>

                    {{-- Quantity Control --}}
                    <div class="qty-wrap" id="qwrap-{{ $index }}">
                        <button type="button"
                                class="btn-qty btn-minus"
                                data-idx="{{ $index }}"
                                disabled
                                aria-label="Kurangi">−</button>

                        <div class="qty-display">
                            <span class="qty-num" id="qnum-{{ $index }}">0</span>
                            <span class="qty-max">/ {{ $item->stock }}</span>
                        </div>

                        <button type="button"
                                class="btn-qty btn-plus"
                                data-idx="{{ $index }}"
                                data-max="{{ $item->stock }}"
                                aria-label="Tambah">+</button>
                    </div>

                    {{-- Warning saat mencapai stok maks --}}
                    <div class="stock-warning" id="warn-{{ $index }}">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Stok penuh — maks. <strong>{{ $item->stock }}</strong> unit</span>
                    </div>

                    {{-- Hidden form input --}}
                    <input type="hidden"
                           name="quantities[{{ $item->name }}]"
                           value="0"
                           id="qinput-{{ $index }}"
                           class="qty-input">
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- No-results state --}}
    <div class="no-results" id="noResults">
        <i class="fas fa-search mb-3" style="font-size:3rem;opacity:0.25;"></i>
        <p class="mb-0">Tidak ada barang yang cocok dengan pencarian.</p>
    </div>

    {{-- ── Sticky Submit Bar ── --}}
    <div class="sticky-bar">
        <div class="sticky-inner">
            <div>
                <div class="text-muted mb-1" style="font-size:0.82rem;font-weight:500;">TOTAL YANG DIPILIH</div>
                <div class="fw-bold" style="font-size:1.4rem;color:#212529;">
                    <span id="totalCount">0</span>
                    <span style="font-size:0.95rem;color:#6c757d;font-weight:400;"> item</span>
                </div>
            </div>
            <button type="submit" class="submit-btn" id="submitBtn" disabled>
                <i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman
            </button>
        </div>
    </div>
</form>

@else
{{-- ── Empty State ── --}}
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body text-center py-5">
        <i class="fas fa-box-open text-muted mb-4" style="font-size:5rem;opacity:0.25;"></i>
        <h4 class="fw-bold text-dark">Tidak Ada Barang Tersedia</h4>
        <p class="text-muted mb-4">Maaf, saat ini seluruh inventaris sedang dipakai atau dalam pemeliharaan.</p>
        <a href="{{ route('user.dashboard') }}"
           class="btn btn-danger rounded-pill px-4 py-2 fw-semibold shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>
</div>
@endif

{{-- ── Panduan ── --}}
<div class="mt-4 p-4 rounded-4"
     style="background:linear-gradient(135deg,#eff8ff,#e0f0ff);border:1px solid #bee3f8;">
    <div class="d-flex gap-3">
        <i class="fas fa-info-circle text-primary fs-4 pt-1 flex-shrink-0"></i>
        <div>
            <h6 class="fw-bold text-primary mb-2">Cara Meminjam</h6>
            <ol class="mb-0 text-muted ps-3 lh-lg" style="font-size:0.88rem;">
                <li>Isi <strong>Tanggal Mulai Pinjam</strong> dan <strong>Tenggat Pengembalian</strong>.</li>
                <li>Tekan tombol <strong>[+]</strong> di setiap jenis barang untuk menambah jumlah yang ingin dipinjam.</li>
                <li>Tombol <strong>[+]</strong> otomatis nonaktif saat mencapai batas stok yang tersedia.</li>
                <li>Klik <strong>"Ajukan Peminjaman"</strong> — sistem akan otomatis memilihkan unit barang yang tersedia untuk Anda.</li>
            </ol>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const submitBtn  = document.getElementById('submitBtn');
    const totalEl    = document.getElementById('totalCount');
    const searchInput = document.getElementById('searchInput');
    const noResults  = document.getElementById('noResults');
    const today      = new Date().toISOString().split('T')[0];

    // ── Set min date ──
    const borrowDateEl  = document.getElementById('borrow_date');
    const returnDateEl  = document.getElementById('expected_return_date');
    if (borrowDateEl)  borrowDateEl.min  = today;
    if (returnDateEl)  returnDateEl.min  = today;
    if (borrowDateEl) {
        borrowDateEl.addEventListener('change', function () {
            if (returnDateEl) returnDateEl.min = this.value || today;
        });
    }

    // ── Update grand total ──
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.qty-input').forEach(inp => {
            total += parseInt(inp.value || 0);
        });
        totalEl.textContent = total;
        submitBtn.disabled  = total === 0;
    }

    // ── Plus buttons ──
    document.querySelectorAll('.btn-plus').forEach(function (plusBtn) {
        plusBtn.addEventListener('click', function () {
            const idx    = this.dataset.idx;
            const max    = parseInt(this.dataset.max);
            const input  = document.getElementById('qinput-' + idx);
            const numEl  = document.getElementById('qnum-'   + idx);
            const minBtn = document.querySelector('.btn-minus[data-idx="' + idx + '"]');
            const warn   = document.getElementById('warn-' + idx);
            const card   = document.getElementById('card-' + idx);

            let cur = parseInt(input.value || 0);
            if (cur < max) {
                cur++;
                input.value      = cur;
                numEl.textContent = cur;
                minBtn.disabled  = false;
                card.classList.toggle('has-qty', cur > 0);

                if (cur >= max) {
                    this.disabled = true;
                    if (warn) warn.classList.add('show');
                } else {
                    if (warn) warn.classList.remove('show');
                }
            }
            updateTotal();
        });
    });

    // ── Minus buttons ──
    document.querySelectorAll('.btn-minus').forEach(function (minBtn) {
        minBtn.addEventListener('click', function () {
            const idx    = this.dataset.idx;
            const input  = document.getElementById('qinput-' + idx);
            const numEl  = document.getElementById('qnum-'   + idx);
            const plusBtn = document.querySelector('.btn-plus[data-idx="' + idx + '"]');
            const warn   = document.getElementById('warn-' + idx);
            const card   = document.getElementById('card-' + idx);

            let cur = parseInt(input.value || 0);
            if (cur > 0) {
                cur--;
                input.value       = cur;
                numEl.textContent = cur;
                this.disabled     = cur === 0;
                plusBtn.disabled  = false;
                card.classList.toggle('has-qty', cur > 0);
                if (warn) warn.classList.remove('show');
            }
            updateTotal();
        });
    });

    // ── Search filter ──
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const term     = this.value.toLowerCase().trim();
            let anyVisible = false;

            document.querySelectorAll('.item-wrapper').forEach(function (wrapper) {
                const txt   = wrapper.dataset.search || '';
                const match = !term || txt.includes(term);
                wrapper.style.display = match ? '' : 'none';
                if (match) anyVisible = true;
            });

            if (noResults) noResults.classList.toggle('show', !anyVisible);
        });
    }

    // ── Form submit validation ──
    const form = document.getElementById('borrowForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            const bd = borrowDateEl ? borrowDateEl.value : '';
            const rd = returnDateEl ? returnDateEl.value : '';

            if (!bd) {
                e.preventDefault();
                alert('Pilih tanggal mulai pinjam terlebih dahulu!');
                return;
            }
            if (!rd) {
                e.preventDefault();
                alert('Pilih tanggal pengembalian terlebih dahulu!');
                return;
            }
            if (rd <= bd) {
                e.preventDefault();
                alert('Tanggal pengembalian harus setelah tanggal mulai pinjam!');
                return;
            }
        });
    }
});
</script>
@endsection
