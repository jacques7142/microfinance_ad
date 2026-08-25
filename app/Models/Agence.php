<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agence extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'ville', 'adresse', 'date_ouverture', 'est_siege', 'latitude', 'longitude', 'secteur', 'telephone_agence', 'description', 'horaires_fonctionnement', 'actif'];

    protected $casts = [
        'date_ouverture' => 'date',
        'est_siege' => 'boolean',
        'actif' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'horaires_fonctionnement' => 'array',
    ];

    public function utilisateurs(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function gerant()
    {
        return $this->utilisateurs()->where('role', 'gerant')->first();
    }

    public function societaires(): HasMany
    {
        return $this->hasMany(Societaire::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function rapports(): HasMany
    {
        return $this->hasMany(Rapport::class);
    }

    /**
     * Régions administratives du Togo, dans l'ordre d'affichage.
     */
    public const REGIONS_TOGO = ['Maritime', 'Plateaux', 'Centrale', 'Kara', 'Savanes'];

    /**
     * Région du Togo à laquelle l'agence appartient, déduite de sa ville.
     */
    public function getRegionAttribute(): string
    {
        return static::regionPourVille($this->ville);
    }

    public static function regionPourVille(?string $ville): string
    {
        if (! $ville) {
            return 'Autre région';
        }

        $v = static::normaliserVille($ville);

        $regions = [
            'Maritime' => [
                'tsevie', 'tsévié', 'keve', 'kévé', 'tabligbo', 'vogan', 'aneho', 'aného',
                'agbodrafo', 'baguida', 'noepe', 'noépé', 'afagnan', 'assahoun', 'davie', 'davié',
                'lome', 'lomé', 'agoe', 'agoè', 'kodjoviakope', 'kodjoviakopé',
                'adidogome', 'adidogomé', 'attikoume', 'attikoumé', 'akodessewa', 'akodésséwa', 'be-kpota',
                'abattoir', 'adetikope', 'adétikopé', 'agbalepedo', 'agbalépédo', 'assivito',
                'be-anfame', 'bé-anfamé', 'djagble', 'djagblé', 'gbenyedji', 'gbényédji',
                'legbassito', 'légbassito', 'ramco', 'zone portuaire', 'portuaire',
            ],
            'Plateaux' => [
                'atakpame', 'atakpamé', 'kpalime', 'kpalimé', 'notse', 'notsé', 'badou',
                'kpekleme', 'kpéklémé', 'amou-oblo', 'amou-obló', 'anie', 'anié', 'asrama',
                'kloto', 'kegue', 'kégué', 'akebou', 'akébou',
            ],
            'Centrale' => [
                'sokode', 'sokodé', 'tchamba', 'sotouboua', 'blitta', 'tchaoudjo',
                'koussountou', 'kambole', 'kambolé', 'yaoukopome',
            ],
            'Kara' => [
                'kara', 'bassar', 'kande', 'kandé', 'niamtougou', 'bafilo', 'pagouda',
                'doufelgou', 'kozah', 'dankpen', 'keran', 'kéran', 'binah', 'assoli',
            ],
            'Savanes' => [
                'dapaong', 'cinkasse', 'cinkassé', 'oti', 'tone', 'tône', 'kpendjal',
                'mandouri', 'sansanne-mango', 'mango',
            ],
        ];

        foreach ($regions as $region => $motsCles) {
            foreach ($motsCles as $mot) {
                if (str_contains($v, $mot)) {
                    return $region;
                }
            }
        }

        return 'Autre région';
    }

    private static function normaliserVille(string $ville): string
    {
        $accent = ['é', 'è', 'ê', 'ë', 'à', 'â', 'ç', 'î', 'ï', 'ô', 'ù', 'û', 'ô'];
        $sansAccent = ['e', 'e', 'e', 'e', 'a', 'a', 'c', 'i', 'i', 'o', 'u', 'u', 'o'];

        return mb_strtolower(str_replace($accent, $sansAccent, trim($ville)), 'UTF-8');
    }
}
