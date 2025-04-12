<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeHorario extends Model
{
    // Define a tabela associada
    protected $table = 'grades_horario';

    protected $primaryKey = 'id_grade';

    // Define os campos que podem ser preenchidos em massa
    protected $fillable = [
        'id_estab',
        'dia_semana',
        'inicio_expediente',
        'termino_expediente'
    ];


    // Desativa os timestamps automáticos
    public $timestamps = false;
}
