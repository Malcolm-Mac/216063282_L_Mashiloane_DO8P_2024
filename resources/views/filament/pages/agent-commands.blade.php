<x-filament-panels::page>
    <div>
        <h2>Execute Remote Command</h2>
        {{ $this->form }}

        <div class="mt-6">
            <h3>Command Output:</h3>
            <pre>{{ $output }}</pre>
        </div>
    </div>
</x-filament-panels::page>
