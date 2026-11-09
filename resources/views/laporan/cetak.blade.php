<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Laporan &middot; SPK Siswa Berprestasi</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body style="background:#fff;">
    <div class="container my-4">
        <div class="d-flex justify-content-end gap-2 mb-3 no-print">
            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak Sekarang
            </button>
            <button type="button" class="btn btn-outline-secondary" onclick="window.close()">
                Tutup
            </button>
        </div>

        @include('laporan._report')
    </div>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () { window.print(); }, 400);
        });
    </script>
</body>
</html>
