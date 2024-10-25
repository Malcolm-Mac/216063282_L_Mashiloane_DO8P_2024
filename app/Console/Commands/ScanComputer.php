<?php

namespace App\Console\Commands;

use App\Models\Computer;
use App\Models\ComplianceBenchmark;
use App\Models\ComplianceCheck;
use Illuminate\Console\Command;

class ScanComputer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:computer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scans the computer for compliance with benchmarks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting scan...');

        // Gather system data
        $os = PHP_OS;
        $computerName = gethostname();
        $ipAddress = gethostbyname(gethostname());

        $ram = ini_get('memory_limit'); // Example for gathering RAM
        $diskFree = disk_free_space("/");
        $diskTotal = disk_total_space("/");

        // Save or update the computer in the database
        $computer = Computer::updateOrCreate(
            ['name' => $computerName],
            [
                'os' => $os,
                'ip_address' => $ipAddress,
            ]
        );

        // Fetch the benchmarks for the computer's OS
        $benchmarks = ComplianceBenchmark::where('os', $os)->get();

        // Loop through benchmarks and check compliance
        foreach ($benchmarks as $benchmark) {
            $complianceStatus = $this->checkCompliance($benchmark, $ram, $diskFree, $diskTotal);

            // Save the compliance check result
            ComplianceCheck::create([
                'computer_id' => $computer->id,
                'parameter' => $benchmark->parameter,
                'status' => $complianceStatus ? 'compliant' : 'non-compliant',
                'details' => "Condition: {$benchmark->condition} {$benchmark->value}, Measured: " . $this->getParameterValue($benchmark->parameter, $ram, $diskFree, $diskTotal)
            ]);
        }

        $this->info('Scan completed.');
    }

    private function checkCompliance($benchmark, $ram, $diskFree, $diskTotal)
    {
        $value = $this->getParameterValue($benchmark->parameter, $ram, $diskFree, $diskTotal);

        // Evaluate compliance based on the condition and value
        switch ($benchmark->condition) {
            case '>=':
                return $value >= $benchmark->value;
            case '<=':
                return $value <= $benchmark->value;
            case '==':
                return $value == $benchmark->value;
            default:
                return false;
        }
    }

    private function getParameterValue($parameter, $ram, $diskFree, $diskTotal)
    {
        switch ($parameter) {
            case 'RAM':
                return $ram;
            case 'Disk Space':
                return $diskFree;
            default:
                return null;
        }
    }
}
