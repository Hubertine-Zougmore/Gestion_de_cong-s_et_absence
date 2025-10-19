<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Models\Rapport;
use App\Models\Demande;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Exports\RapportsExport; // ✅ Correct

class RapportController extends Controller
{
    /**
     * Liste des rapports
     */
    public function index()
    {
        $rapports = Rapport::where('user_id', auth()->id())
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        return view('drh.rapports.index', compact('rapports'));
    }

    /**
     * Formulaire de création
     */
    /**
 * Afficher le formulaire de création d'un rapport
 */
public function create()
{
    // Vérifier que seul le DRH peut accéder
    if (auth()->user()->role !== 'drh') {
        abort(403, 'Accès réservé au DRH');
    }

    // Périodes prédéfinies
    $periodesPredefinies = [
        'mois_cours' => [
            'debut' => now()->startOfMonth(),
            'fin' => now()->endOfMonth(),
            'label' => 'Mois en cours (' . now()->format('F Y') . ')'
        ],
        'mois_precedent' => [
            'debut' => now()->subMonth()->startOfMonth(),
            'fin' => now()->subMonth()->endOfMonth(),
            'label' => 'Mois précédent (' . now()->subMonth()->format('F Y') . ')'
        ],
        'trimestre_cours' => [
            'debut' => now()->startOfQuarter(),
            'fin' => now()->endOfQuarter(),
            'label' => 'Trimestre en cours (Q' . ceil(now()->month / 3) . ' ' . now()->year . ')'
        ],
        'trimestre_precedent' => [
            'debut' => now()->subQuarter()->startOfQuarter(),
            'fin' => now()->subQuarter()->endOfQuarter(),
            'label' => 'Trimestre précédent (Q' . ceil(now()->subQuarter()->month / 3) . ' ' . now()->subQuarter()->year . ')'
        ],
        'annee_cours' => [
            'debut' => now()->startOfYear(),
            'fin' => now()->endOfYear(),
            'label' => 'Année en cours (' . now()->year . ')'
        ],
        'annee_precedente' => [
            'debut' => now()->subYear()->startOfYear(),
            'fin' => now()->subYear()->endOfYear(),
            'label' => 'Année précédente (' . now()->subYear()->year . ')'
        ]
    ];

    // Types de rapports disponibles
    $typesRapport = [
        'mensuel' => 'Rapport Mensuel',
        'trimestriel' => 'Rapport Trimestriel', 
        'annuel' => 'Rapport Annuel',
        'personnalise' => 'Période Personnalisée'
    ];

    return view('drh.rapports.create', compact('periodesPredefinies', 'typesRapport'));
}

    /**
     * Générer et sauvegarder un rapport
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:mensuel,trimestriel,annuel,personnalise',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'description' => 'nullable|string'
        ]);

        // Générer les données du rapport
        $donnees = $this->genererDonneesRapport(
            $validated['date_debut'],
            $validated['date_fin']
        );

        // Créer le rapport
        $rapport = Rapport::create([
            'titre' => $validated['titre'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin'],
            'donnees' => $donnees,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('rapports.show', $rapport)
                       ->with('success', 'Rapport généré avec succès');
    }

    /**
     * Afficher un rapport
     */
    public function show(Rapport $rapport)
    {
        if ($rapport->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('drh.rapports.show', compact('rapport'));
    }

    /**
     * Générer le PDF
     */
   public function pdf(Rapport $rapport)
{
    if ($rapport->user_id !== auth()->id()) {
        abort(403, 'Accès non autorisé');
    }

    $pdf = PDF::loadView('drh.rapports.pdf', [
        'rapport' => $rapport,
        'page' => 1, // Variables pour la pagination
        'pages' => 1
    ]);

    return $pdf->download('rapport-' . $rapport->id . '.pdf');
}

// ...

public function excel(Rapport $rapport)
{
    if ($rapport->user_id !== auth()->id()) {
        abort(403, 'Accès non autorisé');
    }

    $fileName = 'rapport-' . $rapport->id . '-' . now()->format('Y-m-d') . '.xlsx';

    return Excel::download(new RapportsExport($rapport), $fileName);
}

    /**
     * Générer les données statistiques du rapport
     */
    private function genererDonneesRapport($dateDebut, $dateFin)
    {
        // Récupérer les demandes pour la période
        $demandes = Demande::with('user')
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->get();

        // Statistiques générales
        $stats = [
            'total_demandes' => $demandes->count(),
            'en_attente' => $demandes->where('statut', 'en_attente')->count(),
            'approuvees' => $demandes->where('statut', 'approuve')->count(),
            'rejetees' => $demandes->where('statut', 'rejete')->count(),
        ];

        // Par type de demande
        $parType = $demandes->groupBy('type')->map(function($group, $type) {
            return [
                'total' => $group->count(),
                'approuvees' => $group->where('statut', 'approuve')->count(),
                'taux_approbation' => $group->count() > 0 ? 
                    round(($group->where('statut', 'approuve')->count() / $group->count()) * 100, 2) : 0
            ];
        });

        // Par département
        $parDepartement = $demandes->groupBy('user.departement')->map(function($group, $dept) {
            return [
                'total' => $group->count(),
                'approuvees' => $group->where('statut', 'approuve')->count(),
            ];
        });

        // Évolution mensuelle
        $evolution = [];
        $current = Carbon::parse($dateDebut);
        $end = Carbon::parse($dateFin);

        while ($current <= $end) {
            $month = $current->format('Y-m');
            $evolution[$month] = Demande::whereYear('created_at', $current->year)
                                      ->whereMonth('created_at', $current->month)
                                      ->count();
            $current->addMonth();
        }

        return [
            'periode' => [
                'debut' => $dateDebut,
                'fin' => $dateFin
            ],
            'statistiques' => $stats,
            'par_type' => $parType,
            'par_departement' => $parDepartement,
            'evolution' => $evolution,
            'demandes_sample' => $demandes->take(10)->values() // 10 dernières demandes
        ];
    }

    /**
     * Supprimer un rapport
     */
    public function destroy(Rapport $rapport)
    {
        if ($rapport->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }

        $rapport->delete();

        return redirect()->route('rapports.index')
                       ->with('success', 'Rapport supprimé avec succès');
    }
}