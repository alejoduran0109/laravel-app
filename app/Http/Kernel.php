protected $middlewareGroups = [
    'web' => [
        // ... existing code ...
    ],
    
    'filament' => [
        \App\Http\Middleware\CheckFilamentPermissions::class,
    ],
];
protected $middlewareAliases = [
    // ... existing code ...
    'check.resource.permission' => \App\Http\Middleware\CheckResourcePermission::class,
];