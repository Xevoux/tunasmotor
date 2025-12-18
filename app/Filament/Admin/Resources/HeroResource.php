<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HeroResource\Pages;
use App\Filament\Admin\Resources\HeroResource\RelationManagers;
use App\Models\Hero;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class HeroResource extends Resource
{
    protected static ?string $model = Hero::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Hero Section';

    protected static ?string $modelLabel = 'Hero';

    protected static ?string $pluralModelLabel = 'Heroes';

    protected static ?string $navigationGroup = 'Konten & Pengaturan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Hero Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Hero Title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('LENGKAPI MOTOR ANDA. TEMUKAN SUKU CADANG TERBAIK.')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Hero Description')
                            ->required()
                            ->rows(3)
                            ->placeholder('Tingkatkan performa motor Anda dengan suku cadang berkualitas tinggi...')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first')
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Deal Badge & Countdown')
                    ->schema([
                        Forms\Components\TextInput::make('deal_label')
                            ->label('Deal Label')
                            ->placeholder('REFRESHIN WINTER DEALS')
                            ->maxLength(255)
                            ->helperText('Leave empty to hide the deal badge'),

                        Forms\Components\DateTimePicker::make('countdown_end_date')
                            ->label('Countdown End Date')
                            ->placeholder('Select end date for countdown')
                            ->helperText('Leave empty to disable countdown'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Button Settings')
                    ->schema([
                        Forms\Components\TextInput::make('button_text')
                            ->label('Button Text')
                            ->default('Shop Now')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('button_link')
                            ->label('Button Link')
                            ->url()
                            ->placeholder('https://example.com or route name')
                            ->helperText('Leave empty to use default products route'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Hero Image')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Hero Image')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->required()
                            ->directory('heroes')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Upload high-quality image (max 2MB). Recommended size: 1920x1080px'),

                        Forms\Components\TextInput::make('alt_text')
                            ->label('Alt Text')
                            ->maxLength(255)
                            ->placeholder('Describe the hero image for accessibility')
                            ->helperText('Important for SEO and accessibility'),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active hero sections will be displayed on the website'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->height(60)
                    ->width(100)
                    ->circular(false),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('deal_label')
                    ->label('Deal')
                    ->placeholder('No deal')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('countdown_end_date')
                    ->label('Countdown Ends')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('No countdown')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('is_active')
                    ->label('Status')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (array $records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => true]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function (array $records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => false]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeroes::route('/'),
            'create' => Pages\CreateHero::route('/create'),
            'edit' => Pages\EditHero::route('/{record}/edit'),
        ];
    }
}
