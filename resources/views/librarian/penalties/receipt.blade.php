<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de paiement — BiblioExcellence</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #2563eb; font-size: 20px; margin: 0 0 5px; }
        .header p { color: #666; margin: 0; font-size: 12px; }
        .badge { display: inline-block; background: #2563eb; color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; }
        .section { margin-bottom: 20px; }
        .section h2 { font-size: 13px; font-weight: bold; color: #2563eb; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px; margin-bottom: 10px; }
        .grid { display: flex; gap: 20px; }
        .col { flex: 1; }
        .field { margin-bottom: 8px; }
        .field label { font-size: 10px; color: #999; text-transform: uppercase; display: block; }
        .field span { font-weight: 500; }
        .total { background: #f0f9ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 15px; text-align: center; margin: 15px 0; }
        .total .amount { font-size: 24px; font-weight: bold; color: #2563eb; }
        .total .label { font-size: 11px; color: #666; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th { background: #f8fafc; text-align: left; padding: 8px; font-size: 10px; color: #666; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
        td { padding: 8px; border-bottom: 1px solid #f3f4f6; }
        .footer { text-align: center; margin-top: 30px; font-size: 11px; color: #999; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        .status-paid { background: #d1fae5; color: #065f46; padding: 3px 8px; border-radius: 10px; font-size: 11px; }
    </style>
</head>
<body>

    {{-- En-tête --}}
    <div class="header">
        <h1>BiblioExcellence</h1>
        <p>Bibliothèque Universitaire — Reçu de paiement de pénalité</p>
        <br>
        <span class="badge">Reçu #{{ str_pad($penalty->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>

    {{-- Informations utilisateur --}}
    <div class="section">
        <h2>Informations de l'utilisateur</h2>
        <div class="grid">
            <div class="col">
                <div class="field">
                    <label>Nom complet</label>
                    <span>{{ $penalty->user->name }}</span>
                </div>
                <div class="field">
                    <label>Identifiant</label>
                    <span>{{ $penalty->user->identifier }}</span>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <label>Email</label>
                    <span>{{ $penalty->user->email }}</span>
                </div>
                <div class="field">
                    <label>Rôle</label>
                    <span>{{ ucfirst($penalty->user->role_type) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Informations pénalité --}}
    <div class="section">
        <h2>Détails de la pénalité</h2>
        <div class="grid">
            <div class="col">
                <div class="field">
                    <label>Livre emprunté</label>
                    <span>{{ $penalty->loan->bookCopy->book->titre ?? '—' }}</span>
                </div>
                <div class="field">
                    <label>Jours de retard</label>
                    <span>{{ $penalty->jours_retard }} jour(s)</span>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <label>Montant total</label>
                    <span>{{ number_format($penalty->montant, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="field">
                    <label>Statut</label>
                    <span class="status-paid">
                        {{ $penalty->statut === 'payee' ? 'Payée' : 'Partiellement payée' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Total payé --}}
    <div class="total">
        <div class="label">Montant total encaissé</div>
        <div class="amount">{{ number_format($penalty->montant_paye, 0, ',', ' ') }} FCFA</div>
    </div>

    {{-- Historique des paiements --}}
    @if($penalty->payments->isNotEmpty())
        <div class="section">
            <h2>Historique des paiements</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Référence</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penalty->payments as $payment)
                        <tr>
                            <td>{{ $payment->paid_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($payment->montant_paye, 0, ',', ' ') }} FCFA</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment->methode)) }}</td>
                            <td>{{ $payment->reference ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>BiblioExcellence — Bibliothèque Universitaire</p>
    </div>

</body>
</html>