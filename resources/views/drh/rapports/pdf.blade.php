<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport - {{ $rapport->titre }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 15px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f8f9fa; }
        .stat-card { border: 1px solid #ddd; padding: 10px; margin: 5px; }
        .text-center { text-align: center; }
        .mb-3 { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $rapport->titre }}</h1>
        <p>Période du {{ $rapport->date_debut->format('d/m/Y') }} au {{ $rapport->date_fin->format('d/m/Y') }}</p>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }} par {{ auth()->user()->name }}</p>
    </div>

    <div class="section">
        <h2>📈 Statistiques Générales</h2>
        <div style="display: flex; justify-content: space-around; margin-bottom: 20px;">
            <div class="stat-card">
                <h3>{{ $rapport->donnees['statistiques']['total_demandes'] }}</h3>
                <p>Total Demandes</p>
            </div>
            <div class="stat-card">
                <h3>{{ $rapport->donnees['statistiques']['approuvees'] }}</h3>
                <p>Approuvées</p>
            </div>
            <div class="stat-card">
                <h3>{{ $rapport->donnees['statistiques']['rejetees'] }}</h3>
                <p>Rejetées</p>
            </div>
            <div class="stat-card">
                <h3>{{ $rapport->donnees['statistiques']['en_attente'] }}</h3>
                <p>En Attente</p>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>📊 Par Type de Demande</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Total</th>
                    <th>Approuvées</th>
                    <th>Taux</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rapport->donnees['par_type'] as $type => $data)
                <tr>
                    <td>{{ $type }}</td>
                    <td>{{ $data['total'] }}</td>
                    <td>{{ $data['approuvees'] }}</td>
                    <td>{{ $data['taux_approbation'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>🏢 Par Département</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Département</th>
                    <th>Total Demandes</th>
                    <th>Approuvées</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rapport->donnees['par_departement'] as $dept => $data)
                <tr>
                    <td>{{ $dept }}</td>
                    <td>{{ $data['total'] }}</td>
                    <td>{{ $data['approuvees'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>📅 Évolution Mensuelle</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Mois</th>
                    <th>Nombre de Demandes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rapport->donnees['evolution'] as $mois => $count)
                <tr>
                    <td>{{ Carbon\Carbon::parse($mois)->format('F Y') }}</td>
                    <td>{{ $count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
    <p>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</p>
    <p>Système de Gestion des Congés - UTS</p>
</div>
</body>
<script type="text/php">
    if (isset($pdf)) {
        $text = "Page {PAGE_NUM} sur {PAGE_COUNT}";
        $size = 10;
        $font = $fontMetrics->getFont("DejaVu Sans");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 2;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
</script>
</html>