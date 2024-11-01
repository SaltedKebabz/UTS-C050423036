<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Models\Patient;
use Filament\Forms; // Correct import for Forms
use Filament\Tables; // Correct import for Tables
use Filament\Resources\Resource;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Pasien';
    protected static ?string $pluralModelLabel = 'Data Pasien';
    protected static ?string $modelLabel = 'Data Pasien';
    protected static ?string $navigationGroup = 'Kesehatan';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nama Pasien'),
                Forms\Components\DatePicker::make('dob')
                    ->required()
                    ->label('Tanggal Lahir'),
                Forms\Components\Select::make('gender')
                    ->required()
                    ->options([
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
                    ])
                    ->label('Jenis Kelamin'),
                Forms\Components\Textarea::make('disease_history')
                    ->label('Riwayat Penyakit'), // Changed label here
                Forms\Components\Textarea::make('symptoms')
                    ->label('Gejala yang Dirasakan'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Pasien'),
                Tables\Columns\TextColumn::make('dob')->label('Tanggal Lahir')->date(),
                Tables\Columns\TextColumn::make('gender')->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('risk_level')->label('Tingkat Risiko'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Buat Data Pasien'), // Changed label here
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }

    protected static function afterCreate(array $data): void
    {
        // Create the patient record
        $patient = Patient::create($data);
        
        // Calculate and set the risk level
        $patient->risk_level = $patient->calculateRisk();
        
        // Save quietly to avoid triggering events
        $patient->saveQuietly();
    }

    protected static function afterUpdate(array $data, Patient $record): void
    {
        // Update the existing patient record
        $record->update($data);
        
        // Calculate and set the new risk level
        $record->risk_level = $record->calculateRisk();
        
        // Save quietly to avoid triggering events
        $record->saveQuietly();
    }
}