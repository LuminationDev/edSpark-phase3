# edSpark
built with Vuejs, Typescript, Tailwind, Laravel and MySQL (VTLM-stack, I make my own acronym!)

Additional services to run 
1. Meilisearch for globalsearch capabilities with Laravel Scout
2. php artisan queue worker <br>
Meilisearch is running off Azure free tier and will host locally once we moved to DfE's managed hosting
Queue worker should be running on production server (Apache - need to double check when migrating)

Refer to this link below to start using meilisearch locally
https://laravel-news.com/getting-started-laravel-scout-meilisearch
