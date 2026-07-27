<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Credit extends Model
{
    use HasFactory;

    // Types
    public const TYPE_ORDINAIRE = 'ordinaire';
    public const TYPE_PARTENARIAT = 'partenariat';
    public const TYPE_TONTINE = 'tontine';

    public const SOUS_TYPES_ORDINAIRE = [
        'investissement', 'exploitation', 'financement_marche', 'salaire',
        'consommation', 'agricole', 'energies_vertes', 'activites_culturelles',
    ];

    // Statuts (workflow)
    public const STATUT_RECUE = 'recue';
    public const STATUT_EN_INSTRUCTION = 'en_instruction';
    public const STATUT_TRANSMISE_GERANT = 'transmise_gerant';
    public const STATUT_VALIDEE = 'validee';
    public const STATUT_REJETEE = 'rejetee';
    public const STATUT_SOLDEE = 'soldee';

    protected $fillable = [
        'societaire_id', 'agent_credit_id', 'gerant_id', 'compte_tontine_id',
        'type', 'sous_type', 'partenaire', 'proportion_garantie',
        'montant', 'duree_mois', 'taux_interet', 'date_demande', 'statut',
        'avis_agent',
    ];

    protected function casts(): array
    {
        return [
            'date_demande' => 'date',
            'montant' => 'decimal:2',
            'taux_interet' => 'decimal:2',
            'proportion_garantie' => 'decimal:2',
        ];
    }

    // --- Relations ---
    public function societaire(): BelongsTo
    {
        return $this->belongsTo(Societaire::class);
    }

    public function agentCredit(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_credit_id');
    }

    public function gerant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gerant_id');
    }

    public function compteTontine(): BelongsTo
    {
        return $this->belongsTo(CompteTontine::class);
    }

    public function echeances(): HasMany
    {
        return $this->hasMany(Echeance::class);
    }

    public function frais(): HasMany
    {
        return $this->hasMany(FraisCredit::class, 'credit_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // --- Logique métier ---

    /** Somme des frais obligatoires (dossier, commission, assurance...) liés au crédit. */
    public function totalFrais(): float
    {
        return (float) $this->frais()->sum('montant');
    }

    /**
     * TAEG approximatif (méthode linéaire simplifiée à des fins pédagogiques) :
     * (intérêts totaux + frais) / montant / durée en années, exprimé en %.
     * Une vraie implémentation SFD utiliserait la méthode actuarielle (taux proportionnel
     * composé), plus complexe — à affiner si le mémoire l'exige.
     */
    public function taegApproximatif(): float
    {
        if ((float) $this->montant <= 0 || (int) $this->duree_mois <= 0) {
            return 0;
        }
        $dureeAnnees = $this->duree_mois / 12;
        $interetsTotal = (float) $this->montant * ((float) $this->taux_interet / 100) * $dureeAnnees;
        $coutTotal = $interetsTotal + $this->totalFrais();

        return round(($coutTotal / (float) $this->montant / $dureeAnnees) * 100, 2);
    }

    public function depasseLePlafondUsure(): bool
    {
        return $this->taegApproximatif() > (float) config('coopec.taux_usure_plafond');
    }

    public function libelleType(): string
    {
        return match ($this->type) {
            self::TYPE_ORDINAIRE => 'Crédit ordinaire — '.str_replace('_', ' ', (string) $this->sous_type),
            self::TYPE_PARTENARIAT => 'Crédit de partenariat'.($this->partenaire ? " ($this->partenaire)" : ''),
            self::TYPE_TONTINE => 'Crédit tontine adossé',
            default => $this->type,
        };
    }
}
