<?php

use App\Models\CompteEpargne;
use App\Models\Societaire;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    // Conformément à la pratique COOPEC-AD : chaque sociétaire dispose d'un
    // compte épargne (DAV — livret) ouvert dès son adhésion.
    // Cette migration rattrape les sociétaires existants sans compte DAV.
    public function up(): void
    {
        $societaires = Societaire::all();

        foreach ($societaires as $societaire) {
            $existe = $societaire->comptesEpargne()
                ->where('type', CompteEpargne::TYPE_DAV)
                ->exists();

            if (!$existe) {
                CompteEpargne::create([
                    'societaire_id' => $societaire->id,
                    'type' => CompteEpargne::TYPE_DAV,
                    'solde' => 0,
                    'date_ouverture' => $societaire->date_adhesion ?? now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        $ids = CompteEpargne::where('type', CompteEpargne::TYPE_DAV)
            ->where('solde', 0)
            ->whereDoesntHave('transactions')
            ->pluck('id');

        CompteEpargne::whereIn('id', $ids)->delete();
    }
};
