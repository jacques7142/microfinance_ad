<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FraisCredit extends Model
{
    use HasFactory;

    protected $table = 'frais_credit';

    protected $fillable = ['credit_id', 'type_frais', 'libelle', 'montant', 'date_application'];

    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
            'date_application' => 'date',
        ];
    }

    public function credit(): BelongsTo
    {
        return $this->belongsTo(Credit::class);
    }
}
