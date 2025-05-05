<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- SweetAlert Udah pake local di app.js -->

    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
    {{-- fontawesome --}}
    <script src="https://kit.fontawesome.com/a45f001349.js" crossorigin="anonymous"></script>

    {{-- css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-flags.min.css?1667333929') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-payments.min.css?1667333929') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-vendors.min.css?1667333929') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.min.css?1667333929') }}">

    <link rel="stylesheet" href="{{ asset('assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">



    <script src="{{ asset('assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/list.js/dist/list.min.js?1684106062') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
    </style>

    {{-- sssss --}}
    {{-- <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet"> --}}
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/js/sihub-datatable.js') }}"></script>

    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/js/app.js')
</head>

<body>
    {{-- @include('sweetalert::alert') --}}
    <script src="{{ asset('assets/js/demo-theme.min.js?1667333929') }}"></script>
    {{-- sweet alert mas --}}
    @if (session('success'))
        <script>
            Swal.fire("SweetAlert2 is working!");
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @elseif(session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    {{-- end sweet alert mas --}}
    <div id="app">
        {{-- gawe nyeluk component navbar --}}
        <x-navbar />
        <main class="py-4">
            {{ $slot }}
        </main>
    </div>
</body>
<!-- Libs JS -->
<script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js?1684106062') }}"></script>
<script src="{{ asset('assets/libs/jsvectormap/dist/js/jsvectormap.min.js?1667333929') }}"></script>
<script src="{{ asset('assets/libs/jsvectormap/dist/maps/world.js?1667333929') }}"></script>
<script src="{{ asset('assets/libs/jsvectormap/dist/maps/world-merc.js?1667333929') }}"></script>

<script src="{{ asset('assets/libs/nouislider/dist/nouislider.min.js?1667333929') }}" defer></script>
<script src="{{ asset('assets/libs/litepicker/dist/litepicker.js?1667333929') }}" defer></script>
<script src="{{ asset('assets/libs/tom-select/dist/js/tom-select.base.min.js?1667333929') }}" defer></script>
<script src="{{ asset('assets/js/tabler.min.js?1667333929') }}"></script>
<script src="{{ asset('assets/js/demo.min.js?1667333929') }}"></script>

<script>
    $(document).ready(function() {
        $(".data-table").each(function() {
            if (!$.fn.DataTable.isDataTable(this)) {
                $(this).DataTable({
                    dom: "<'.row'<'col-12 col-md-6 pb-2'l><'col-12 col-md-4 ms-auto pb-2'f>><'.row'<'col-12'tr>><'.row'<'col-4'i><'col-8'p>>",
                    language: {
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Data ke _START_ - _END_ dari _TOTAL_",
                        infoFiltered: "(disaring dari total _MAX_ data)",
                        emptyTable: "Tidak ada data",
                        infoEmpty: "Menampilkan 0 data",
                        zeroRecords: "Data tidak ditemukan",
                    },
                    pageLength: 25,
                    autoWidth: false,
                });
            }
        });
    });
</script>

</html>
