<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Visu Komandu Pārskats</title>
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
        .team-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            page-break-inside: avoid;
        }
        .team-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .team-members {
            margin-top: 10px;
        }
        .team-name {
            font-size: 16px;
            font-weight: bold;
            color: #0066cc;
        }
        .member-list {
            margin-top: 5px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Visu Komandu Pārskats</h1>
        <div class="subtitle">Izveidots {{ $date }}</div>
    </div>
    
    <div class="info">
        <p><strong>Kopējais Komandu Skaits:</strong> {{ $totalTeams }}</p>
    </div>
    
    @if(count($teams) > 0)
        @foreach($teams as $team)
            <div class="team-card">
                <div class="team-header">
                    <div class="team-name">{{ $team->name }}</div>
                    <div><strong>Vadītājs:</strong> {{ $team->owner }}</div>
                    <div><strong>Izveidots:</strong> {{ date('F d, Y', strtotime($team->created_at)) }}</div>
                    <div><strong>Ielūguma Kods:</strong> {{ $team->invite_code }}</div>
                </div>
                
                <div class="team-members">
                    <strong>Komandas Dalībnieki ({{ $team->users->count() }}):</strong>
                    <table class="member-list">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Vārds</th>
                                <th>E-pasts</th>
                                <th>Pievienojās</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($team->users as $index => $member)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->pivot->created_at ? date('M d, Y', strtotime($member->pivot->created_at)) : 'Nezināms' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @else
        <p style="text-align: center;">Nav atrasta neviena komanda.</p>
    @endif
    
    <div class="footer">
        <p>Šis dokuments tika automātiski ģenerēts no Mērķu Vadības Sistēmas.</p>
        <p>KONFIDENCIĀLS - Tikai administratīvai lietošanai</p>
    </div>
</body>
</html> 