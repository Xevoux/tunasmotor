<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NewsletterResource\Pages;
use App\Filament\Admin\Resources\NewsletterResource\RelationManagers;
use App\Models\Newsletter;
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

class NewsletterResource extends Resource
{
    protected static ?string $model = Newsletter::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Newsletters';

    protected static ?string $modelLabel = 'Newsletter';

    protected static ?string $pluralModelLabel = 'Newsletters';

    protected static ?string $navigationGroup = 'Konten & Pengaturan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Newsletter Content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan judul newsletter'),
                        Forms\Components\Textarea::make('excerpt')
                            ->maxLength(500)
                            ->placeholder('Ringkasan singkat newsletter (opsional)')
                            ->rows(3),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->placeholder('Tulis konten newsletter di sini...')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                                'blockquote',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Status & Publishing')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->required(),
                        Forms\Components\Placeholder::make('sent_info')
                            ->label('')
                            ->content(function ($record) {
                                if (!$record || $record->status !== 'sent') {
                                    return 'Newsletter belum dikirim';
                                }
                                return "Dikirim pada: {$record->sent_at->format('d F Y H:i')} | Penerima: {$record->recipient_count}";
                            })
                            ->visible(fn ($record) => $record && $record->status === 'sent'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'published',
                        'primary' => 'sent',
                    ])
                    ->icons([
                        'heroicon-o-pencil' => 'draft',
                        'heroicon-o-eye' => 'published',
                        'heroicon-o-paper-airplane' => 'sent',
                    ]),
                Tables\Columns\TextColumn::make('excerpt')
                    ->limit(100)
                    ->placeholder('Tidak ada ringkasan')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sent_at')
                    ->label('Sent At')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Belum dikirim'),
                Tables\Columns\TextColumn::make('recipient_count')
                    ->label('Recipients')
                    ->numeric()
                    ->sortable()
                    ->placeholder('0'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'sent' => 'Sent',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn (Newsletter $record): bool => $record->status !== 'sent'),
                Action::make('send_newsletter')
                    ->label('Send Newsletter')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Newsletter')
                    ->modalDescription('Apakah Anda yakin ingin mengirim newsletter ini ke semua subscriber aktif?')
                    ->modalSubmitActionLabel('Kirim Sekarang')
                    ->action(function (Newsletter $record) {
                        $activeSubscribers = Subscriber::active()->get();

                        if ($activeSubscribers->isEmpty()) {
                            Notification::make()
                                ->title('Tidak ada subscriber aktif')
                                ->body('Tidak ada subscriber aktif untuk dikirimi newsletter')
                                ->warning()
                                ->send();
                            return;
                        }

                        $sentCount = 0;
                        $errors = [];

                        foreach ($activeSubscribers as $subscriber) {
                            try {
                                Mail::to($subscriber->email)
                                    ->send(new \App\Mail\NewsletterMail($record, $subscriber->email));
                                $sentCount++;
                            } catch (\Exception $e) {
                                $errors[] = $subscriber->email;
                                \Illuminate\Support\Facades\Log::error('Failed to send newsletter to ' . $subscriber->email . ': ' . $e->getMessage());
                            }
                        }

                        // Mark newsletter as sent
                        $record->markAsSent($sentCount, $activeSubscribers->pluck('email')->toArray());

                        $message = "Newsletter '{$record->title}' berhasil dikirim ke {$sentCount} subscriber";
                        if (!empty($errors)) {
                            $message .= ". Gagal kirim ke " . count($errors) . " email.";
                        }

                        Notification::make()
                            ->title('Newsletter dikirim!')
                            ->body($message)
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Newsletter $record): bool => $record->status === 'published'),
                Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-eye')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Publish Newsletter')
                    ->modalDescription('Setelah dipublish, newsletter dapat dikirim ke subscriber.')
                    ->modalSubmitActionLabel('Publish')
                    ->action(function (Newsletter $record) {
                        $record->publish();
                        Notification::make()
                            ->title('Newsletter dipublish')
                            ->body("Newsletter '{$record->title}' sekarang dapat dikirim")
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Newsletter $record): bool => $record->status === 'draft'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => false), // Disabled for now
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListNewsletters::route('/'),
            'create' => Pages\CreateNewsletter::route('/create'),
            'edit' => Pages\EditNewsletter::route('/{record}/edit'),
        ];
    }
}
