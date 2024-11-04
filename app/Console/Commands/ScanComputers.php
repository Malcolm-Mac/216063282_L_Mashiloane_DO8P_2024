<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NetworkScan;
use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ScanComputers extends Command
{
    protected $signature = 'network:scan';
    protected $description = 'Scan the local network for connected devices';

    public function handle()
    {
        // Define subnet for scanning (e.g., 192.168.1.0/24)
        $localIp = gethostbyname(gethostname());
        $subnet = substr($localIp, 0, strrpos($localIp, '.') + 1) . '0/24'; // /24 covers 256 addresses in the subnet

        // Start the scan
        $command = "nmap -sn $subnet";
        $output = [];
        exec($command, $output);

        // Initialize network scan
        $networkScan = NetworkScan::create([
            'scan_date' => Carbon::now(),
            'status' => 'in_progress',
            'total_devices_found' => 0,
            'total_online_devices' => 0,
            'scan_duration' => null,
            'scan_errors' => null,
            'network_range' => $subnet,
            'initiated_by' => auth()->id() ?? 1, // Replace with authenticated user ID
        ]);

        // Parse scan output
        $devices = [];
        $currentDevice = [];
        foreach ($output as $line) {
            // Check for IP address
            if (preg_match('/Nmap scan report for (.+)/', $line, $matches)) {
                if (!empty($currentDevice)) {
                    $devices[] = $currentDevice;
                }
                $currentDevice = ['device_ip' => trim($matches[1])];

                // Get MAC address using nmap if it's not present in the initial scan output
                $currentDevice['device_mac'] = $this->getMacAddressNmap($currentDevice['device_ip']);
            }

            // Check for MAC address
            if (preg_match('/MAC Address: (.+?) /', $line, $matches)) {
                $currentDevice['device_mac'] = trim($matches[1]);
            }

            // Device name (if available)
            if (preg_match('/\((.+)\)/', $line, $matches)) {
                $currentDevice['device_name'] = trim($matches[1]);
            }
        }

        // Add last found device
        if (!empty($currentDevice)) {
            $devices[] = $currentDevice;
        }

        // Save discovered devices and count results
        $networkScan->update([
            'status' => 'completed',
            'total_devices_found' => count($devices),
            'total_online_devices' => count($devices),
            'scan_duration' => round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 2),
        ]);

        foreach ($devices as $device) {
            if (isset($device['device_ip'])) {
                Device::create([
                    'network_scan_id' => $networkScan->id,
                    'device_ip' => $device['device_ip'],
                    'device_mac' => $device['device_mac'] ?? null,
                    'device_name' => $device['device_name'] ?? null,
                    'device_type' => $this->getDeviceType($device['device_name'] ?? ''),
                    'status' => 'online',
                ]);
            } else {
                Log::warning('Device IP not found for a device entry, skipping.');
            }
        }

        // Log the results
        if (count($devices) > 0) {
            foreach ($devices as $device) {
                Log::info("IP: " . ($device['device_ip'] ?? 'N/A') . ", MAC: " . ($device['device_mac'] ?? 'N/A') . ", Name: " . ($device['device_name'] ?? 'N/A'));
            }
        } else {
            Log::info('No devices found on the network.');
        }

        Log::info('Network scan completed successfully.');

        return 0;
    }

    /**
     * Get the MAC address for a specific IP address using nmap.
     *
     * @param string $ipAddress
     * @return string|null
     */
    private function getMacAddressNmap($ipAddress)
    {
        // Run nmap command to get MAC address from IP
        $output = shell_exec("nmap -sP $ipAddress");

        // Parse output for MAC address
        if (preg_match('/MAC Address: (.*) /', $output, $matches)) {
            return trim($matches[1]);
        }

        return null; // Return null if MAC address is not found
    }

    /**
     * Helper function to categorize device type based on device name.
     *
     * @param string $deviceName
     * @return string
     */
    private function getDeviceType(string $deviceName): string
    {
        if (stripos($deviceName, 'phone') !== false) {
            return 'Mobile Phone';
        } elseif (stripos($deviceName, 'tablet') !== false) {
            return 'Tablet';
        } elseif (stripos($deviceName, 'laptop') !== false || stripos($deviceName, 'desktop') !== false) {
            return 'Computer';
        } elseif (stripos($deviceName, 'printer') !== false) {
            return 'Printer';
        }
        return 'Unknown';
    }
}
