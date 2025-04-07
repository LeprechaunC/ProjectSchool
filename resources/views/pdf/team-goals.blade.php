<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $team->name }} Goals</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #333;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 10px;
            color: #666;
        }
        .team-info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .priority-high {
            color: #dc3545;
        }
        .priority-medium {
            color: #fd7e14;
        }
        .priority-low {
            color: #28a745;
        }
        .status-done {
            color: #28a745;
        }
        .status-pending {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $team->name }} - Team Goals Report</h1>
        <div class="subtitle">Generated on {{ date('F d, Y H:i:s') }}</div>
    </div>
    
    <div class="team-info">
        <p><strong>Team Name:</strong> {{ $team->name }}</p>
        <p><strong>Team Owner:</strong> {{ $team->owner }}</p>
        <p><strong>Members:</strong> {{ $team->users->count() }}</p>
        <p><strong>Total Goals:</strong> {{ count($goals) }}</p>
    </div>
    
    @if(count($goals) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 15%;">Title</th>
                    <th style="width: 25%;">Description</th>
                    <th style="width: 15%;">Start Time</th>
                    <th style="width: 15%;">End Time</th>
                    <th style="width: 10%;">Assigned To</th>
                    <th style="width: 7.5%;">Priority</th>
                    <th style="width: 7.5%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($goals as $index => $goal)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $goal->title }}</td>
                        <td>{{ $goal->description }}</td>
                        <td>{{ date('M d, Y H:i', strtotime($goal->start_time)) }}</td>
                        <td>{{ date('M d, Y H:i', strtotime($goal->end_time)) }}</td>
                        <td>{{ $goal->user->name ?? 'Unassigned' }}</td>
                        <td class="priority-{{ strtolower($goal->priority) }}">{{ ucfirst($goal->priority) }}</td>
                        <td class="status-{{ $goal->done ? 'done' : 'pending' }}">{{ $goal->done ? 'Completed' : 'Pending' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center;">No team goals found.</p>
    @endif
    
    <div class="footer">
        <p>This document was automatically generated from your Goal Management System.</p>
    </div>
</body>
</html> 