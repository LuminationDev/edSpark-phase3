# edSpark
built with Vuejs, Typescript, Tailwind, Laravel and MySQL (VTLM-stack, I make my own acronym!)

Additional services to run 
1. Meilisearch for globalsearch capabilities with Laravel Scout
2. php artisan queue worker <br>
Meilisearch is running off Azure free tier and will host locally once we moved to DfE's managed hosting
Queue worker should be running on production server (Apache - need to double check when migrating)

Still migrating to Typescript, doing it slowly and as we go <br>
Migrating logic and functions into ServiceFiles, checkout softwareService, autosaveService etc <br>
Vue only for rendering UI and simple UI logic<br>
Store for states. getter and actions focus on state manipulation <br>

SWRV are used only for simple and quick fetching <br>
Ignore recommender class. not sure if it's the right approach <br>

Refer to this link below to start using meilisearch locally
https://laravel-news.com/getting-started-laravel-scout-meilisearch


Site-wide works (in queue)
1. Harden Authentication. Currently a little patchy and API are not protected. Some user related API are not safe (Will need to be overhauled - will allocate some focus time)
2. Pagination on fetch all requests