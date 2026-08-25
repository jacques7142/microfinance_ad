<?php

namespace App\Support;

use App\Models\Agence;
use Illuminate\Support\Collection;

class TogoRegions
{
    /**
     * Les 5 régions administratives du Togo (du nord au sud).
     *
     * @return array<string, array{label: string, short: string, villes: array<int, string>}>
     */
    public static function all(): array
    {
        return [
            'savanes' => [
                'label' => 'Savanes',
                'short' => 'Savanes',
                'villes' => ['Dapaong', 'Mango', 'Tône', 'Oti'],
            ],
            'kara' => [
                'label' => 'Kara',
                'short' => 'Kara',
                'villes' => ['Kara', 'Bassar', 'Niamtougou', 'Bafilo', 'Kandé'],
            ],
            'centrale' => [
                'label' => 'Centrale',
                'short' => 'Centrale',
                'villes' => ['Sokodé', 'Tchamba', 'Sotouboua', 'Blitta'],
            ],
            'plateaux' => [
                'label' => 'Plateaux',
                'short' => 'Plateaux',
                'villes' => ['Atakpamé', 'Kpalimé', 'Badou', 'Notsé', 'Amlamé'],
            ],
            'maritime' => [
                'label' => 'Maritime',
                'short' => 'Maritime',
                'villes' => ['Lomé', 'Aneho', 'Tabligbo', 'Vogan', 'Tsévié', 'Agoè', 'Golfe', 'Zio', 'Vo'],
            ],
        ];
    }

    /**
     * Code région à partir d'une ville (insensible à la casse et aux accents).
     */
    public static function codeFromVille(string $ville): ?string
    {
        $normalize = static function (string $value): string {
            $value = mb_strtolower($value);
            $value = str_replace(
                ['é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'û', 'ù', 'ç', "'", "'", '’'],
                ['e', 'e', 'e', 'e', 'a', 'a', 'i', 'i', 'o', 'u', 'u', 'c', ' ', ' ', ' '],
                $value
            );

            return trim(preg_replace('/\s+/', ' ', $value));
        };

        $ville = $normalize($ville);

        foreach (self::all() as $code => $region) {
            foreach ($region['villes'] as $villeRegion) {
                if ($normalize($villeRegion) === $ville) {
                    return $code;
                }
            }
        }

        return null;
    }

    /**
     * Retourne les agences regroupées par région (uniquement celles rattachées à une région).
     *
     * @param  \Illuminate\Support\Collection<int, Agence>  $agences
     * @return array<string, \Illuminate\Support\Collection<int, Agence>>
     */
    public static function groupByRegion(Collection $agences): array
    {
        $grouped = [];

        foreach (self::all() as $code => $region) { 
            $grouped[$code] = collect();
        }

        foreach ($agences as $agence) {
            $code = self::codeFromVille($agence->ville ?? '');
            if ($code !== null) {
                $grouped[$code]->push($agence);
            }
        }

        return $grouped;
    }

    /**
     * Statistiques par région (nombre d'agences, nombre de sociétaires).
     *
     * @param  \Illuminate\Support\Collection<int, Agence>  $agences
     * @return array<string, array{label: string, count: int, societaires: int}>
     */
    public static function stats(Collection $agences): array
    {
        $grouped = self::groupByRegion($agences);
        $stats = [];

        foreach (self::all() as $code => $region) {
            $regionAgences = $grouped[$code];
            $stats[$code] = [
                'label' => $region['label'],
                'count' => $regionAgences->count(),
                'societaires' => $regionAgences->sum(fn (Agence $agence) => $agence->societaires_count ?? 0),
            ];
        }

        return $stats;
    }
}
