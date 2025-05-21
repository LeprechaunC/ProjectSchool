<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Visu Lietotāju Pārskats</title>
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
        <h1>Visu Lietotāju Pārskats</h1>
        <div class="subtitle">Izveidots {{ $date }}</div>
    </div>
    
    <div class="info">
        <p><strong>Kopējais Lietotāju Skaits:</strong> {{ $totalUsers }}</p>
    </div>
    
    @if(count($users) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 15%;">Vārds</th>
                    <th style="width: 20%;">E-pasts</th>
                    <th style="width: 10%;">Loma</th>
                    <th style="width: 15%;">Komandas</th>
                    <th style="width: 15%;">Reģistrēts</th>
                    <th style="width: 10%;">Pēdējā Pieslēgšanās</th>
                    <th style="width: 10%;">Statuss</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="{{ $user->is_admin ? 'admin' : '' }}">{{ $user->is_admin ? 'Administrators' : 'Lietotājs' }}</td>
                        <td>{{ $user->teams->count() }} ({{ $user->teams->pluck('name')->join(', ') }})</td>
                        <td>{{ date('M d, Y H:i', strtotime($user->created_at)) }}</td>
                        <td>{{ $user->last_login_at ? date('M d, Y H:i', strtotime($user->last_login_at)) : 'Nekad' }}</td>
                        <td>{{ $user->email_verified_at ? 'Verificēts' : 'Neverificēts' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center;">Nav atrasts neviens lietotājs.</p>
    @endif
    
    <div class="footer">
        <p>Šis dokuments tika automātiski ģenerēts no Mērķu Vadības Sistēmas.</p>
        <p>KONFIDENCIĀLS - Tikai administratīvai lietošanai</p>
    </div>
</body>
</html> 