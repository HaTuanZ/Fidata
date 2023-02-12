## active api-keys plugin
```cmd
php artisan cms:plugin:activate api-keys
```

## update role & permissions

```cmd
php artisan permission:role:add author  api-keys.index api-keys.create api-keys.edit api-keys.destroy
php artisan permission:role:add administrator  api-keys.index api-keys.create api-keys.edit api-keys.destroy
php artisan cms:user:rebuild-permissions
```


## migration for plugin

### create migration

```cmd
php artisan make:migration <your_migration> --path=platform\plugins\<your_plugin>\database\migrations
```

### migrate

```cmd
php artisan migrate --path=platform\plugins\<your_plugin>\database\migrations
```

