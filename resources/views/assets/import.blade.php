@extends('layouts.app')
@section('title', 'Import Asset')
@section('page-title', 'Import Asset via Excel')

@section('content')

    {{-- Step indicator --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                style="width:32px;height:32px;background:{{ isset($results) ? '#28a745' : '#2563eb' }};color:#fff;font-size:.85rem">
                {{ isset($results) ? '✓' : '1' }}
            </div>
            <span class="small fw-semibold {{ isset($results) ? 'text-success' : 'text-primary' }}">Upload File</span>
        </div>
        <div style="width:40px;height:2px;background:{{ isset($results) ? '#28a745' : '#dee2e6' }}"></div>
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                style="width:32px;height:32px;background:{{ isset($results) ? '#2563eb' : '#dee2e6' }};color:#fff;font-size:.85rem">
                2</div>
            <span class="small fw-semibold {{ isset($results) ? 'text-primary' : 'text-muted' }}">Preview & Validasi</span>
        </div>
        <div style="width:40px;height:2px;background:#dee2e6"></div>
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                style="width:32px;height:32px;background:#dee2e6;color:#fff;font-size:.85rem">3</div>
            <span class="small fw-semibold text-muted">Selesai</span>
        </div>
    </div>

    @if (!isset($results))
        {{-- ── STEP 1: Upload ── --}}
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-upload me-2 text-primary"></i>Upload File Excel / CSV
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('assets.import.preview') }}" method="POST" enctype="multipart/form-data"
                            id="uploadForm">
                            @csrf
                            <div class="upload-area border-2 border-dashed rounded-3 p-4 text-center mb-3" id="dropZone"
                                style="border-color:#cbd5e1;cursor:pointer;transition:all .2s">
                                <i class="bi bi-file-earmark-spreadsheet text-success" style="font-size:2.5rem"></i>
                                <div class="mt-2 fw-semibold">Drag & drop file di sini</div>
                                <div class="text-muted small mb-3">atau klik untuk pilih file</div>
                                <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" class="d-none"
                                    required>
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="document.getElementById('fileInput').click()">
                                    <i class="bi bi-folder2-open me-1"></i> Pilih File
                                </button>
                            </div>

                            <div id="fileInfo" class="d-none alert alert-success py-2 px-3 mb-3">
                                <i class="bi bi-file-earmark-check me-2"></i>
                                <span id="fileName"></span>
                                <span id="fileSize" class="text-muted ms-2 small"></span>
                            </div>

                            @error('file')
                                <div class="alert alert-danger py-2 px-3 small">{{ $message }}</div>
                            @enderror

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="btnPreview" disabled>
                                    <i class="bi bi-eye me-1"></i> Preview & Validasi
                                </button>
                                <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                {{-- Download template --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-1"><i class="bi bi-download me-2 text-success"></i>Download Template</h6>
                        <p class="text-muted small mb-3">Gunakan template resmi agar format sesuai dan tidak error saat
                            import.</p>
                        <a href="{{ route('assets.import.template') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i> Download Template Excel
                        </a>
                    </div>
                </div>

                {{-- Panduan --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-2">
                        <h6 class="mb-0 fw-semibold small"><i class="bi bi-info-circle me-2 text-info"></i>Panduan Import
                        </h6>
                    </div>
                    <div class="card-body py-2">
                        <ul class="list-unstyled mb-0 small text-muted" style="line-height:1.9">
                            <li><i class="bi bi-check2 text-success me-1"></i> Format: <strong>.xlsx</strong>,
                                <strong>.xls</strong>, atau <strong>.csv</strong></li>
                            <li><i class="bi bi-check2 text-success me-1"></i> Ukuran maksimal: <strong>5 MB</strong></li>
                            <li><i class="bi bi-check2 text-success me-1"></i> Maksimal <strong>500 baris</strong> per
                                import</li>
                            <li><i class="bi bi-check2 text-success me-1"></i> Kode asset harus <strong>unik</strong></li>
                            <li><i class="bi bi-check2 text-success me-1"></i> Nama kategori harus <strong>sesuai
                                    sistem</strong></li>
                            <li><i class="bi bi-exclamation-triangle text-warning me-1"></i> Kondisi: <code>good / fair /
                                    poor / broken</code></li>
                            <li><i class="bi bi-exclamation-triangle text-warning me-1"></i> Status: <code>available /
                                    in_use / maintenance / retired</code></li>
                        </ul>
                    </div>
                </div>

                {{-- Daftar kategori --}}
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-white py-2">
                        <h6 class="mb-0 fw-semibold small"><i class="bi bi-tags me-2 text-primary"></i>Kategori Tersedia
                        </h6>
                    </div>
                    <div class="card-body py-2">
                        <div class="small">
                            @foreach (\App\Models\AssetCategory::active()->get() as $cat)
                                <span class="badge bg-light text-dark border me-1 mb-1">{{ $cat->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- ── STEP 2: Preview & Validasi ── --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-table me-2"></i>Preview Data Import</h6>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <span class="badge bg-success">{{ $validCount }} valid</span>
                    @if ($invalidCount > 0)
                        <span class="badge bg-danger">{{ $invalidCount }} error</span>
                    @endif
                    <span class="badge bg-secondary">{{ count($results) }} total baris</span>
                </div>
            </div>

            @if ($invalidCount > 0)
                <div class="alert alert-warning m-3 mb-0 py-2 px-3 small">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <strong>{{ $invalidCount }} baris memiliki error</strong> dan tidak akan diimport.
                    Hanya <strong>{{ $validCount }} baris valid</strong> yang akan disimpan.
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" style="font-size:.82rem">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width:45px">Baris</th>
                            <th>Nama Asset</th>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Merek</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                            <th class="text-center" style="width:80px">Status Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $row)
                            <tr class="{{ $row['valid'] ? '' : 'table-danger' }}">
                                <td class="text-center text-muted">{{ $row['row'] }}</td>
                                <td>{{ $row['name'] ?: '—' }}</td>
                                <td><code>{{ $row['code'] ?: '—' }}</code></td>
                                <td>{{ $row['category_name'] ?: '—' }}</td>
                                <td class="text-muted">{{ $row['brand'] ?: '—' }}</td>
                                <td>
                                    @if ($row['condition'])
                                        <span
                                            class="badge bg-{{ ['good' => 'success', 'fair' => 'warning', 'poor' => 'danger', 'broken' => 'dark'][$row['condition']] ?? 'secondary' }}">
                                            {{ $row['condition'] }}
                                        </span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $row['status'] ?: '—' }}</td>
                                <td class="text-center">
                                    @if ($row['valid'])
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <span data-bs-toggle="tooltip" title="{{ implode(' | ', $row['errors']) }}">
                                            <i class="bi bi-x-circle-fill text-danger"></i>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @if (!$row['valid'])
                                <tr class="table-danger">
                                    <td></td>
                                    <td colspan="7" class="text-danger small py-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ implode(' &bull; ', $row['errors']) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white d-flex gap-2 flex-wrap">
                @if ($validCount > 0)
                    <form action="{{ route('assets.import.process') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-upload me-1"></i>
                            Import {{ $validCount }} Asset Valid
                        </button>
                    </form>
                @endif
                <a href="{{ route('assets.import') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Upload Ulang
                </a>
                <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        // Tooltip Bootstrap
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });

        // File input handler
        const fileInput = document.getElementById('fileInput');
        const fileInfo = document.getElementById('fileInfo');
        const btnPreview = document.getElementById('btnPreview');
        const dropZone = document.getElementById('dropZone');

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files[0]) showFile(this.files[0]);
            });
        }

        if (dropZone) {
            dropZone.addEventListener('dragover', e => {
                e.preventDefault();
                dropZone.style.borderColor = '#2563eb';
                dropZone.style.background = '#f0f4ff';
            });
            dropZone.addEventListener('dragleave', () => {
                dropZone.style.borderColor = '#cbd5e1';
                dropZone.style.background = '';
            });
            dropZone.addEventListener('drop', e => {
                e.preventDefault();
                dropZone.style.borderColor = '#cbd5e1';
                dropZone.style.background = '';
                const file = e.dataTransfer.files[0];
                if (file) {
                    fileInput.files = e.dataTransfer.files;
                    showFile(file);
                }
            });
        }

        function showFile(file) {
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = '(' + (file.size / 1024).toFixed(1) + ' KB)';
            fileInfo.classList.remove('d-none');
            btnPreview.disabled = false;
        }
    </script>
@endpush
