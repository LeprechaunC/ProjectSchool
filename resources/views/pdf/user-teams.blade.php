<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Manas Komandas</title>
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
        .user-info {
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
            border-radius: 5px;
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
            font-size: 18px;
            font-weight: bold;
            color: #0066cc;
        }
        .member-list {
            margin-top: 5px;
            border-left: 3px solid #ddd;
            padding-left: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Manu Komandu Pārskats</h1>
        <div class="subtitle">Izveidots {{ date('F d, Y H:i:s') }}</div>
    </div>
    
    <div class="user-info">
        <p><strong>Vārds:</strong> {{ $user->name }}</p>
        <p><strong>E-pasts:</strong> {{ $user->email }}</p>
        <p><strong>Kopējais Komandu Skaits:</strong> {{ count($teams) }}</p>
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
                    <div class="member-list">
                        @foreach($team->users as $member)
                            <div>{{ $member->name }} {{ $member->email == $user->email ? '(Jūs)' : '' }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p style="text-align: center;">Jūs neesat nevienas komandas dalībnieks.</p>
    @endif
    
    <div class="footer">
        <p>Šis dokuments tika automātiski ģenerēts no jūsu Mērķu Vadības Sistēmas.</p>
    </div>
</body>
</html> 