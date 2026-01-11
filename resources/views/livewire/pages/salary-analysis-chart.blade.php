<div>

    <div class="mb-4">
        <label for="yearFilter" class="block text-sm font-medium text-gray-700">Select Year</label>
        <select
            wire:model.live="selectedYear"
            id="yearFilter"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
        >
            @foreach($availableYears as $year)
                <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-white p-4 rounded-lg shadow">
        <div wire:ignore x-data="{
            chart: null,
            init() {
                this.renderChart();

                Livewire.on('refreshChart', () => {
                    this.renderChart();
                });
            },
            renderChart() {
                if (this.chart) {
                    this.chart.destroy();
                }

                const ctx = this.$refs.canvas.getContext('2d');
                this.chart = new Chart(ctx, {
                    type: 'line',
                    data: @js($chartData),
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Salary Analysis for {{ $selectedYear }}',
                                font: {
                                    size: 16
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': $' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '#' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }" style="position: relative; height: 400px; width: 100%">
            <canvas x-ref="canvas"></canvas>
        </div>
    </div>


{{--    @push('scripts')--}}
{{--        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}
{{--        --}}{{--    window.{{ $chart->id }}--}}
{{--        <script>--}}
{{--            window.addEventListener('refreshChart', () => {--}}
{{--                Alpine.store('chart').renderChart();--}}
{{--            });--}}
{{--        </script>--}}
{{--    @endpush--}}
{{--</div>--}}

{{--<div>--}}
{{--    <div class="mb-4">--}}
{{--        <label for="yearFilter" class="block text-sm font-medium text-gray-700">Select Year</label>--}}
{{--        <select--}}
{{--            wire:model.live="selectedYear"--}}
{{--            id="yearFilter"--}}
{{--            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"--}}
{{--        >--}}
{{--            @foreach($availableYears as $year)--}}
{{--                <option value="{{ $year }}">{{ $year }}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--    </div>--}}

{{--    <div class="bg-white p-4 rounded-lg shadow">--}}
{{--        <div wire:ignore x-data="{--}}
{{--            chart: null,--}}
{{--            init() {--}}
{{--                this.renderChart();--}}

{{--                // Watch for Livewire updates--}}
{{--                Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {--}}
{{--                    succeed(() => {--}}
{{--                        if (component.id === @this.__instance.id) {--}}
{{--                            this.updateChart();--}}
{{--                        }--}}
{{--                    });--}}
{{--                });--}}
{{--            },--}}
{{--            renderChart() {--}}
{{--                const ctx = this.$refs.canvas.getContext('2d');--}}
{{--                this.chart = new Chart(ctx, {--}}
{{--                    type: 'line',--}}
{{--                    data: @js($chartData),--}}
{{--                    options: {--}}
{{--                        responsive: true,--}}
{{--                        maintainAspectRatio: false,--}}
{{--                        plugins: {--}}
{{--                            title: {--}}
{{--                                display: true,--}}
{{--                                text: 'Salary Analysis for {{ $selectedYear }}',--}}
{{--                                font: {--}}
{{--                                    size: 16--}}
{{--                                }--}}
{{--                            },--}}
{{--                            tooltip: {--}}
{{--                                mode: 'index',--}}
{{--                                intersect: false,--}}
{{--                                callbacks: {--}}
{{--                                    label: function(context) {--}}
{{--                                        return context.dataset.label + ': $' + context.parsed.y.toLocaleString();--}}
{{--                                    }--}}
{{--                                }--}}
{{--                            }--}}
{{--                        },--}}
{{--                        scales: {--}}
{{--                            y: {--}}
{{--                                beginAtZero: true,--}}
{{--                                ticks: {--}}
{{--                                    callback: function(value) {--}}
{{--                                        return '$' + value.toLocaleString();--}}
{{--                                    }--}}
{{--                                }--}}
{{--                            }--}}
{{--                        }--}}
{{--                    }--}}
{{--                });--}}
{{--            },--}}
{{--            updateChart() {--}}
{{--                if (!this.chart) return;--}}

{{--                this.chart.data = @js($chartData);--}}
{{--                this.chart.options.plugins.title.text = 'Salary Analysis for {{ $selectedYear }}';--}}
{{--                this.chart.update();--}}
{{--            }--}}
{{--        }" style="position: relative; height: 400px; width: 100%">--}}
{{--            <canvas x-ref="canvas" id="{{ $chartId }}"></canvas>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
