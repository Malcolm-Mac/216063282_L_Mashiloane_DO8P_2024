#!/bin/bash

# Gather system information
cpu_usage=$(ps -A -o %cpu | awk '{s+=$1} END {print s}')
mem_usage=$(vm_stat | grep "free:" | awk '{print $3}' | sed 's/\.//g')
disk_usage=$(df -h / | tail -1 | awk '{print $5}')

# API endpoint (Laravel backend)
api_url="http://localhost:8000/api/agent/report"

# Send data to the Laravel API
curl -X POST $api_url \
  -H "Content-Type: application/json" \
  -d '{"os": "macos", "cpu_usage": "'"$cpu_usage"'", "mem_usage": "'"$mem_usage"'", "disk_usage": "'"$disk_usage"'"}'

# Command execution (example: run remote command from Laravel)
command=$(curl -s "http://localhost:8000/api/agent/command/macos")
eval $command
