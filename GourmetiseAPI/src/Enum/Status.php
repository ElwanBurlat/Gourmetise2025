<?php
namespace App\Enum;

enum Status: string
{
    case NOT_OPENED = 'not_opened';          // Avant ouverture
    case REGISTRATION_OPEN = 'registration'; // Période d'inscription
    case EVALUATION_OPEN = 'evaluation';     // Période d’évaluation
    case FINISHED = 'finished';              // Concours terminé


    public function label(): string
    {
        return match($this) {
            self::NOT_OPENED => 'Non ouvert',
            self::REGISTRATION_OPEN => 'Inscriptions en cours',
            self::EVALUATION_OPEN => 'Évaluation en cours',
            self::FINISHED => 'Terminé',
        };
    }
}
