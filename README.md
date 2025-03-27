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

https://www.markdownguide.org/cheat-sheet/

## Some section's logic on edSpark

### Partner restriction
User who has role as 'partner' will not be able to access advice, software and school section.
Means:
- API: Disable all advice, software and school API for user role 'partner'
- API: Create own partner controller-only allow their own resources/posts ? (Under consideration)
- Client: Router middleware, do not add disabled section when rendering navbar and registering route
- Client: Simplified dashboard. (Possibly just their own page?)

Router middleware
- Checks if user is partner
    if yes, check for specific meta / specific to be determined
    if not, next

router.ts -> meta restrictPartner?

`` this two below are closely related, will make it work closely
database->partner_meta ? 
and inside the API?
```pseudo code
    if(Auth::User()->role->role_name === 'Partner'){
        Partnermeta::Where userId = User-> id && partner_meta_key === 'allowed_section'
        if ( allowedType contains currentResourceType) return fetchAllPosts (adv, software, evt)
        else fetchUserPosts (adv, software, evt)
        (Query with current APIs)
    }
```
<!-- Hidden comment -->
