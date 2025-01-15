<div class="card">
    <div class="card-body">
        <h5 class="card-title">SEO Analyzer</h5>

        <!-- Input field for the URL -->
        <div class="mb-3">
            <label for="url" class="form-label">Enter a URL to analyze</label>
            <input type="text" id="url" wire:model="url" class="form-control" placeholder="e.g., https://example.com">
        </div>

        <!-- Analyze button -->
        <button wire:click="analyze" class="btn btn-primary" wire:loading.attr="disabled">
            Analyze
        </button>

        <!-- Loading Spinner (shown while Livewire is processing the action) -->
        <div wire:loading wire:target="analyze">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Analyzing...</span>
            </div>
            <span>Analyzing...</span>
        </div>

        <!-- Display Results -->
        @if ($results || $errors)
        <h6 class="mt-3">Analysis Results:</h6>

        @if ($errors)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if ($brokenLinks)
        <table class="table mt-4">
            <thead>
                <tr>
                    <th scope="col">Broken Links</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($brokenLinks as $link)
                <tr>
                    <td><a href="{{ $link }}" target="_blank" class="text-danger">{{ $link }}</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Results Table -->
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>SEO Element</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $key => $value)
                <tr>
                    <td>{{ $key }}</td>
                    <td>
                        @if (is_array($value))
                        <ul>
                            @foreach ($value as $item)
                            <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                        @else
                        {{ $value }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>



        <!-- Website Performance -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Website Performance</h5>

                <!-- Display Performance Metrics -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Metric</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Page Load Time</td>
                            <td>{{ $performanceData['Page Load Time'] ?? 'N/A' }} ms</td>
                        </tr>
                        <tr>
                            <td>First Contentful Paint (FCP)</td>
                            <td>{{ $performanceData['FCP'] ?? 'N/A' }} ms</td>
                        </tr>
                        <tr>
                            <td>Largest Contentful Paint (LCP)</td>
                            <td>{{ $performanceData['LCP'] ?? 'N/A' }} ms</td>
                        </tr>
                        <tr>
                            <td>Time to Interactive (TTI)</td>
                            <td>{{ $performanceData['TTI'] ?? 'N/A' }} ms</td>
                        </tr>
                        <tr>
                            <td>Cumulative Layout Shift (CLS)</td>
                            <td>{{ $performanceData['CLS'] ?? 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Performance Chart -->
            </div>
        </div>
        @endif


        <canvas id="performanceChart" width="400" height="200" class="d-none"></canvas>

    </div>
</div>

<script>
    document.addEventListener('livewire:init', function() {
        let performanceChart; // Declare a local variable to store the chart instance

        // Listen for the 'performanceUpdated' event dispatched from Livewire
        Livewire.on('performanceUpdated', (metrics) => {
            document.getElementById('performanceChart').style.display = 'block'; // Show the chart canvas
            console.log('Performance Metrics:', metrics);

            // Check if metrics is an array and has at least one element
            if (Array.isArray(metrics) && metrics.length > 0) {
                const performanceData = metrics[0]; // Get the first object from the array

                // Log the performance data to ensure it's correct
                console.log('Page Load Time:', performanceData['Page Load Time']);
                console.log('FCP:', performanceData['FCP']);
                console.log('LCP:', performanceData['LCP']);
                console.log('TTI:', performanceData['TTI']);
                console.log('CLS:', performanceData['CLS']);

                // Process data to ensure they are all numbers (for proper chart rendering)
                const pageLoadTime = parseInt(performanceData['Page Load Time']) || 0;
                const fcp = parseInt(performanceData['FCP']) || 0;
                const lcp = parseInt(performanceData['LCP']) || 0;
                const tti = parseInt(performanceData['TTI']) || 0;
                const cls = parseFloat(performanceData['CLS'].split(' ')[0]) || 0; // Get the numeric part of CLS

                // Get chart context
                var ctx = document.getElementById('performanceChart').getContext('2d');

                // If the chart already exists, destroy it before creating a new one
                if (performanceChart) {
                    performanceChart.destroy();
                }

                // Create a new chart
                performanceChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Page Load Time', 'FCP', 'LCP', 'TTI', 'CLS'],
                        datasets: [{
                            label: 'Performance Metrics',
                            data: [pageLoadTime, fcp, lcp, tti, cls],
                            backgroundColor: ['#4caf50', '#4caf50', '#4caf50', '#f44336', '#4caf50'],
                            borderColor: ['#388e3c', '#388e3c', '#388e3c', '#d32f2f', '#388e3c'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    // Set max value for Y axis
                                    max: Math.max(pageLoadTime, fcp, lcp, tti, cls) + 500
                                }
                            }
                        }
                    }
                });
            } else {
                console.error('Performance metrics data is missing or incorrectly formatted:', metrics);
            }
        });
    });
</script>