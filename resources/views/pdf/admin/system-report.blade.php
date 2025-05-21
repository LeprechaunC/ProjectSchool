<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sistēmas Pārskats</title>
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
        <h1>Sistēmas Pārskats</h1>
        <div class="subtitle">Izveidots {{ $date }}</div>
    </div>
    
    <h2>Sistēmas Pārskats</h2>
    <div class="overview-container">
        <div class="overview-box">
            <div class="overview-value">{{ $userCount }}</div>
            <div class="overview-label">Reģistrētie Lietotāji</div>
        </div>
        <div class="overview-box">
            <div class="overview-value">{{ $teamCount }}</div>
            <div class="overview-label">Komandas</div>
        </div>
        <div class="overview-box">
            <div class="overview-value">{{ $goalCount }}</div>
            <div class="overview-label">Kopējais Mērķu Skaits</div>
        </div>
    </div>
    
    <h2>Nesenie Notikumi</h2>
    
    <h3>Nesenie Lietotāji (Pēdējie 10)</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Vārds</th>
                <th>E-pasts</th>
                <th>Reģistrēts</th>
                <th>Statuss</th>
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
                        <td>{{ $user->email_verified_at ? 'Verificēts' : 'Neverificēts' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center;">Nav neseno lietotāju</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <h3>Nesenas Komandas (Pēdējās 10)</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Komandas Nosaukums</th>
                <th>Vadītājs</th>
                <th>Dalībnieki</th>
                <th>Izveidots</th>
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
                    <td colspan="5" style="text-align: center;">Nav nesenu komandu</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <div class="page-break"></div>
    
    <h3>Nesenie Mērķi (Pēdējie 10)</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nosaukums</th>
                <th>Tips</th>
                <th>Prioritāte</th>
                <th>Statuss</th>
                <th>Izveidots</th>
            </tr>
        </thead>
        <tbody>
            @if(count($recentGoals) > 0)
                @foreach($recentGoals as $index => $goal)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $goal->title }}</td>
                        <td>{{ $goal->team_id ? 'Komandas' : 'Personīgais' }}</td>
                        <td>{{ ucfirst($goal->priority) }}</td>
                        <td>{{ $goal->done ? 'Pabeigts' : 'Gaida' }}</td>
                        <td>{{ date('M d, Y H:i', strtotime($goal->created_at)) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="text-align: center;">Nav nesenu mērķu</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <h2>Sistēmas Veselības Kopsavilkums</h2>
    <p>Šis pārskats sniedz pārskatu par sistēmas pašreizējo stāvokli un neseno aktivitāti. Tajā iekļauts visu lietotāju, komandu un mērķu skaits sistēmā, kā arī informācija par jaunākajām reģistrācijām un satura izveidēm.</p>
    
    <div class="footer">
        <p>Šis dokuments tika automātiski ģenerēts no Mērķu Vadības Sistēmas.</p>
        <p>KONFIDENCIĀLS - Tikai administratīvai lietošanai</p>
        <p>Pārskata Datums: {{ $date }}</p>
    </div>
</body>
</html> 