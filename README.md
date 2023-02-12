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

### make migration

```cmd
php artisan make:migrate --path=platform\plugins\<your_plugin>\database\migrations
```

