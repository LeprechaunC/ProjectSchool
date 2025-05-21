<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mērķu Statistika</title>
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
            margin-top: 20px;
            margin-bottom: 10px;
            color: #444;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .stat-box {
            width: 45%;
            margin-right: 5%;
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            text-align: center;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #0066cc;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
        }
        .progress-container {
            background-color: #eee;
            height: 20px;
            border-radius: 10px;
            margin: 10px 0;
            overflow: hidden;
        }
        .progress-bar {
            background-color: #4caf50;
            height: 100%;
            border-radius: 10px;
            text-align: center;
            color: white;
            font-size: 10px;
            line-height: 20px;
        }
        .chart-container {
            width: 100%;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Mērķu Statistika</h1>
        <div class="subtitle">Izveidots {{ $date }}</div>
    </div>
    
    <h2>Pārskats</h2>
    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-value">{{ $totalGoals }}</div>
            <div class="stat-label">Kopējais Mērķu Skaits</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $completedGoals }}</div>
            <div class="stat-label">Pabeigtie Mērķi</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $pendingGoals }}</div>
            <div class="stat-label">Gaidošie Mērķi</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $teamGoals }}</div>
            <div class="stat-label">Komandas Mērķi</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $personalGoals }}</div>
            <div class="stat-label">Personīgie Mērķi</div>
        </div>
    </div>
    
    <h2>Mērķu Statuss</h2>
    <div style="margin-bottom: 20px;">
        <p><strong>Pabeigšanas Līmenis:</strong> {{ $totalGoals > 0 ? round(($completedGoals / $totalGoals) * 100, 1) : 0 }}%</p>
        <div class="progress-container">
            <div class="progress-bar" style="width: {{ $totalGoals > 0 ? round(($completedGoals / $totalGoals) * 100, 1) : 0 }}%">
                {{ $totalGoals > 0 ? round(($completedGoals / $totalGoals) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>
    
    <h2>Mērķi pēc Prioritātes</h2>
    <table>
        <thead>
            <tr>
                <th>Prioritāte</th>
                <th>Skaits</th>
                <th>Procenti</th>
                <th>Sadalījums</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Augsta</td>
                <td>{{ $highPriorityGoals }}</td>
                <td>{{ $totalGoals > 0 ? round(($highPriorityGoals / $totalGoals) * 100, 1) : 0 }}%</td>
                <td>
                    <div class="progress-container" style="margin: 0;">
                        <div class="progress-bar" style="width: {{ $totalGoals > 0 ? round(($highPriorityGoals / $totalGoals) * 100, 1) : 0 }}%; background-color: #dc3545;">
                            {{ $totalGoals > 0 ? round(($highPriorityGoals / $totalGoals) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Vidēja</td>
                <td>{{ $mediumPriorityGoals }}</td>
                <td>{{ $totalGoals > 0 ? round(($mediumPriorityGoals / $totalGoals) * 100, 1) : 0 }}%</td>
                <td>
                    <div class="progress-container" style="margin: 0;">
                        <div class="progress-bar" style="width: {{ $totalGoals > 0 ? round(($mediumPriorityGoals / $totalGoals) * 100, 1) : 0 }}%; background-color: #fd7e14;">
                            {{ $totalGoals > 0 ? round(($mediumPriorityGoals / $totalGoals) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Zema</td>
                <td>{{ $lowPriorityGoals }}</td>
                <td>{{ $totalGoals > 0 ? round(($lowPriorityGoals / $totalGoals) * 100, 1) : 0 }}%</td>
                <td>
                    <div class="progress-container" style="margin: 0;">
                        <div class="progress-bar" style="width: {{ $totalGoals > 0 ? round(($lowPriorityGoals / $totalGoals) * 100, 1) : 0 }}%; background-color: #28a745;">
                            {{ $totalGoals > 0 ? round(($lowPriorityGoals / $totalGoals) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    
    <h2>Komandas vs Personīgie Mērķi</h2>
    <div style="margin-bottom: 20px;">
        <table>
            <thead>
                <tr>
                    <th>Tips</th>
                    <th>Skaits</th>
                    <th>Procenti</th>
                    <th>Sadalījums</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Komandas Mērķi</td>
                    <td>{{ $teamGoals }}</td>
                    <td>{{ $totalGoals > 0 ? round(($teamGoals / $totalGoals) * 100, 1) : 0 }}%</td>
                    <td>
                        <div class="progress-container" style="margin: 0;">
                            <div class="progress-bar" style="width: {{ $totalGoals > 0 ? round(($teamGoals / $totalGoals) * 100, 1) : 0 }}%; background-color: #0066cc;">
                                {{ $totalGoals > 0 ? round(($teamGoals / $totalGoals) * 100, 1) : 0 }}%
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Personīgie Mērķi</td>
                    <td>{{ $personalGoals }}</td>
                    <td>{{ $totalGoals > 0 ? round(($personalGoals / $totalGoals) * 100, 1) : 0 }}%</td>
                    <td>
                        <div class="progress-container" style="margin: 0;">
                            <div class="progress-bar" style="width: {{ $totalGoals > 0 ? round(($personalGoals / $totalGoals) * 100, 1) : 0 }}%; background-color: #6f42c1;">
                                {{ $totalGoals > 0 ? round(($personalGoals / $totalGoals) * 100, 1) : 0 }}%
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="footer">
        <p>Šis dokuments tika automātiski ģenerēts no Mērķu Vadības Sistēmas.</p>
        <p>KONFIDENCIĀLS - Tikai administratīvai lietošanai</p>
    </div>
</body>
</html> 