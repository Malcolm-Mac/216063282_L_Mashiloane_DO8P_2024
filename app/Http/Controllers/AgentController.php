<?php

namespace App\Http\Controllers;

use App\Models\ComplianceCheck;
use App\Models\Computer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    public function triggerAgent($os)
    {
        // Define the path to your agent scripts
        $scriptPath = base_path("agents/");

        switch ($os) {
            case 'linux':
                $scriptPath .= 'linux_agent.sh';
                break;
            case 'macos':
                $scriptPath .= 'macos_agent.sh';
                break;
            case 'windows':
                $scriptPath .= 'windows_agent.ps1'; // Adjust the path if necessary
                break;
            default:
                return response()->json(['error' => 'Invalid OS specified'], 400);
        }

        // Execute the script based on the OS type
        $output = '';

        if ($os === 'windows') {
            // For Windows, use powershell to execute the script
            $output = shell_exec("powershell -ExecutionPolicy Bypass -File " . escapeshellarg($scriptPath));
        } else {
            // For Linux and macOS
            $output = shell_exec("bash " . escapeshellarg($scriptPath));
        }

        // Log the output for debugging
        Log::info('Agent output for ' . $os . ': ' . $output);

        return response()->json(['output' => $output]);
    }

    public function report(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'ip_address' => 'required|string',
            'cpu_usage' => 'required|string',
            'mem_usage' => 'required|string',
            'disk_usage' => 'required|string',
        ]);

        $computer = Computer::where('ip_address', $validated['ip_address'])->first();

        if (!$computer) {
            return response()->json(['message' => 'Computer not found.'], 404);
        }

        // Create a new compliance check record
        $complianceCheck = ComplianceCheck::create([
            'computer_id' => $computer->id,
            'parameter' => 'CPU Usage',
            'status' => $this->getStatus($validated['cpu_usage']), // Implement getStatus() based on your logic
            'details' => json_encode($validated), // Store all details as JSON
        ]);

        // You can add more checks for memory and disk usage here if needed

        return response()->json(['message' => 'Report received successfully.', 'data' => $complianceCheck], 201);
    }

    protected function getComputerId($os)
    {
        // Logic to retrieve the computer ID based on the OS or other identifiers
        // This could involve looking up the computer in your database
        // Example placeholder logic:
        return 1; // Replace with actual lookup logic
    }

    protected function getStatus($cpuUsage)
    {
        // Logic to determine compliance status based on CPU usage or any benchmarks you have set
        return ($cpuUsage < 80) ? 'compliant' : 'non-compliant'; // Example logic
    }
}
