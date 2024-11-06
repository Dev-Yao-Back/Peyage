<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $activeNavigationIcon = 'heroicon-s-cursor-arrow-rays';
    


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('INFORMATION UTILISATEUR')
                        ->icon('heroicon-m-identification')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([
                            Fieldset::make('Détails Utilisateur')
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Nom')
                                        ->required()
                                        ->maxLength(255),

                                    TextInput::make('email')
                                        ->label('Email')
                                        ->required()
                                        ->email()
                                        ->maxLength(255),

                                    TextInput::make('password')
                                        ->label('Mot de passe')
                                        ->password()
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->columns(3),
                        ])
                        ->columnSpan('full'),

                    Wizard\Step::make('CONTACT')
                        ->icon('heroicon-m-phone')
                        ->completedIcon('heroicon-m-check')
                        ->schema([
                            Fieldset::make('Informations de Contact')
                                ->schema([
                                    TextInput::make('adresse')
                                        ->label('Adresse')
                                        ->nullable()
                                        ->maxLength(255),

                                    TextInput::make('telephone')
                                        ->label('Téléphone')
                                        ->nullable()
                                        ->tel()
                                        ->maxLength(15),
                                ])
                                ->columnSpan('full'),

                            Split::make([
                                Section::make([
                                    Select::make('roles')
                                        ->label('Rôles')
                                        ->relationship('roles', 'name')
                                        ->required(),
                                ]),
                            ]),
                        ]),
                ])->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('roles.name')->label('Roles')->sortable(),

                TextColumn::make('adresse')
                    ->label('Adresse')
                    ->sortable()
                    ->limit(50),

                TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                // Ajoutez vos filtres ici
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
            // Ajoutez vos relations ici
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
