<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Resources\Pages\EditRecord;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected static ?string $title = 'Editar Permiso';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 