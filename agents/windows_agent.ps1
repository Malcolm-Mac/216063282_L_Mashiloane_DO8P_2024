# Gather system information
$cpu_usage = Get-WmiObject Win32_Processor | Measure-Object -property LoadPercentage -Average | Select Average
$mem_info = Get-WmiObject Win32_OperatingSystem
$disk_usage = Get-WmiObject Win32_LogicalDisk -Filter "DeviceID='C:'"

$mem_usage = ($mem_info.TotalVisibleMemorySize - $mem_info.FreePhysicalMemory) / $mem_info.TotalVisibleMemorySize * 100
$disk_usage = [math]::round(($disk_usage.Size - $disk_usage.FreeSpace) / $disk_usage.Size * 100, 2)

# API endpoint (Laravel backend)
$api_url = "http://localhost:8000/api/agent/report"

# Send system information to Laravel
$payload = @{
    os = "windows"
    cpu_usage = $cpu_usage.Average
    mem_usage = "$mem_usage%"
    disk_usage = "$disk_usage%"
} | ConvertTo-Json

Invoke-RestMethod -Uri $api_url -Method Post -ContentType "application/json" -Body $payload

# Command execution
$command_url = "http://localhost:8000/api/agent/command/windows"
$command = Invoke-RestMethod -Uri $command_url -Method Get
Invoke-Expression $command
