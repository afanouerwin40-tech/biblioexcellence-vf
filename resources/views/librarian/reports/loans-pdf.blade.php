<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rapport Emprunts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

        h1 {
            color: #2563eb;
            font-size: 16px;
        }

        p.sub {
            color: #666;
            font-size: 10px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #2563eb;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }

        td {
            padding: 5px 8px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 10px;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }

        .actif {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .retourne {
            background: #d1fae5;
            color: #065f46;
        }

        .en_retard {
            background: #fee2e2;
            color: #991b1b;
        }

        .footer {
            margin-top: 15px;
            font-size: 9px;
            color: #999;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>BiblioExcellence — Rapport des Emprunts</h1>
    <p class="sub">Généré le {{ now()->format('d/m/Y à H:i') }} — Total : {{ $loans->count() }} emprunt(s)</p>
    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Utilisateur</th>
                <th>Livre</th>
                <th>Date emprunt</th>
                <th>Retour prévu</th>
                <th>Retour effectif</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
            <tr>
                <td>{{ $loan->id }}</td>
                <td>{{ $loan->user->name ?? '—' }}<br><span style="color:#999">{{ $loan->user->identifier ?? '' }}</span></td>
                <td>{{ $loan->bookCopy->book->titre ?? '—' }}</td>
                <td>{{ $loan->date_emprunt->format('d/m/Y') }}</td>
                <td>{{ $loan->date_retour_prevue->format('d/m/Y') }}</td>
                <td>{{ $loan->date_retour_effective?->format('d/m/Y') ?? '—' }}</td>
                <td><span class="badge {{ $loan->statut }}">{{ ucfirst(str_replace('_', ' ', $loan->statut)) }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">BiblioExcellence — Bibliothèque Universitaire</div>
</body>

</html>