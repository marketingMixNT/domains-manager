<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Host extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    public static function getForm(){
        return [
            TextInput::make('name')
            ->label('Nazwa klienta')
            ->required()
            ->minLength(3)
            ->maxLength(255),
        ];
    }
}
