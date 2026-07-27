<?php

// config/coopec.php
//
// Paramètres métier centralisés ici plutôt qu'en base de données : un plafond
// réglementaire (fixé par la BCEAO) peut changer sans qu'on ait à écrire une
// migration — on modifie simplement le .env et on redéploie.

return [

    // Taux d'usure plafond pour les SFD (TAEG, frais compris) — Avis BCEAO
    // n°007-12-2025, applicable au 1er juin 2026. À vérifier/mettre à jour
    // auprès des textes officiels en vigueur.
    'taux_usure_plafond' => (float) env('TAUX_USURE_PLAFOND', 24.0),

    // Seuil (en FCFA) au-delà duquel une opération déclenche une alerte de
    // vigilance LBC/FT — seuil interne illustratif, à calibrer selon la
    // politique de conformité réelle de la COOPEC-AD.
    'seuil_vigilance_lbc' => (float) env('SEUIL_VIGILANCE_LBC', 2000000),

    // Proportion par défaut du solde tontine accumulé utilisable comme
    // garantie pour un crédit tontine adossé.
    'proportion_garantie_tontine' => (float) env('PROPORTION_GARANTIE_TONTINE', 0.70),

];
