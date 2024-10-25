#!/bin/bash

# Gather system information
cpu_usage=$(top -bn1 | grep "Cpu(s)" | sed "s/.*, *\([0-9.]*\)%* id.*/\1/" | awk '{print 100 - $1"%"}')
mem_usage=$(free -m | awk '/Mem:/ { printf("%3.1f%%", $3/$2*100) }')
disk_usage=$(df -h | awk '$NF=="/"{printf "%s\t\t", $5}')

# API endpoint (Laravel backend)
api_url="http://localhost:8000/api/agent/report"

# Send data to the Laravel API
curl -X POST $api_url \
  -H "Content-Type: application/json" \
  -d '{"os": "linux", "cpu_usage": "'"$cpu_usage"'", "mem_usage": "'"$mem_usage"'", "disk_usage": "'"$disk_usage"'"}'

# Command execution (example: run remote command from Laravel)
command=$(curl -s "http://localhost:8000/api/agent/command/linux")
eval $command
