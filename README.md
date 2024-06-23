# Zerops x PHP
This is the most bare-bones example of PHP app running on [Zerops](https://zerops.io) — as few libraries as possible, just a simple endpoint with connnect, read and write to a Zerops PostgreSQL database.

![php](https://github.com/zeropsio/recipe-shared-assets/blob/main/covers/cover-php.png)

<br />

## Deploy on Zerops
You can either click the deploy button to deploy directly on Zerops, or manually copy the [import yaml](https://github.com/zeropsio/recipe-php/blob/main/zerops-project-import.yml) to the import dialog in the Zerops app.

[![Deploy on Zerops](https://github.com/zeropsio/recipe-shared-assets/blob/main/deploy-button/green/deploy-button.svg)](https://app.zerops.io/recipe/php)

<br/>

## Recipe features
- **PHP 8.3** app running in a load balanced **Zerops Apache and Nginx** service
- Zerops **PostgreSQL 16** service as database
- Healthcheck setup example
- Utilization of Zerops' built-in **environment variables** system
- Utilization of Zerops' built-in **log management**

<br/>

## Production vs. development

Base of the recipe is ready for production, the difference comes down to:

- Use highly available version of the PostgreSQL database (change `mode` from `NON_HA` to `HA` in recipe YAML, `db` service section)
- Use at least two containers for the PHP service to achieve high reliability and resilience (add `minContainers: 2` in recipe YAML, `apacheapi/nginxapi` service section)

Further things to think about when running more complex, highly available PHP production apps on Zerops:

- Containers are volatile - use Zerops object storage to store your files
- Use Zerops Redis (KeyDB) for caching, storing sessions and pub/sub messaging
- Use more advanced logging lib, such as [Monolog](https://github.com/Seldaek/monolog), [Analog](https://github.com/jbroadway/analog) or [KLogger](https://github.com/katzgrau/KLogger)
  
<br/>
<br/>

Need help setting your project up? Join [Zerops Discord community](https://discord.com/invite/WDvCZ54).
