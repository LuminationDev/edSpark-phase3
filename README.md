# edSpark
built with Vuejs, Typescript, Tailwind, Laravel and MySQL (VTLM-stack, I make my own acronym!)

Additional services to run 
1. Meilisearch for global search capabilities with Laravel Scout. (running on VPS on Vultr, via IP Address & masterkey)
3. php artisan queue:worker <br>
Queue worker should be running on the production server (Apache - need to double check when migrating)

Still migrating to Typescript, doing it slowly and as we go <br>
Migrating logic and functions into ServiceFiles, checkout softwareService, autosaveService etc <br>
Vue only for rendering UI and simple UI logic<br>
Store for states. getter and actions focus on state manipulation <br>

SWRV are used only for simple and quick fetching <br>

Refer to this link below to start using meilisearch locally
https://laravel-news.com/getting-started-laravel-scout-meilisearch
