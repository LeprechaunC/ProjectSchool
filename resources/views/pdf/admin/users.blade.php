<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Users Report</title>
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
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
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
        .admin {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>All Users Report</h1>
        <div class="subtitle">Generated on {{ $date }}</div>
    </div>
    
    <div class="info">
        <p><strong>Total Users:</strong> {{ $totalUsers }}</p>
    </div>
    
    @if(count($users) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 15%;">Name</th>
                    <th style="width: 20%;">Email</th>
                    <th style="width: 10%;">Role</th>
                    <th style="width: 15%;">Teams</th>
                    <th style="width: 15%;">Registered</th>
                    <th style="width: 10%;">Last Login</th>
                    <th style="width: 10%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="{{ $user->is_admin ? 'admin' : '' }}">{{ $user->is_admin ? 'Admin' : 'User' }}</td>
                        <td>{{ $user->teams->count() }} ({{ $user->teams->pluck('name')->join(', ') }})</td>
                        <td>{{ date('M d, Y H:i', strtotime($user->created_at)) }}</td>
                        <td>{{ $user->last_login_at ? date('M d, Y H:i', strtotime($user->last_login_at)) : 'Never' }}</td>
                        <td>{{ $user->email_verified_at ? 'Verified' : 'Unverified' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center;">No users found.</p>
    @endif
    
    <div class="footer">
        <p>This document was automatically generated from the Goal Management System.</p>
        <p>CONFIDENTIAL - For administrative use only</p>
    </div>
</body>
</html> 