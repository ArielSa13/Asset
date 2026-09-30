<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>
        Berita Acara Serah Terima - <?php echo e($loan->asset->name); ?>

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

        

        <div class="title">
            BERITA ACARA SERAH TERIMA PERALATAN KERJA
        </div>

        <div class="document-number">
            <?php echo e($loan->document_number); ?>

        </div>


        

        <div class="paragraph">

            Pada Hari

            <strong>
                <?php echo e($loan->borrowed_at->translatedFormat('l')); ?>

            </strong>

            tanggal

            <strong>
                <?php echo e($loan->borrowed_at->translatedFormat('d F Y')); ?>

            </strong>

            telah dilakukan serah terima peralatan kerja kepada karyawan
            <strong>PT. ONE DIGITAL MEDIA</strong>
            dengan keterangan berikut:

        </div>


        

        <div class="identity">

            
            <div class="identity-row">

                <div class="identity-label">
                    Nama
                </div>

                <div class="identity-separator">
                    :
                </div>

                <div class="identity-value">
                    <?php echo e($loan->borrower_name); ?>

                </div>

            </div>


            
            <div class="identity-row">

                <div class="identity-label">
                    Jabatan
                </div>

                <div class="identity-separator">
                    :
                </div>

                <div class="identity-value">
                    <?php echo e($loan->borrower_position ?: '-'); ?>

                </div>

            </div>


            
            <div class="identity-row">

                <div class="identity-label">
                    Divisi
                </div>

                <div class="identity-separator">
                    :
                </div>

                <div class="identity-value">
                    <?php echo e($loan->borrower_department ?: '-'); ?>

                </div>

            </div>

        </div>


        

        <div class="equipment-title">
            Alat kerja berupa:
        </div>

        <div class="equipment">

            
            <div class="equipment-row">

                <div class="equipment-label">
                    Nama Barang
                </div>

                <div class="equipment-separator">
                    :
                </div>

                <div class="equipment-value">

                    <?php echo e($loan->asset->name); ?>


                    <?php if($loan->asset->brand || $loan->asset->model): ?>

                        -
                        <?php echo e($loan->asset->brand); ?>


                        <?php if($loan->asset->model): ?>
                            <?php echo e($loan->asset->model); ?>

                        <?php endif; ?>

                    <?php endif; ?>

                </div>

            </div>


            
            <div class="equipment-row">

                <div class="equipment-label">
                    Serial Number
                </div>

                <div class="equipment-separator">
                    :
                </div>

                <div class="equipment-value">
                    <?php echo e($loan->asset->serial_number ?: '-'); ?>

                </div>

            </div>


            
            <div class="equipment-row">

                <div class="equipment-label">
                    Spesifikasi
                </div>

                <div class="equipment-separator">
                    :
                </div>

                <div class="equipment-value">
                    <?php echo e($loan->asset->description ?: '-'); ?>

                </div>

            </div>


            
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


        

        <div class="closing">

            Demikian berita acara serah terima ini dibuat untuk dapat diketahui
            dan digunakan sebagaimana mestinya.

        </div>


        

        <div class="signature-date">

            <?php echo e($loan->borrowed_at->translatedFormat('l')); ?>,
            <?php echo e($loan->borrowed_at->translatedFormat('d F Y')); ?>


        </div>


        

        <table class="signature-table">

            <tr>

                

                <td>

                    <strong>
                        Pemberi,
                    </strong>

                    <div class="signature-space">

                        <?php if(!empty($itSupportSignature) && is_file($itSupportSignature)): ?>
                            <img src="<?php echo e($itSupportSignature); ?>" alt="Tanda Tangan IT Support" class="signature-image">
                        <?php endif; ?>

                    </div>

                    <div class="signature-name">
                        Muhamad Ariel Saputra
                    </div>

                    <div class="signature-position">
                        IT Support
                    </div>

                </td>


                

                <td>

                    <strong>
                        Penerima,
                    </strong>

                    <div class="signature-space">

                        <?php if(!empty($signaturePath) && is_file($signaturePath)): ?>
                            <img src="<?php echo e($signaturePath); ?>" alt="Tanda Tangan Peminjam" class="signature-image">
                        <?php endif; ?>

                    </div>

                    <div class="signature-name">
                        <?php echo e($loan->borrower_name); ?>

                    </div>

                    <div class="signature-position">
                        <?php echo e($loan->borrower_position ?: 'Karyawan'); ?>

                    </div>

                </td>

            </tr>

        </table>

    </div>

</body>

</html>
<?php /**PATH /www/wwwroot/asset.adb.web.id/resources/views/loans/pdf.blade.php ENDPATH**/ ?>