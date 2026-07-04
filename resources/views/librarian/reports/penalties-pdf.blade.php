<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rapport Pénalités</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

        h1 {
            color: #dc2626;
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
            background: #dc2626;
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

        .total {
            background: #fef2f2;
            padding: 10px;
            border-radius: 6px;
            margin-top: 15px;
            text-align: right;
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
    <h1>BiblioExcellence — Rapport des Pénalités</h1>
    <p class="sub">Généré le {{ now()->format('d/m/Y à H:i') }} — Total : {{ $penalties->count() }} pénalité(s)</p>
    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Utilisateur</th>
                <th>Livre</th>
                <th>Jours retard</th>
                <th>Montant (FCFA)</th>
                <th>Payé (FCFA)</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penalties as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->user->name ?? '—' }}</td>
                <td>{{ $p->loan->bookCopy->book->titre ?? '—' }}</td>
                <td>{{ $p->jours_retard }}</td>
                <td>{{ number_format($p->montant, 0, ',', ' ') }}</td>
                <td>{{ number_format($p->montant_paye, 0, ',', ' ') }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $p->statut)) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="total">
        <strong>Total impayé : {{ number_format($penalties->where('statut','impayee')->sum('montant'), 0, ',', ' ') }} FCFA</strong>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Total encaissé : {{ number_format($penalties->where('statut','payee')->sum('montant'), 0, ',', ' ') }} FCFA</strong>
    </div>
    <div class="footer">BiblioExcellence — Bibliothèque Universitaire</div>
</body>

</html>