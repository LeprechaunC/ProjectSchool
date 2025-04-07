<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>System Report</title>
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
        h2 {
            font-size: 18px;
            margin-top: 30px;
            margin-bottom: 15px;
            color: #444;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }
        .overview-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .overview-box {
            width: 30%;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            text-align: center;
            border-radius: 5px;
        }
        .overview-value {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #0066cc;
        }
        .overview-label {
            font-size: 14px;
            color: #666;
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
            font-size: 11px;
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
            margin-top: 40px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>System Report</h1>
        <div class="subtitle">Generated on {{ $date }}</div>
    </div>
    
    <h2>System Overview</h2>
    <div class="overview-container">
        <div class="overview-box">
            <div class="overview-value">{{ $userCount }}</div>
            <div class="overview-label">Registered Users</div>
        </div>
        <div class="overview-box">
            <div class="overview-value">{{ $teamCount }}</div>
            <div class="overview-label">Teams</div>
        </div>
        <div class="overview-box">
            <div class="overview-value">{{ $goalCount }}</div>
            <div class="overview-label">Total Goals</div>
        </div>
    </div>
    
    <h2>Recent Activity</h2>
    
    <h3>Recent Users (Last 10)</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registered</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if(count($recentUsers) > 0)
                @foreach($recentUsers as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ date('M d, Y H:i', strtotime($user->created_at)) }}</td>
                        <td>{{ $user->email_verified_at ? 'Verified' : 'Unverified' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center;">No recent users</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <h3>Recent Teams (Last 10)</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Team Name</th>
                <th>Owner</th>
                <th>Members</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @if(count($recentTeams) > 0)
                @foreach($recentTeams as $index => $team)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $team->name }}</td>
                        <td>{{ $team->owner }}</td>
                        <td>{{ $team->users->count() }}</td>
                        <td>{{ date('M d, Y H:i', strtotime($team->created_at)) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center;">No recent teams</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <div class="page-break"></div>
    
    <h3>Recent Goals (Last 10)</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Type</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @if(count($recentGoals) > 0)
                @foreach($recentGoals as $index => $goal)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $goal->title }}</td>
                        <td>{{ $goal->team_id ? 'Team' : 'Personal' }}</td>
                        <td>{{ ucfirst($goal->priority) }}</td>
                        <td>{{ $goal->done ? 'Completed' : 'Pending' }}</td>
                        <td>{{ date('M d, Y H:i', strtotime($goal->created_at)) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="text-align: center;">No recent goals</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <h2>System Health Summary</h2>
    <p>This report provides an overview of the system's current state and recent activity. It includes counts of all users, teams, and goals in the system, as well as details about the most recent registrations and content creations.</p>
    
    <div class="footer">
        <p>This document was automatically generated from the Goal Management System.</p>
        <p>CONFIDENTIAL - For administrative use only</p>
        <p>Report Date: {{ $date }}</p>
    </div>
</body>
</html> 