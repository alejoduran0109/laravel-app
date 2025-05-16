<?php

namespace App\Providers\Filament;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\PermissionResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder; // Importar NavigationBuilder
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->resources([ // Los recursos deben registrarse aquí para que las rutas funcionen
                UserResource::class,
                RoleResource::class,
                PermissionResource::class,
            ])
            ->pages([
                Pages\Dashboard::class, // Asegúrate que tu página de Dashboard esté registrada
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\CheckFilamentPermissions::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web')
            ->navigationGroups([ // Define los grupos de navegación
                'Administración',
                // Puedes añadir más grupos si los necesitas
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                $finalNavigationItems = [];

                // Añadir Dashboard (u otros items de Pages)
                // Pages\Dashboard::getNavigationItems() devuelve un array de NavigationItem
                // Normalmente es un solo item.
                if (class_exists(Pages\Dashboard::class)) {
                    foreach (Pages\Dashboard::getNavigationItems() as $dashboardItem) {
                        // El Dashboard usualmente no tiene grupo o tiene uno por defecto.
                        // Puedes asignarle un sort si quieres que aparezca en un orden específico.
                        $finalNavigationItems[] = $dashboardItem->sort(-1); // Ejemplo: para ponerlo al principio
                    }
                }


                // Añadir items de recursos condicionalmente
                if (auth()->user()?->can('view_usuarios')) {
                    // UserResource::getNavigationItems() devuelve un array de NavigationItem.
                    // Usualmente queremos el primero/principal.
                    $resourceNavItems = UserResource::getNavigationItems();
                    if (isset($resourceNavItems[0])) {
                        $finalNavigationItems[] = $resourceNavItems[0]
                                                    ->group('Administración')
                                                    ->sort(1);
                    }
                }

                if (auth()->user()?->can('view_roles')) {
                    $resourceNavItems = RoleResource::getNavigationItems();
                    if (isset($resourceNavItems[0])) {
                        $finalNavigationItems[] = $resourceNavItems[0]
                                                    ->group('Administración')
                                                    ->sort(2);
                    }
                }

                if (auth()->user()?->can('view_permisos')) {
                    $resourceNavItems = PermissionResource::getNavigationItems();
                    if (isset($resourceNavItems[0])) {
                        $finalNavigationItems[] = $resourceNavItems[0]
                                                    ->group('Administración')
                                                    ->sort(3);
                    }
                }

                return $builder->items($finalNavigationItems);
            });
    }
}
