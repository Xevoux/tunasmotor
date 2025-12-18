<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SubscriberResource\Pages;
use App\Filament\Admin\Resources\SubscriberResource\RelationManagers;
use App\Models\Subscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Mail;

class SubscriberResource extends Resource
{
    protected static ?string $model = Subscriber::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Subscribers';

    protected static ?string $modelLabel = 'Subscriber';

    protected static ?string $pluralModelLabel = 'Subscribers';

    protected static ?string $navigationGroup = 'Konten & Pengaturan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->placeholder('Nama subscriber (opsional)'),
                Forms\Components\DateTimePicker::make('subscribed_at')
                    ->required()
                    ->default(now()),
                Forms\Components\DateTimePicker::make('unsubscribed_at')
                    ->disabled(),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->default(true)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (!$state) {
                            $set('unsubscribed_at', now());
                        } else {
                            $set('unsubscribed_at', null);
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->placeholder('Tidak ada nama'),
                Tables\Columns\TextColumn::make('subscribed_at')
                    ->label('Subscribed')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unsubscribed_at')
                    ->label('Unsubscribed')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Masih aktif'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'] === 'active',
                                fn (Builder $query): Builder => $query->where('is_active', true)->whereNull('unsubscribed_at'),
                            )
                            ->when(
                                $data['value'] === 'inactive',
                                fn (Builder $query): Builder => $query->where(function ($q) {
                                    $q->where('is_active', false)->orWhereNotNull('unsubscribed_at');
                                }),
                            );
                    }),
                Tables\Filters\Filter::make('subscribed_today')
                    ->query(fn (Builder $query): Builder => $query->whereDate('subscribed_at', today())),
                Tables\Filters\Filter::make('unsubscribed_recently')
                    ->query(fn (Builder $query): Builder => $query->where('unsubscribed_at', '>=', now()->subDays(7))),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('send_newsletter')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('newsletter_id')
                            ->label('Pilih Newsletter')
                            ->options(\App\Models\Newsletter::published()->pluck('title', 'id'))
                            ->required()
                            ->searchable(),
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Newsletter')
                    ->modalDescription('Kirim newsletter yang sudah dibuat ke subscriber ini')
                    ->modalSubmitActionLabel('Kirim Newsletter')
                    ->action(function (Subscriber $record, array $data) {
                        $newsletter = \App\Models\Newsletter::find($data['newsletter_id']);

                        if (!$newsletter) {
                            Notification::make()
                                ->title('Error')
                                ->body('Newsletter tidak ditemukan')
                                ->danger()
                                ->send();
                            return;
                        }

                        try {
                            Mail::to($record->email)
                                ->send(new \App\Mail\NewsletterMail($newsletter, $record->email));

                            Notification::make()
                                ->title('Newsletter dikirim')
                                ->body("Newsletter '{$newsletter->title}' berhasil dikirim ke {$record->email}")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error')
                                ->body('Gagal mengirim newsletter: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (Subscriber $record): bool => $record->isSubscribed() && \App\Models\Newsletter::published()->count() > 0),
                Action::make('toggle_subscription')
                    ->icon('heroicon-o-eye-slash')
                    ->color('warning')
                    ->label('Unsubscribe')
                    ->requiresConfirmation()
                    ->modalHeading('Unsubscribe Subscriber')
                    ->modalDescription('Apakah Anda yakin ingin unsubscribe subscriber ini?')
                    ->modalSubmitActionLabel('Unsubscribe')
                    ->action(function (Subscriber $record) {
                        $record->unsubscribe();
                        Notification::make()
                            ->title('Subscriber di-unsubscribe')
                            ->body("{$record->email} telah di-unsubscribe")
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Subscriber $record): bool => $record->isSubscribed()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('send_bulk_newsletter')
                        ->label('Kirim Newsletter')
                        ->icon('heroicon-o-envelope')
                        ->color('success')
                        ->form([
                            Forms\Components\Select::make('newsletter_id')
                                ->label('Pilih Newsletter')
                                ->options(\App\Models\Newsletter::published()->pluck('title', 'id'))
                                ->required()
                                ->searchable(),
                        ])
                        ->requiresConfirmation()
                        ->modalHeading('Kirim Newsletter Massal')
                        ->modalDescription('Kirim newsletter ke semua subscriber yang dipilih')
                        ->modalSubmitActionLabel('Kirim ke Semua')
                        ->action(function (array $records, array $data) {
                            $newsletter = \App\Models\Newsletter::find($data['newsletter_id']);
                            $count = count($records);

                            if (!$newsletter) {
                                Notification::make()
                                    ->title('Error')
                                    ->body('Newsletter tidak ditemukan')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $sentCount = 0;
                            foreach ($records as $subscriber) {
                                if ($subscriber->isSubscribed()) {
                                    try {
                                        Mail::to($subscriber->email)
                                            ->send(new \App\Mail\NewsletterMail($newsletter, $subscriber->email));
                                        $sentCount++;
                                    } catch (\Exception $e) {
                                        // Log error but continue with other emails
                                        \Illuminate\Support\Facades\Log::error('Failed to send newsletter to ' . $subscriber->email . ': ' . $e->getMessage());
                                    }
                                }
                            }

                            Notification::make()
                                ->title('Newsletter dikirim')
                                ->body("Newsletter '{$newsletter->title}' berhasil dikirim ke {$sentCount} dari {$count} subscriber")
                                ->success()
                                ->send();
                        })
                        ->visible(fn (): bool => \App\Models\Newsletter::published()->count() > 0),
                ]),
            ]);
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
            'index' => Pages\ListSubscribers::route('/'),
            'create' => Pages\CreateSubscriber::route('/create'),
            'edit' => Pages\EditSubscriber::route('/{record}/edit'),
        ];
    }
}
