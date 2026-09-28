<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>
        Berita Acara Serah Terima - {{ $loan->asset->name }}
    </title>

    <style>
        @page {
            size: A4;
            margin: 25mm 25mm 20mm 25mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .document {
            width: 100%;
        }

        /* =========================================================
           JUDUL
        ========================================================= */

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .document-number {
            text-align: center;
            font-size: 12px;
            margin-bottom: 25px;
        }

        /* =========================================================
           PARAGRAF
        ========================================================= */

        .paragraph {
            text-align: justify;
            margin-bottom: 12px;
        }

        /* =========================================================
           IDENTITAS PEMINJAM
        ========================================================= */

        .identity {
            margin: 8px 0 14px 0;
        }

        .identity-row {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }

        .identity-label {
            display: table-cell;
            width: 110px;
        }

        .identity-separator {
            display: table-cell;
            width: 15px;
        }

        .identity-value {
            display: table-cell;
        }

        /* =========================================================
           ALAT KERJA
        ========================================================= */

        .equipment-title {
            margin-top: 8px;
            margin-bottom: 5px;
        }

        .equipment {
            margin-left: 15px;
            margin-bottom: 15px;
        }

        .equipment-row {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }

        .equipment-label {
            display: table-cell;
            width: 120px;
        }

        .equipment-separator {
            display: table-cell;
            width: 15px;
        }

        .equipment-value {
            display: table-cell;
        }

        /* =========================================================
           KETENTUAN
        ========================================================= */

        .terms-title {
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .terms {
            margin-top: 0;
            padding-left: 22px;
            margin-bottom: 15px;
        }

        .terms li {
            margin-bottom: 5px;
            padding-left: 3px;
            text-align: justify;
        }

        /* =========================================================
           PENUTUP
        ========================================================= */

        .closing {
            text-align: justify;
            margin-top: 10px;
        }

        /* =========================================================
           TANGGAL
        ========================================================= */

        .signature-date {
            text-align: right;
            margin-top: 25px;
            margin-bottom: 35px;
        }

        /* =========================================================
           TABEL TANDA TANGAN
        ========================================================= */

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            border: none;
        }

        /* =========================================================
           AREA TANDA TANGAN
        ========================================================= */

        .signature-space {
            height: 80px;
            vertical-align: middle;
        }

        /* =========================================================
           GAMBAR TANDA TANGAN
        ========================================================= */

        .signature-image {
            width: 150px;
            height: 70px;
            object-fit: contain;
        }

        /* =========================================================
           NAMA PENANDA TANGAN
        ========================================================= */

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .signature-position {
            margin-top: 2px;
        }
    </style>
</head>

<body>

    <div class="document">

        {{-- =========================================================
         JUDUL
    ========================================================== --}}

        <div class="title">
            BERITA ACARA SERAH TERIMA PERALATAN KERJA
        </div>

        <div class="document-number">
            {{ $loan->document_number }}
        </div>


        {{-- =========================================================
         PEMBUKA
    ========================================================== --}}

        <div class="paragraph">

            Pada Hari

            <strong>
                {{ $loan->borrowed_at->translatedFormat('l') }}
            </strong>

            tanggal

            <strong>
                {{ $loan->borrowed_at->translatedFormat('d F Y') }}
            </strong>

            telah dilakukan serah terima peralatan kerja kepada karyawan
            <strong>PT. VIVA MEDIA BARU</strong>
            dengan keterangan berikut:

        </div>


        {{-- =========================================================
         IDENTITAS PEMINJAM
    ========================================================== --}}

        <div class="identity">

            {{-- Nama --}}
            <div class="identity-row">

                <div class="identity-label">
                    Nama
                </div>

                <div class="identity-separator">
                    :
                </div>

                <div class="identity-value">
                    {{ $loan->borrower_name }}
                </div>

            </div>


            {{-- Jabatan --}}
            <div class="identity-row">

                <div class="identity-label">
                    Jabatan
                </div>

                <div class="identity-separator">
                    :
                </div>

                <div class="identity-value">
                    {{ $loan->borrower_position ?: '-' }}
                </div>

            </div>


            {{-- Divisi --}}
            <div class="identity-row">

                <div class="identity-label">
                    Divisi
                </div>

                <div class="identity-separator">
                    :
                </div>

                <div class="identity-value">
                    {{ $loan->borrower_department ?: '-' }}
                </div>

            </div>

        </div>


        {{-- =========================================================
         ALAT KERJA
    ========================================================== --}}

        <div class="equipment-title">
            Alat kerja berupa:
        </div>

        <div class="equipment">

            {{-- Nama Barang --}}
            <div class="equipment-row">

                <div class="equipment-label">
                    Nama Barang
                </div>

                <div class="equipment-separator">
                    :
                </div>

                <div class="equipment-value">

                    {{ $loan->asset->name }}

                    @if ($loan->asset->brand || $loan->asset->model)

                        -
                        {{ $loan->asset->brand }}

                        @if ($loan->asset->model)
                            {{ $loan->asset->model }}
                        @endif

                    @endif

                </div>

            </div>


            {{-- Serial Number --}}
            <div class="equipment-row">

                <div class="equipment-label">
                    Serial Number
                </div>

                <div class="equipment-separator">
                    :
                </div>

                <div class="equipment-value">
                    {{ $loan->asset->serial_number ?: '-' }}
                </div>

            </div>


            {{-- Spesifikasi --}}
            <div class="equipment-row">

                <div class="equipment-label">
                    Spesifikasi
                </div>

                <div class="equipment-separator">
                    :
                </div>

                <div class="equipment-value">
                    {{ $loan->asset->description ?: '-' }}
                </div>

            </div>


            {{-- Kelengkapan --}}
            <div class="equipment-row">

                <div class="equipment-label">
                    Kelengkapan
                </div>

                <div class="equipment-separator">
                    :
                </div>

                <div class="equipment-value">
                    Charger Adapter
                </div>

            </div>

        </div>


        {{-- =========================================================
         KETENTUAN
    ========================================================== --}}

        <div class="terms-title">
            Dengan ketentuan sebagai berikut:
        </div>

        <ol class="terms">

            <li>
                Perawatan sehari hari menjadi tanggung jawab pengguna.
            </li>

            <li>
                Kerusakan selama masa garansi ditanggung oleh vendor
                yang difasilitasi Bagian GA dan diurus oleh Bagian Procurement.
            </li>

            <li>
                Kerusakan dan atau kehilangan sebagian atau seluruh komponen
                peralatan karena kecelakaan kerja menjadi tanggung jawab perusahaan.
            </li>

            <li>
                Kerusakan dan atau kehilangan sebagian atau seluruh komponen
                peralatan kerja tersebut diatas akibat kelalaian pengguna
                menjadi tanggung jawab pihak pengguna sepenuhnya.
            </li>

        </ol>


        {{-- =========================================================
         PENUTUP
    ========================================================== --}}

        <div class="closing">

            Demikian berita acara serah terima ini dibuat untuk dapat diketahui
            dan digunakan sebagaimana mestinya.

        </div>


        {{-- =========================================================
         TANGGAL
    ========================================================== --}}

        <div class="signature-date">

            {{ $loan->borrowed_at->translatedFormat('l') }},
            {{ $loan->borrowed_at->translatedFormat('d F Y') }}

        </div>


        {{-- =========================================================
         TANDA TANGAN
    ========================================================== --}}

        <table class="signature-table">

            <tr>

                {{-- =================================================
                 PEMBERI
            ================================================== --}}

                <td>

                    <strong>
                        Pemberi,
                    </strong>

                    <div class="signature-space">
                        {{-- Area kosong tanda tangan pemberi --}}
                    </div>

                    <div class="signature-name">
                        Muhamad Ariel Saputra
                    </div>

                    <div class="signature-position">
                        IT Support
                    </div>

                </td>


                {{-- =================================================
                 PENERIMA
            ================================================== --}}

                <td>

                    <strong>
                        Penerima,
                    </strong>

                    <div class="signature-space">

                        @if (!empty($signaturePath) && is_file($signaturePath))
                            <img src="{{ $signaturePath }}" alt="Tanda Tangan Peminjam" class="signature-image">
                        @endif

                    </div>

                    <div class="signature-name">
                        {{ $loan->borrower_name }}
                    </div>

                    <div class="signature-position">
                        {{ $loan->borrower_position ?: 'Karyawan' }}
                    </div>

                </td>

            </tr>

        </table>

    </div>

</body>

</html>
