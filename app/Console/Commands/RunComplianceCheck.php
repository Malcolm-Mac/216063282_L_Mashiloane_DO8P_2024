<?php

namespace App\Console\Commands;

use App\Models\ComplianceCheck;
use App\Models\Computer;
use Illuminate\Console\Command;

class RunComplianceCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compliance:check {computerId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run compliance checks on the specified computer';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $computerId = $this->argument('computerId');
        $computer = Computer::findOrFail($computerId);

        $ipAddress = $computer->ip_address;

        // Perform the compliance checks (this is where you implement your logic)
        $complianceResults = $this->performComplianceCheck($ipAddress);

        // Save the results in the ComplianceCheck model
        foreach ($complianceResults as $benchmark => $status) {
            ComplianceCheck::create([
                'computer_id' => $computer->id,
                'parameter' => $benchmark,
                'status' => $status,
            ]);
        }

        $this->info('Compliance checks completed for computer: ' . $computer->name);
    }

    private function performComplianceCheck($ipAddress)
    {
        $ssh = new \phpseclib3\Net\SSH2($ipAddress);

        /* if (!$ssh->login('username', 'password')) {
            return [
                'Connection' => 'Failed',
            ];
        } */

        // Example commands to check compliance
        $firewallStatus = $ssh->exec('sudo ufw status');
        $antivirusStatus = $ssh->exec('sudo systemctl status clamav');

        return [
            'Firewall Status' => strpos($firewallStatus, 'active') !== false ? 'Compliant' : 'Non-compliant',
            'Antivirus Status' => strpos($antivirusStatus, 'running') !== false ? 'Compliant' : 'Non-compliant',
        ];
    }
}
