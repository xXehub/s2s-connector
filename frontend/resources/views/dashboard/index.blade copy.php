<x-app>
    <div class="page-wrapper">
        <div class="container-xl">
            <!-- Page title -->
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Dashboard
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <!-- Summary Cards -->
                <div class="row row-deck row-cards mb-4">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total Users</div>
                                </div>
                                <div class="h1 mb-3">{{ $totalUsers ?? 0 }}</div>
                                <div class="d-flex mb-2">
                                    <div>Active users</div>
                                    <div class="ms-auto">
                                        <span class="text-green d-inline-flex align-items-center lh-1">
                                            {{ $activeUsers ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total Products</div>
                                </div>
                                <div class="h1 mb-3">{{ $totalProducts ?? 0 }}</div>
                                <div class="d-flex mb-2">
                                    <div>In stock</div>
                                    <div class="ms-auto">
                                        <span class="text-green d-inline-flex align-items-center lh-1">
                                            {{ $inStockProducts ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total Orders</div>
                                </div>
                                <div class="h1 mb-3">{{ $totalOrders ?? 0 }}</div>
                                <div class="d-flex mb-2">
                                    <div>Completed</div>
                                    <div class="ms-auto">
                                        <span class="text-green d-inline-flex align-items-center lh-1">
                                            {{ $completedOrders ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="subheader">Total Revenue</div>
                                </div>
                                <div class="h1 mb-3">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                                <div class="d-flex mb-2">
                                    <div>This month</div>
                                    <div class="ms-auto">
                                        <span class="text-green d-inline-flex align-items-center lh-1">
                                            Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Recent Orders</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped" id="recent-orders-datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="w-1">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders ?? [] as $order)
                                    <tr>
                                        <td>{{ $order['id'] }}</td>
                                        {{-- <td>{{ $order['user_name'] }}</td>
                                        <td>{{ $order['total'] }}</td> --}}
                                        {{-- <td>
                                            <span class="badge bg-{{ $order['status'] == 'completed' ? 'success' : ($order['status'] == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($order['status']) }}
                                            </span>
                                        </td> --}}
                                        <td>{{ $order['created_at'] }}</td>
                                        <td>
                                            <a href="{{ route('orders.show', $order['id']) }}" class="btn btn-sm btn-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No recent orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Top Products</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped" id="top-products-datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Sales</th>
                                    <th class="w-1">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts ?? [] as $product)
                                    <tr>
                                        <td>{{ $product['id'] }}</td>
                                        <td>{{ $product['name'] }}</td>
                                        <td>{{ $product['price'] }}</td>
                                        <td>{{ $product['stock'] }}</td>
                                        <td>{{ $product['sales'] }}</td>
                                        <td>
                                            <a href="{{ route('products.edit', $product['id']) }}" class="btn btn-sm btn-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No top products found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <x-footer />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi datatable untuk recent orders
            if (typeof $.fn.dataTable !== 'undefined') {
                // Initialize DataTables for recent orders
                $('#recent-orders-datatable').DataTable({
                    responsive: true,
                    pageLength: 5,
                    lengthMenu: [
                        [5, 10, 25, -1],
                        [5, 10, 25, "All"]
                    ],
                    dom: '<"card-body border-bottom py-3"<"d-flex"<"text-muted custom-length"l><"ms-auto text-muted"f>>>' +
                        '<"table-responsive"t>' +
                        '<"card-footer d-flex align-items-center"<"m-0 text-muted"i><"custom-pagination m-0 ms-auto"p>>',
                    language: {
                        lengthMenu: "_MENU_" // Keep this minimal as we'll replace it
                    },
                    drawCallback: function(settings) {
                        // Get pagination info
                        var api = this.api();
                        var info = api.page.info();

                        // Get current page
                        var currentPage = info.page;
                        var totalPages = info.pages;

                        // Clear existing pagination
                        var paginationContainer = $(this).closest('.dataTables_wrapper').find(
                            '.custom-pagination');
                        paginationContainer.empty();

                        // Create Tabler pagination
                        var paginationHtml = '<ul class="pagination m-0 ms-auto">';

                        // Previous button
                        var prevDisabled = currentPage === 0 ? 'disabled' : '';
                        paginationHtml += '<li class="page-item ' + prevDisabled + '">' +
                            '<a class="page-link" href="#" tabindex="-1" aria-disabled="' + (
                                prevDisabled === 'disabled') + '">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" ' +
                            'stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
                            '<path stroke="none" d="M0 0h24v24H0z" fill="none" />' +
                            '<path d="M15 6l-6 6l6 6" />' +
                            '</svg> prev</a></li>';

                        // Page numbers
                        var startPage = Math.max(0, Math.min(currentPage - 2, totalPages - 5));
                        var endPage = Math.min(totalPages - 1, startPage + 4);

                        for (var i = startPage; i <= endPage; i++) {
                            var activeClass = i === currentPage ? 'active' : '';
                            paginationHtml += '<li class="page-item ' + activeClass + '">' +
                                '<a class="page-link" href="#">' + (i + 1) + '</a></li>';
                        }

                        // Next button
                        var nextDisabled = currentPage >= totalPages - 1 ? 'disabled' : '';
                        paginationHtml += '<li class="page-item ' + nextDisabled + '">' +
                            '<a class="page-link" href="#">next ' +
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" ' +
                            'stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
                            '<path stroke="none" d="M0 0h24v24H0z" fill="none" />' +
                            '<path d="M9 6l6 6l-6 6" />' +
                            '</svg></a></li>';

                        paginationHtml += '</ul>';

                        // Add pagination to container
                        paginationContainer.html(paginationHtml);

                        // Add click event to pagination buttons
                        paginationContainer.find('.page-item:not(.disabled) .page-link').on('click',
                            function(e) {
                                e.preventDefault();
                                var $this = $(this);
                                var pageText = $this.text().trim();

                                if (pageText.indexOf('prev') !== -1) {
                                    api.page('previous').draw('page');
                                } else if (pageText.indexOf('next') !== -1) {
                                    api.page('next').draw('page');
                                } else {
                                    var page = parseInt(pageText) - 1;
                                    api.page(page).draw('page');
                                }
                            });

                        // Format the info text with spans
                        var infoContainer = $(this).closest('.dataTables_wrapper').find(
                            '.dataTables_info');
                        var infoText = infoContainer.text();
                        var parts = infoText.match(/Showing (\d+) to (\d+) of (\d+) entries/);

                        if (parts && parts.length === 4) {
                            infoContainer.html('Showing <span>' + parts[1] + '</span> to <span>' +
                                parts[2] + '</span> of <span>' + parts[3] + '</span> entries');
                        }

                        // Fix the length menu if it exists
                        fixLengthMenu($(this));
                    },
                    initComplete: function() {
                        // Fix search box
                        $('.dataTables_filter').html(
                            '<div class="ms-auto text-muted">Cari: <div class="ms-2 d-inline-block"><input type="text" class="form-control form-control-sm custom-search" aria-label="Search"></div></div>'
                        );

                        // Bind custom search
                        $('.custom-search').on('keyup', function() {
                            var tableId = $(this).closest('.dataTables_wrapper').find('table')
                                .attr('id');
                            $('#' + tableId).DataTable().search(this.value).draw();
                        });

                        // Fix length menu initially
                        fixLengthMenu($(this));
                    }
                });

                // Initialize DataTables for top products
                $('#top-products-datatable').DataTable({
                    responsive: true,
                    pageLength: 5,
                    lengthMenu: [
                        [5, 10, 25, -1],
                        [5, 10, 25, "All"]
                    ],
                    dom: '<"card-body border-bottom py-3"<"d-flex"<"text-muted custom-length"l><"ms-auto text-muted"f>>>' +
                        '<"table-responsive"t>' +
                        '<"card-footer d-flex align-items-center"<"m-0 text-muted"i><"custom-pagination m-0 ms-auto"p>>',
                    language: {
                        lengthMenu: "_MENU_" // Keep this minimal as we'll replace it
                    },
                    drawCallback: function(settings) {
                        // Get pagination info
                        var api = this.api();
                        var info = api.page.info();

                        // Get current page
                        var currentPage = info.page;
                        var totalPages = info.pages;

                        // Clear existing pagination
                        var paginationContainer = $(this).closest('.dataTables_wrapper').find(
                            '.custom-pagination');
                        paginationContainer.empty();

                        // Create Tabler pagination
                        var paginationHtml = '<ul class="pagination m-0 ms-auto">';

                        // Previous button
                        var prevDisabled = currentPage === 0 ? 'disabled' : '';
                        paginationHtml += '<li class="page-item ' + prevDisabled + '">' +
                            '<a class="page-link" href="#" tabindex="-1" aria-disabled="' + (
                                prevDisabled === 'disabled') + '">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" ' +
                            'stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
                            '<path stroke="none" d="M0 0h24v24H0z" fill="none" />' +
                            '<path d="M15 6l-6 6l6 6" />' +
                            '</svg> prev</a></li>';

                        // Page numbers
                        var startPage = Math.max(0, Math.min(currentPage - 2, totalPages - 5));
                        var endPage = Math.min(totalPages - 1, startPage + 4);

                        for (var i = startPage; i <= endPage; i++) {
                            var activeClass = i === currentPage ? 'active' : '';
                            paginationHtml += '<li class="page-item ' + activeClass + '">' +
                                '<a class="page-link" href="#">' + (i + 1) + '</a></li>';
                        }

                        // Next button
                        var nextDisabled = currentPage >= totalPages - 1 ? 'disabled' : '';
                        paginationHtml += '<li class="page-item ' + nextDisabled + '">' +
                            '<a class="page-link" href="#">next ' +
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" ' +
                            'stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
                            '<path stroke="none" d="M0 0h24v24H0z" fill="none" />' +
                            '<path d="M9 6l6 6l-6 6" />' +
                            '</svg></a></li>';

                        paginationHtml += '</ul>';

                        // Add pagination to container
                        paginationContainer.html(paginationHtml);

                        // Add click event to pagination buttons
                        paginationContainer.find('.page-item:not(.disabled) .page-link').on('click',
                            function(e) {
                                e.preventDefault();
                                var $this = $(this);
                                var pageText = $this.text().trim();

                                if (pageText.indexOf('prev') !== -1) {
                                    api.page('previous').draw('page');
                                } else if (pageText.indexOf('next') !== -1) {
                                    api.page('next').draw('page');
                                } else {
                                    var page = parseInt(pageText) - 1;
                                    api.page(page).draw('page');
                                }
                            });

                        // Format the info text with spans
                        var infoContainer = $(this).closest('.dataTables_wrapper').find(
                            '.dataTables_info');
                        var infoText = infoContainer.text();
                        var parts = infoText.match(/Showing (\d+) to (\d+) of (\d+) entries/);

                        if (parts && parts.length === 4) {
                            infoContainer.html('Showing <span>' + parts[1] + '</span> to <span>' +
                                parts[2] + '</span> of <span>' + parts[3] + '</span> entries');
                        }

                        // Fix the length menu if it exists
                        fixLengthMenu($(this));
                    },
                    initComplete: function() {
                        // Fix search box
                        $('.dataTables_filter').html(
                            '<div class="ms-auto text-muted">Cari: <div class="ms-2 d-inline-block"><input type="text" class="form-control form-control-sm custom-search" aria-label="Search"></div></div>'
                        );

                        // Bind custom search
                        $('.custom-search').on('keyup', function() {
                            var tableId = $(this).closest('.dataTables_wrapper').find('table')
                                .attr('id');
                            $('#' + tableId).DataTable().search(this.value).draw();
                        });

                        // Fix length menu initially
                        fixLengthMenu($(this));
                    }
                });

                // Function to fix the length menu
                function fixLengthMenu(tableElement) {
                    var wrapper = tableElement.closest('.dataTables_wrapper');
                    var lengthContainer = wrapper.find('.custom-length');
                    var currentLength = tableElement.DataTable().page.len();

                    // Create custom length input
                    var lengthHtml =
                        'Menampilkan' +
                        '<div class="mx-2 d-inline-block">' +
                        '<input type="text" class="form-control form-control-sm length-input" value="' +
                        currentLength + '" size="3" aria-label="Entries count">' +
                        '</div>' +
                        'data';

                    lengthContainer.html(lengthHtml);

                    // Bind input event for length change
                    lengthContainer.find('.length-input').on('change', function() {
                        var value = parseInt($(this).val());
                        if (isNaN(value) || value < 1) {
                            value = 10; // Default to 10 if invalid
                            $(this).val(value);
                        }
                        tableElement.DataTable().page.len(value).draw();
                    });
                }
            }
        });
    </script>
</x-app>