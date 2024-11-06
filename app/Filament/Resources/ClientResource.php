<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Client;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ClientResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Filament\Resources\ClientResource\RelationManagers\OperationRelationManager;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Planteur';
    protected static ?string $activeNavigationIcon = 'heroicon-s-cursor-arrow-rays';


    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make([
                        TextInput::make('nom_client')
                        ->required()
                        ->label('Nom Complet'),

                        TextInput::make('numero_client')
                            ->required()
                            ->label('Code Client')
                            ->default(mt_rand(1000, 9999))
                            //->default(strtoupper(Str::random(5))) // Générer le code par défaut
                            ->extraAttributes(['style' => 'font-size: 72px; font-weight: bold;']),

                        TextInput::make('adresse_client')
                            ->label('Adresse'),
                    ]),
                    Section::make([
                        TextInput::make('telephone_client')
                        ->tel()
                        ->label('Téléphone'),

                    TextInput::make('email_client')
                        ->email()
                        ->label('Email'),
                    TextInput::make('note_client')

                        ->label('description'),


                    ])
                ])->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom_client')
                ->label('Nom Complet')
                ->searchable()
                ->description(fn (Client $record): string => $record->note_client)

                ->sortable(),

                Tables\Columns\TextColumn::make('numero_client')
                ->label('Code Client')
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('adresse_client')
                ->label('Adresse')
                ->searchable(),

                Tables\Columns\TextColumn::make('telephone_client')
                ->label('Téléphone')
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('email_client')
                ->label('Email')
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('note_client')
                ->label('Description')
                ->limit(50)
                ->wrap(),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OperationRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}