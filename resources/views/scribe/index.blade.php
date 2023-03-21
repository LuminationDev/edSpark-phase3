<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>EDSPARK API</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../docs/css/theme-default.style.css" media="screen">
    <link rel="stylesheet" href="../docs/css/theme-default.print.css" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .javascript-example code { display: none; }
                    body .content .php-example code { display: none; }
            </style>

    <script>
        var baseUrl = "http://localhost:8000";
        var useCsrf = Boolean(1);
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="../docs/js/tryitout-4.17.0.js"></script>

    <script src="../docs/js/theme-default-4.17.0.js"></script>

</head>

<body data-languages="[&quot;javascript&quot;,&quot;php&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="../docs/images/navbar.png" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                            <button type="button" class="lang-button" data-language-name="php">php</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-fetchAdvicePosts">
                                <a href="#endpoints-GETapi-fetchAdvicePosts">GET api/fetchAdvicePosts</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-fetchSoftwarePosts">
                                <a href="#endpoints-GETapi-fetchSoftwarePosts">GET api/fetchSoftwarePosts</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-fetchEventPosts">
                                <a href="#endpoints-GETapi-fetchEventPosts">GET api/fetchEventPosts</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-fetchCommunityPosts">
                                <a href="#endpoints-GETapi-fetchCommunityPosts">GET api/fetchCommunityPosts</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-fetchUser--id-">
                                <a href="#endpoints-GETapi-fetchUser--id-">GET api/fetchUser/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-createUser">
                                <a href="#endpoints-POSTapi-createUser">POST api/createUser</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-updateUser">
                                <a href="#endpoints-POSTapi-updateUser">POST api/updateUser</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-fetchAllSites">
                                <a href="#endpoints-GETapi-fetchAllSites">GET api/fetchAllSites</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-fetchSiteById--id-">
                                <a href="#endpoints-GETapi-fetchSiteById--id-">GET api/fetchSiteById/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-fetchAllBrands">
                                <a href="#endpoints-GETapi-fetchAllBrands">GET api/fetchAllBrands</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-fetchAllCategories">
                                <a href="#endpoints-GETapi-fetchAllCategories">GET api/fetchAllCategories</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-fetchAllProducts">
                                <a href="#endpoints-GETapi-fetchAllProducts">GET api/fetchAllProducts</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                        <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: March 21, 2023</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>EDSPARK API</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000/</code>
</aside>
<p>This documentation aims to provide all the information you need to work with edSpark API.</p>
<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-fetchAdvicePosts">GET api/fetchAdvicePosts</h2>

<p>
</p>



<span id="example-requests-GETapi-fetchAdvicePosts">
<blockquote>Example request:</blockquote>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/fetchAdvicePosts"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://localhost:8000/api/fetchAdvicePosts',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-fetchAdvicePosts">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 59
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;post_id&quot;: 1,
        &quot;post_title&quot;: &quot;Aliquam rerum magnam quidem molestias aut maxime.&quot;,
        &quot;post_content&quot;: &quot;Eos eum cumque rerum veritatis fugiat. Sed ut aperiam recusandae minima est officiis vel. Mollitia et veniam in dignissimos aperiam temporibus eveniet.&quot;,
        &quot;post_excerpt&quot;: &quot;Temporibus eligendi eos quis quibusdam rem qui. Quia rerum quae non voluptatem quasi quia pariatur rerum. Molestiae voluptatum ut placeat sequi quia.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/009966?text=excepturi&quot;,
        &quot;post_date&quot;: &quot;1987-10-18 10:09:31&quot;,
        &quot;post_modified&quot;: &quot;2001-03-18 18:12:41&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;advice_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;post_id&quot;: 2,
        &quot;post_title&quot;: &quot;Eveniet dolores alias qui incidunt.&quot;,
        &quot;post_content&quot;: &quot;Dolores et non aliquam consequuntur minus quam voluptatem. Quos molestiae ad quia odit est accusantium ad. Excepturi eveniet est et itaque modi et consequatur. Animi accusamus maiores consectetur aspernatur.&quot;,
        &quot;post_excerpt&quot;: &quot;Veniam dolore ut explicabo autem. Quidem qui ullam earum similique. Nisi sed laudantium repellat architecto non.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/00ccee?text=deleniti&quot;,
        &quot;post_date&quot;: &quot;1995-06-21 13:58:21&quot;,
        &quot;post_modified&quot;: &quot;1989-12-06 02:36:39&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;advice_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;post_id&quot;: 3,
        &quot;post_title&quot;: &quot;Corrupti cum nemo aut non similique fugiat aut aut.&quot;,
        &quot;post_content&quot;: &quot;Qui et commodi id. Omnis expedita provident iusto quos minus in. Dignissimos enim alias aut facere odit sint. Officiis expedita eveniet aut excepturi vel corrupti maiores.&quot;,
        &quot;post_excerpt&quot;: &quot;Provident necessitatibus laudantium et dolore velit. In commodi aliquam sunt dolor repellat et. Maiores dolorem sit facilis dolores eius a.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/00bbdd?text=molestiae&quot;,
        &quot;post_date&quot;: &quot;1973-05-24 21:17:46&quot;,
        &quot;post_modified&quot;: &quot;2008-12-23 09:14:05&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;advice_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;post_id&quot;: 4,
        &quot;post_title&quot;: &quot;Velit eum molestiae animi illum ab dolorem.&quot;,
        &quot;post_content&quot;: &quot;Deserunt laudantium nihil vel molestiae nostrum consequuntur repellendus nostrum. Et corrupti sed dolor dolor expedita vel alias. Laudantium qui molestias vero.&quot;,
        &quot;post_excerpt&quot;: &quot;Perspiciatis veritatis et quisquam voluptas ratione dolorem ut. Maiores fugit et sunt quos aut. Quia quo minus saepe quia.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/00ffbb?text=tempora&quot;,
        &quot;post_date&quot;: &quot;1999-05-13 12:27:11&quot;,
        &quot;post_modified&quot;: &quot;2003-05-02 16:51:51&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;advice_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;post_id&quot;: 5,
        &quot;post_title&quot;: &quot;Nihil ipsum qui earum.&quot;,
        &quot;post_content&quot;: &quot;Sed similique est perferendis omnis. Quis provident itaque temporibus officiis ut. Delectus in laborum inventore.&quot;,
        &quot;post_excerpt&quot;: &quot;Et temporibus aut quisquam. Qui atque vero quae reprehenderit rerum id. Sit deleniti fugiat est numquam dolorem quia magnam vel.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/009977?text=fugit&quot;,
        &quot;post_date&quot;: &quot;1970-10-21 13:13:01&quot;,
        &quot;post_modified&quot;: &quot;2013-10-01 17:00:32&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;advice_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-fetchAdvicePosts" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-fetchAdvicePosts"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-fetchAdvicePosts" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-fetchAdvicePosts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-fetchAdvicePosts"></code></pre>
</span>
<form id="form-GETapi-fetchAdvicePosts" data-method="GET"
      data-path="api/fetchAdvicePosts"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-fetchAdvicePosts', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-fetchAdvicePosts"
                    onclick="tryItOut('GETapi-fetchAdvicePosts');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-fetchAdvicePosts"
                    onclick="cancelTryOut('GETapi-fetchAdvicePosts');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-fetchAdvicePosts" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/fetchAdvicePosts</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Content-Type"                data-endpoint="GETapi-fetchAdvicePosts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Accept"                data-endpoint="GETapi-fetchAdvicePosts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-fetchSoftwarePosts">GET api/fetchSoftwarePosts</h2>

<p>
</p>



<span id="example-requests-GETapi-fetchSoftwarePosts">
<blockquote>Example request:</blockquote>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/fetchSoftwarePosts"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://localhost:8000/api/fetchSoftwarePosts',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-fetchSoftwarePosts">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 58
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;post_id&quot;: 1,
        &quot;post_title&quot;: &quot;Vitae dolorem aut recusandae quia.&quot;,
        &quot;post_content&quot;: &quot;Ut quia nesciunt officia ipsum qui assumenda sed. Exercitationem a et ut doloribus. Est omnis ut facere ea porro qui sit. Et ex sunt et autem voluptatem dolores necessitatibus consequatur.&quot;,
        &quot;post_excerpt&quot;: &quot;Consectetur odio aut consequuntur odit voluptas. Eaque omnis occaecati quia et. Quia aperiam sit at totam rerum et. Facere minus placeat et delectus laborum.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/00ccee?text=quia&quot;,
        &quot;post_date&quot;: &quot;1977-10-30 23:07:57&quot;,
        &quot;post_modified&quot;: &quot;2011-10-27 07:07:31&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;advice_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;post_id&quot;: 2,
        &quot;post_title&quot;: &quot;Recusandae id provident iure cupiditate sed quia.&quot;,
        &quot;post_content&quot;: &quot;Aut dolorem omnis vel sit neque quo. Iste culpa neque nisi quasi labore provident id. Quod dolor consequuntur ut quia aut.&quot;,
        &quot;post_excerpt&quot;: &quot;Distinctio neque sit quo debitis sint. Quam aliquam quas eaque non. Illum ut deleniti quas aut nesciunt maxime. Ea aut tenetur non facilis accusamus facere nam dolorem.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/00ff11?text=sequi&quot;,
        &quot;post_date&quot;: &quot;1988-06-27 20:40:18&quot;,
        &quot;post_modified&quot;: &quot;2002-03-30 03:44:05&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;advice_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;post_id&quot;: 3,
        &quot;post_title&quot;: &quot;Ut dolore aperiam voluptatem explicabo eligendi.&quot;,
        &quot;post_content&quot;: &quot;Dolore id voluptatem itaque ipsa neque id natus. Alias consequatur quia quia expedita nemo. Necessitatibus autem sint qui quidem ab. Quasi quis placeat voluptatem enim.&quot;,
        &quot;post_excerpt&quot;: &quot;Optio esse blanditiis culpa sint minus. Eum placeat dolores aperiam consequatur excepturi. Ullam optio nihil eos nesciunt adipisci sunt dolorem perferendis. Non quisquam totam error dolorem.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/007777?text=dolor&quot;,
        &quot;post_date&quot;: &quot;1999-10-20 09:08:00&quot;,
        &quot;post_modified&quot;: &quot;2021-09-12 19:32:49&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;advice_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;post_id&quot;: 4,
        &quot;post_title&quot;: &quot;Qui distinctio quas vero aut officia praesentium ut.&quot;,
        &quot;post_content&quot;: &quot;Suscipit doloribus ipsa sint autem aliquam culpa qui omnis. Rerum eos perspiciatis fugiat in et. Adipisci et doloribus nesciunt aliquid. Et voluptatem ullam iure earum. Harum in nisi libero ipsa unde consequatur aliquam.&quot;,
        &quot;post_excerpt&quot;: &quot;Sequi assumenda possimus enim et sed et sed. Rem odio a eveniet eum delectus. Rerum ipsam incidunt eius.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/0055bb?text=distinctio&quot;,
        &quot;post_date&quot;: &quot;2016-11-07 02:17:18&quot;,
        &quot;post_modified&quot;: &quot;2004-03-04 02:26:11&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;advice_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;post_id&quot;: 5,
        &quot;post_title&quot;: &quot;Similique voluptatem delectus deserunt cumque incidunt.&quot;,
        &quot;post_content&quot;: &quot;Doloremque ex fugit sint aut ut enim quis. Laborum aut cumque rem sed. Et labore facilis laboriosam. Suscipit sunt sit quia est fugiat ad qui vel.&quot;,
        &quot;post_excerpt&quot;: &quot;Consequatur sit accusamus odio ducimus error ut voluptatum unde. Omnis dolor ut quia sequi cum dolorem. Excepturi est et ipsa ratione omnis consequuntur aperiam inventore.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/001166?text=minus&quot;,
        &quot;post_date&quot;: &quot;2023-02-27 05:29:52&quot;,
        &quot;post_modified&quot;: &quot;1972-11-28 19:51:38&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;advice_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-fetchSoftwarePosts" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-fetchSoftwarePosts"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-fetchSoftwarePosts" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-fetchSoftwarePosts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-fetchSoftwarePosts"></code></pre>
</span>
<form id="form-GETapi-fetchSoftwarePosts" data-method="GET"
      data-path="api/fetchSoftwarePosts"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-fetchSoftwarePosts', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-fetchSoftwarePosts"
                    onclick="tryItOut('GETapi-fetchSoftwarePosts');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-fetchSoftwarePosts"
                    onclick="cancelTryOut('GETapi-fetchSoftwarePosts');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-fetchSoftwarePosts" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/fetchSoftwarePosts</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Content-Type"                data-endpoint="GETapi-fetchSoftwarePosts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Accept"                data-endpoint="GETapi-fetchSoftwarePosts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-fetchEventPosts">GET api/fetchEventPosts</h2>

<p>
</p>



<span id="example-requests-GETapi-fetchEventPosts">
<blockquote>Example request:</blockquote>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/fetchEventPosts"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://localhost:8000/api/fetchEventPosts',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-fetchEventPosts">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 57
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;event_id&quot;: 1,
        &quot;event_title&quot;: &quot;Quisquam accusantium enim asperiores necessitatibus hic. Enim quae facilis eaque dolores. Deserunt laudantium ea rerum explicabo nisi.&quot;,
        &quot;event_content&quot;: &quot;Consequatur autem molestias tenetur. Molestiae exercitationem vel sint cum tempore illum labore modi.&quot;,
        &quot;event_excerpt&quot;: &quot;Porro molestiae voluptas fugit itaque laboriosam nisi.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/0044ee?text=eum&quot;,
        &quot;start_date&quot;: &quot;1989-04-21 16:56:41&quot;,
        &quot;end_date&quot;: &quot;2005-06-07 14:54:23&quot;,
        &quot;event_status&quot;: &quot;Published&quot;,
        &quot;event_type&quot;: &quot;test event&quot;,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;event_id&quot;: 2,
        &quot;event_title&quot;: &quot;Quia quidem et corrupti dolorum qui. Minima tempore dicta nihil nam neque voluptatum illum quo. Voluptas odio dignissimos in ipsum sunt quia. Soluta nihil est eius nesciunt odio corrupti sit.&quot;,
        &quot;event_content&quot;: &quot;Voluptatem ratione dolorem corporis asperiores ut dolorem sed odit. Autem voluptas dolorem hic. Ea voluptatum est adipisci et sit tenetur. Animi illum ut et quia maiores sed illo.&quot;,
        &quot;event_excerpt&quot;: &quot;Molestias maxime qui aut sed rerum.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/0044cc?text=occaecati&quot;,
        &quot;start_date&quot;: &quot;2006-12-09 13:40:07&quot;,
        &quot;end_date&quot;: &quot;1979-01-30 12:16:35&quot;,
        &quot;event_status&quot;: &quot;Published&quot;,
        &quot;event_type&quot;: &quot;test event&quot;,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;event_id&quot;: 3,
        &quot;event_title&quot;: &quot;Est id dolore nesciunt rerum dolor voluptas. Similique repellat omnis non ea et ducimus omnis error. Quia excepturi placeat vel.&quot;,
        &quot;event_content&quot;: &quot;Perferendis sit aliquid omnis ut. At voluptatem et sequi explicabo. Pariatur consequatur dolorem quis perferendis beatae aperiam distinctio. Deleniti maxime rerum laboriosam quaerat.&quot;,
        &quot;event_excerpt&quot;: &quot;Quasi hic non quae neque perferendis.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/0066ff?text=molestiae&quot;,
        &quot;start_date&quot;: &quot;2002-08-15 11:05:10&quot;,
        &quot;end_date&quot;: &quot;2011-03-25 23:27:15&quot;,
        &quot;event_status&quot;: &quot;Published&quot;,
        &quot;event_type&quot;: &quot;test event&quot;,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;event_id&quot;: 4,
        &quot;event_title&quot;: &quot;Ut nostrum assumenda perferendis ex reiciendis aut. Dicta accusamus et est et eaque consequatur nobis.&quot;,
        &quot;event_content&quot;: &quot;Quia incidunt itaque dolorem officiis voluptatem. Ab ipsa adipisci officiis ipsum velit et illum. Illum consequatur magni accusantium sunt sed sit. Porro libero rerum nulla asperiores aut porro cumque.&quot;,
        &quot;event_excerpt&quot;: &quot;Vel quis et cum pariatur ad illo.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/0099ee?text=voluptatem&quot;,
        &quot;start_date&quot;: &quot;2001-01-30 11:18:22&quot;,
        &quot;end_date&quot;: &quot;2009-03-22 04:45:57&quot;,
        &quot;event_status&quot;: &quot;Published&quot;,
        &quot;event_type&quot;: &quot;test event&quot;,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;event_id&quot;: 5,
        &quot;event_title&quot;: &quot;Voluptatibus ut quia ea non rem. Laboriosam ipsum vitae impedit et. Molestiae et accusamus quos laborum. Ut natus minus a harum mollitia beatae consequuntur harum.&quot;,
        &quot;event_content&quot;: &quot;Rerum aut placeat voluptatem. Sunt aliquid magni inventore mollitia ut rerum. Sunt consequatur ut eaque inventore dolorum quis.&quot;,
        &quot;event_excerpt&quot;: &quot;Vel maiores praesentium odit necessitatibus praesentium.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/00ffee?text=ipsum&quot;,
        &quot;start_date&quot;: &quot;2008-03-02 11:13:14&quot;,
        &quot;end_date&quot;: &quot;2020-12-31 06:11:32&quot;,
        &quot;event_status&quot;: &quot;Published&quot;,
        &quot;event_type&quot;: &quot;test event&quot;,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;event_id&quot;: 6,
        &quot;event_title&quot;: &quot;Test event test&quot;,
        &quot;event_content&quot;: &quot;&lt;p&gt;test test event content&lt;/p&gt;&quot;,
        &quot;event_excerpt&quot;: &quot;&lt;p&gt;test test event excerpt&lt;/p&gt;&quot;,
        &quot;author&quot;: &quot;&quot;,
        &quot;cover_image&quot;: null,
        &quot;start_date&quot;: &quot;2023-03-28 00:00:00&quot;,
        &quot;end_date&quot;: &quot;2023-03-29 00:00:00&quot;,
        &quot;event_status&quot;: &quot;Published&quot;,
        &quot;event_type&quot;: &quot;test event&quot;,
        &quot;created_at&quot;: &quot;2023-03-20T21:36:50.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-20T22:07:08.000000Z&quot;
    },
    {
        &quot;event_id&quot;: 7,
        &quot;event_title&quot;: &quot;another event&quot;,
        &quot;event_content&quot;: &quot;&lt;p&gt;another event&lt;/p&gt;&quot;,
        &quot;event_excerpt&quot;: &quot;&lt;p&gt;another event&lt;/p&gt;&quot;,
        &quot;author&quot;: &quot;Asim Thapa&quot;,
        &quot;cover_image&quot;: null,
        &quot;start_date&quot;: &quot;2023-03-22 00:00:00&quot;,
        &quot;end_date&quot;: &quot;2023-03-24 00:00:00&quot;,
        &quot;event_status&quot;: &quot;Published&quot;,
        &quot;event_type&quot;: &quot;test event&quot;,
        &quot;created_at&quot;: &quot;2023-03-20T21:40:21.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-20T21:40:21.000000Z&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-fetchEventPosts" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-fetchEventPosts"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-fetchEventPosts" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-fetchEventPosts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-fetchEventPosts"></code></pre>
</span>
<form id="form-GETapi-fetchEventPosts" data-method="GET"
      data-path="api/fetchEventPosts"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-fetchEventPosts', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-fetchEventPosts"
                    onclick="tryItOut('GETapi-fetchEventPosts');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-fetchEventPosts"
                    onclick="cancelTryOut('GETapi-fetchEventPosts');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-fetchEventPosts" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/fetchEventPosts</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Content-Type"                data-endpoint="GETapi-fetchEventPosts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Accept"                data-endpoint="GETapi-fetchEventPosts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-fetchCommunityPosts">GET api/fetchCommunityPosts</h2>

<p>
</p>



<span id="example-requests-GETapi-fetchCommunityPosts">
<blockquote>Example request:</blockquote>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/fetchCommunityPosts"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://localhost:8000/api/fetchCommunityPosts',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-fetchCommunityPosts">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 56
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;community_id&quot;: 1,
        &quot;post_title&quot;: &quot;Facilis esse possimus iusto commodi sint ut voluptatum in.&quot;,
        &quot;post_content&quot;: &quot;Praesentium reiciendis et quae laborum consequatur tempora. Est sit dolores quo animi deserunt vel quisquam exercitationem. Beatae earum qui delectus.&quot;,
        &quot;post_excerpt&quot;: &quot;Aspernatur dolorem ut nostrum. Est asperiores suscipit ea autem quia assumenda odit nobis. Quisquam provident vel debitis quo voluptatibus. Perferendis rem iure nulla fugiat sint.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/0044ee?text=ut&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;community_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;community_id&quot;: 2,
        &quot;post_title&quot;: &quot;Et voluptates ducimus rem nulla nihil.&quot;,
        &quot;post_content&quot;: &quot;Odio possimus perspiciatis vel ad sunt voluptatem. Nemo quia nemo non qui et maxime. Aut ut et pariatur et dolore.&quot;,
        &quot;post_excerpt&quot;: &quot;Voluptate voluptates ad corporis dicta dolores ex. Ullam veniam quos repudiandae eveniet quas aut sed. Fuga aut ut sed est vel. Quia voluptatem numquam ut ad unde est.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/003311?text=enim&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;community_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;community_id&quot;: 3,
        &quot;post_title&quot;: &quot;Illum assumenda aut nisi vero et qui.&quot;,
        &quot;post_content&quot;: &quot;Itaque beatae nam quo excepturi quae asperiores. Corrupti sed voluptate non accusantium. Consequatur odio consequatur temporibus rerum dolorum excepturi iste hic. Qui laudantium quam ducimus ex rerum accusantium nam.&quot;,
        &quot;post_excerpt&quot;: &quot;Porro qui molestiae deleniti nulla maiores distinctio. Magnam et laborum impedit iste ipsa alias numquam. Rem non eos quod non. Molestiae nostrum dolor corrupti sunt illum atque nulla tempore.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/00aaff?text=et&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;community_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;community_id&quot;: 4,
        &quot;post_title&quot;: &quot;Commodi ratione aut laboriosam ad nihil.&quot;,
        &quot;post_content&quot;: &quot;Deleniti maiores qui animi soluta dolorum repudiandae. Aut nulla consequuntur odio rerum provident.&quot;,
        &quot;post_excerpt&quot;: &quot;Cupiditate eligendi laboriosam vitae sequi et enim aut. Veritatis quia sunt rem omnis velit a tenetur. Sit culpa commodi doloribus qui velit alias placeat.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/0011ff?text=illo&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;community_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;community_id&quot;: 5,
        &quot;post_title&quot;: &quot;Dolores quo non expedita beatae cumque.&quot;,
        &quot;post_content&quot;: &quot;Quod voluptatum sint fugit ut fugiat veniam quasi amet. Aperiam a qui non aut et. Assumenda aut quod sint. Consequatur rerum aut fugiat consectetur consequuntur ipsa quae.&quot;,
        &quot;post_excerpt&quot;: &quot;Molestiae suscipit beatae aut ipsum non delectus velit assumenda. Ut cupiditate sed et dolores minus eius quasi quibusdam. Vel distinctio eos voluptatum nobis.&quot;,
        &quot;author&quot;: &quot;Jake Mackinlay&quot;,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/00ffdd?text=id&quot;,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;community_type&quot;: null,
        &quot;created_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-16T05:19:06.000000Z&quot;
    },
    {
        &quot;community_id&quot;: 6,
        &quot;post_title&quot;: &quot;test community post&quot;,
        &quot;post_content&quot;: &quot;&lt;p&gt;test content&lt;/p&gt;&quot;,
        &quot;post_excerpt&quot;: &quot;&lt;p&gt;test excerpt&lt;/p&gt;&quot;,
        &quot;author&quot;: &quot;Asim Thapa&quot;,
        &quot;cover_image&quot;: null,
        &quot;post_status&quot;: &quot;Published&quot;,
        &quot;community_type&quot;: &quot;test community type&quot;,
        &quot;created_at&quot;: &quot;2023-03-21T00:33:55.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-03-21T00:36:10.000000Z&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-fetchCommunityPosts" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-fetchCommunityPosts"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-fetchCommunityPosts" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-fetchCommunityPosts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-fetchCommunityPosts"></code></pre>
</span>
<form id="form-GETapi-fetchCommunityPosts" data-method="GET"
      data-path="api/fetchCommunityPosts"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-fetchCommunityPosts', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-fetchCommunityPosts"
                    onclick="tryItOut('GETapi-fetchCommunityPosts');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-fetchCommunityPosts"
                    onclick="cancelTryOut('GETapi-fetchCommunityPosts');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-fetchCommunityPosts" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/fetchCommunityPosts</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Content-Type"                data-endpoint="GETapi-fetchCommunityPosts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Accept"                data-endpoint="GETapi-fetchCommunityPosts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-fetchUser--id-">GET api/fetchUser/{id}</h2>

<p>
</p>



<span id="example-requests-GETapi-fetchUser--id-">
<blockquote>Example request:</blockquote>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/fetchUser/culpa"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://localhost:8000/api/fetchUser/culpa',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-fetchUser--id-">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 55
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Attempt to read property \&quot;id\&quot; on null&quot;,
    &quot;exception&quot;: &quot;ErrorException&quot;,
    &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\app\\Http\\Controllers\\UserController.php&quot;,
    &quot;line&quot;: 30,
    &quot;trace&quot;: [
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Bootstrap\\HandleExceptions.php&quot;,
            &quot;line&quot;: 266,
            &quot;function&quot;: &quot;handleError&quot;,
            &quot;class&quot;: &quot;Illuminate\\Foundation\\Bootstrap\\HandleExceptions&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\app\\Http\\Controllers\\UserController.php&quot;,
            &quot;line&quot;: 30,
            &quot;function&quot;: &quot;Illuminate\\Foundation\\Bootstrap\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Foundation\\Bootstrap\\HandleExceptions&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Controller.php&quot;,
            &quot;line&quot;: 54,
            &quot;function&quot;: &quot;fetchUser&quot;,
            &quot;class&quot;: &quot;App\\Http\\Controllers\\UserController&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\ControllerDispatcher.php&quot;,
            &quot;line&quot;: 43,
            &quot;function&quot;: &quot;callAction&quot;,
            &quot;class&quot;: &quot;Illuminate\\Routing\\Controller&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php&quot;,
            &quot;line&quot;: 259,
            &quot;function&quot;: &quot;dispatch&quot;,
            &quot;class&quot;: &quot;Illuminate\\Routing\\ControllerDispatcher&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php&quot;,
            &quot;line&quot;: 205,
            &quot;function&quot;: &quot;runController&quot;,
            &quot;class&quot;: &quot;Illuminate\\Routing\\Route&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php&quot;,
            &quot;line&quot;: 798,
            &quot;function&quot;: &quot;run&quot;,
            &quot;class&quot;: &quot;Illuminate\\Routing\\Route&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php&quot;,
            &quot;line&quot;: 141,
            &quot;function&quot;: &quot;Illuminate\\Routing\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Routing\\Router&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\SubstituteBindings.php&quot;,
            &quot;line&quot;: 50,
            &quot;function&quot;: &quot;Illuminate\\Pipeline\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Pipeline\\Pipeline&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php&quot;,
            &quot;line&quot;: 180,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Illuminate\\Routing\\Middleware\\SubstituteBindings&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\ThrottleRequests.php&quot;,
            &quot;line&quot;: 126,
            &quot;function&quot;: &quot;Illuminate\\Pipeline\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Pipeline\\Pipeline&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\ThrottleRequests.php&quot;,
            &quot;line&quot;: 92,
            &quot;function&quot;: &quot;handleRequest&quot;,
            &quot;class&quot;: &quot;Illuminate\\Routing\\Middleware\\ThrottleRequests&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\ThrottleRequests.php&quot;,
            &quot;line&quot;: 54,
            &quot;function&quot;: &quot;handleRequestUsingNamedLimiter&quot;,
            &quot;class&quot;: &quot;Illuminate\\Routing\\Middleware\\ThrottleRequests&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php&quot;,
            &quot;line&quot;: 180,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Illuminate\\Routing\\Middleware\\ThrottleRequests&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php&quot;,
            &quot;line&quot;: 116,
            &quot;function&quot;: &quot;Illuminate\\Pipeline\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Pipeline\\Pipeline&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php&quot;,
            &quot;line&quot;: 797,
            &quot;function&quot;: &quot;then&quot;,
            &quot;class&quot;: &quot;Illuminate\\Pipeline\\Pipeline&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php&quot;,
            &quot;line&quot;: 776,
            &quot;function&quot;: &quot;runRouteWithinStack&quot;,
            &quot;class&quot;: &quot;Illuminate\\Routing\\Router&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php&quot;,
            &quot;line&quot;: 740,
            &quot;function&quot;: &quot;runRoute&quot;,
            &quot;class&quot;: &quot;Illuminate\\Routing\\Router&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php&quot;,
            &quot;line&quot;: 729,
            &quot;function&quot;: &quot;dispatchToRoute&quot;,
            &quot;class&quot;: &quot;Illuminate\\Routing\\Router&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php&quot;,
            &quot;line&quot;: 190,
            &quot;function&quot;: &quot;dispatch&quot;,
            &quot;class&quot;: &quot;Illuminate\\Routing\\Router&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php&quot;,
            &quot;line&quot;: 141,
            &quot;function&quot;: &quot;Illuminate\\Foundation\\Http\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Foundation\\Http\\Kernel&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\livewire\\livewire\\src\\DisableBrowserCache.php&quot;,
            &quot;line&quot;: 19,
            &quot;function&quot;: &quot;Illuminate\\Pipeline\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Pipeline\\Pipeline&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php&quot;,
            &quot;line&quot;: 180,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Livewire\\DisableBrowserCache&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php&quot;,
            &quot;line&quot;: 21,
            &quot;function&quot;: &quot;Illuminate\\Pipeline\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Pipeline\\Pipeline&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull.php&quot;,
            &quot;line&quot;: 31,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php&quot;,
            &quot;line&quot;: 180,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php&quot;,
            &quot;line&quot;: 21,
            &quot;function&quot;: &quot;Illuminate\\Pipeline\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Pipeline\\Pipeline&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TrimStrings.php&quot;,
            &quot;line&quot;: 40,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php&quot;,
            &quot;line&quot;: 180,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Illuminate\\Foundation\\Http\\Middleware\\TrimStrings&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize.php&quot;,
            &quot;line&quot;: 27,
            &quot;function&quot;: &quot;Illuminate\\Pipeline\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Pipeline\\Pipeline&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php&quot;,
            &quot;line&quot;: 180,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance.php&quot;,
            &quot;line&quot;: 86,
            &quot;function&quot;: &quot;Illuminate\\Pipeline\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Pipeline\\Pipeline&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php&quot;,
            &quot;line&quot;: 180,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Middleware\\HandleCors.php&quot;,
            &quot;line&quot;: 62,
            &quot;function&quot;: &quot;Illuminate\\Pipeline\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Pipeline\\Pipeline&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php&quot;,
            &quot;line&quot;: 180,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Illuminate\\Http\\Middleware\\HandleCors&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Middleware\\TrustProxies.php&quot;,
            &quot;line&quot;: 39,
            &quot;function&quot;: &quot;Illuminate\\Pipeline\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Pipeline\\Pipeline&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php&quot;,
            &quot;line&quot;: 180,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Illuminate\\Http\\Middleware\\TrustProxies&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php&quot;,
            &quot;line&quot;: 116,
            &quot;function&quot;: &quot;Illuminate\\Pipeline\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Pipeline\\Pipeline&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php&quot;,
            &quot;line&quot;: 165,
            &quot;function&quot;: &quot;then&quot;,
            &quot;class&quot;: &quot;Illuminate\\Pipeline\\Pipeline&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php&quot;,
            &quot;line&quot;: 134,
            &quot;function&quot;: &quot;sendRequestThroughRouter&quot;,
            &quot;class&quot;: &quot;Illuminate\\Foundation\\Http\\Kernel&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php&quot;,
            &quot;line&quot;: 299,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Illuminate\\Foundation\\Http\\Kernel&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php&quot;,
            &quot;line&quot;: 287,
            &quot;function&quot;: &quot;callLaravelOrLumenRoute&quot;,
            &quot;class&quot;: &quot;Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php&quot;,
            &quot;line&quot;: 92,
            &quot;function&quot;: &quot;makeApiCall&quot;,
            &quot;class&quot;: &quot;Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php&quot;,
            &quot;line&quot;: 45,
            &quot;function&quot;: &quot;makeResponseCall&quot;,
            &quot;class&quot;: &quot;Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Strategies\\Responses\\ResponseCalls.php&quot;,
            &quot;line&quot;: 35,
            &quot;function&quot;: &quot;makeResponseCallIfConditionsPass&quot;,
            &quot;class&quot;: &quot;Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Extractor.php&quot;,
            &quot;line&quot;: 209,
            &quot;function&quot;: &quot;__invoke&quot;,
            &quot;class&quot;: &quot;Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Extractor.php&quot;,
            &quot;line&quot;: 163,
            &quot;function&quot;: &quot;iterateThroughStrategies&quot;,
            &quot;class&quot;: &quot;Knuckles\\Scribe\\Extracting\\Extractor&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\knuckleswtf\\scribe\\src\\Extracting\\Extractor.php&quot;,
            &quot;line&quot;: 95,
            &quot;function&quot;: &quot;fetchResponses&quot;,
            &quot;class&quot;: &quot;Knuckles\\Scribe\\Extracting\\Extractor&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\knuckleswtf\\scribe\\src\\GroupedEndpoints\\GroupedEndpointsFromApp.php&quot;,
            &quot;line&quot;: 124,
            &quot;function&quot;: &quot;processRoute&quot;,
            &quot;class&quot;: &quot;Knuckles\\Scribe\\Extracting\\Extractor&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\knuckleswtf\\scribe\\src\\GroupedEndpoints\\GroupedEndpointsFromApp.php&quot;,
            &quot;line&quot;: 71,
            &quot;function&quot;: &quot;extractEndpointsInfoFromLaravelApp&quot;,
            &quot;class&quot;: &quot;Knuckles\\Scribe\\GroupedEndpoints\\GroupedEndpointsFromApp&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\knuckleswtf\\scribe\\src\\GroupedEndpoints\\GroupedEndpointsFromApp.php&quot;,
            &quot;line&quot;: 49,
            &quot;function&quot;: &quot;extractEndpointsInfoAndWriteToDisk&quot;,
            &quot;class&quot;: &quot;Knuckles\\Scribe\\GroupedEndpoints\\GroupedEndpointsFromApp&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\knuckleswtf\\scribe\\src\\Commands\\GenerateDocumentation.php&quot;,
            &quot;line&quot;: 51,
            &quot;function&quot;: &quot;get&quot;,
            &quot;class&quot;: &quot;Knuckles\\Scribe\\GroupedEndpoints\\GroupedEndpointsFromApp&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php&quot;,
            &quot;line&quot;: 36,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Knuckles\\Scribe\\Commands\\GenerateDocumentation&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php&quot;,
            &quot;line&quot;: 41,
            &quot;function&quot;: &quot;Illuminate\\Container\\{closure}&quot;,
            &quot;class&quot;: &quot;Illuminate\\Container\\BoundMethod&quot;,
            &quot;type&quot;: &quot;::&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php&quot;,
            &quot;line&quot;: 93,
            &quot;function&quot;: &quot;unwrapIfClosure&quot;,
            &quot;class&quot;: &quot;Illuminate\\Container\\Util&quot;,
            &quot;type&quot;: &quot;::&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php&quot;,
            &quot;line&quot;: 35,
            &quot;function&quot;: &quot;callBoundMethod&quot;,
            &quot;class&quot;: &quot;Illuminate\\Container\\BoundMethod&quot;,
            &quot;type&quot;: &quot;::&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php&quot;,
            &quot;line&quot;: 661,
            &quot;function&quot;: &quot;call&quot;,
            &quot;class&quot;: &quot;Illuminate\\Container\\BoundMethod&quot;,
            &quot;type&quot;: &quot;::&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php&quot;,
            &quot;line&quot;: 183,
            &quot;function&quot;: &quot;call&quot;,
            &quot;class&quot;: &quot;Illuminate\\Container\\Container&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\symfony\\console\\Command\\Command.php&quot;,
            &quot;line&quot;: 312,
            &quot;function&quot;: &quot;execute&quot;,
            &quot;class&quot;: &quot;Illuminate\\Console\\Command&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php&quot;,
            &quot;line&quot;: 152,
            &quot;function&quot;: &quot;run&quot;,
            &quot;class&quot;: &quot;Symfony\\Component\\Console\\Command\\Command&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\symfony\\console\\Application.php&quot;,
            &quot;line&quot;: 1022,
            &quot;function&quot;: &quot;run&quot;,
            &quot;class&quot;: &quot;Illuminate\\Console\\Command&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\symfony\\console\\Application.php&quot;,
            &quot;line&quot;: 314,
            &quot;function&quot;: &quot;doRunCommand&quot;,
            &quot;class&quot;: &quot;Symfony\\Component\\Console\\Application&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\symfony\\console\\Application.php&quot;,
            &quot;line&quot;: 168,
            &quot;function&quot;: &quot;doRun&quot;,
            &quot;class&quot;: &quot;Symfony\\Component\\Console\\Application&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php&quot;,
            &quot;line&quot;: 102,
            &quot;function&quot;: &quot;run&quot;,
            &quot;class&quot;: &quot;Symfony\\Component\\Console\\Application&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php&quot;,
            &quot;line&quot;: 155,
            &quot;function&quot;: &quot;run&quot;,
            &quot;class&quot;: &quot;Illuminate\\Console\\Application&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        },
        {
            &quot;file&quot;: &quot;C:\\Users\\athap\\Desktop\\Projects-Lumination\\edSpark-phase3\\artisan&quot;,
            &quot;line&quot;: 35,
            &quot;function&quot;: &quot;handle&quot;,
            &quot;class&quot;: &quot;Illuminate\\Foundation\\Console\\Kernel&quot;,
            &quot;type&quot;: &quot;-&gt;&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-fetchUser--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-fetchUser--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-fetchUser--id-" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-fetchUser--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-fetchUser--id-"></code></pre>
</span>
<form id="form-GETapi-fetchUser--id-" data-method="GET"
      data-path="api/fetchUser/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-fetchUser--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-fetchUser--id-"
                    onclick="tryItOut('GETapi-fetchUser--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-fetchUser--id-"
                    onclick="cancelTryOut('GETapi-fetchUser--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-fetchUser--id-" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/fetchUser/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Content-Type"                data-endpoint="GETapi-fetchUser--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Accept"                data-endpoint="GETapi-fetchUser--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="id"                data-endpoint="GETapi-fetchUser--id-"
               value="culpa"
               data-component="url">
    <br>
<p>The ID of the fetchUser. Example: <code>culpa</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-createUser">POST api/createUser</h2>

<p>
</p>



<span id="example-requests-POSTapi-createUser">
<blockquote>Example request:</blockquote>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/createUser"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;post(
    'http://localhost:8000/api/createUser',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-createUser">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 54
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;User added successfully&quot;,
    &quot;error&quot;: &quot;&quot;,
    &quot;status&quot;: 200
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-createUser" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-createUser"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-createUser" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-createUser" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-createUser"></code></pre>
</span>
<form id="form-POSTapi-createUser" data-method="POST"
      data-path="api/createUser"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-createUser', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-createUser"
                    onclick="tryItOut('POSTapi-createUser');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-createUser"
                    onclick="cancelTryOut('POSTapi-createUser');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-createUser" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/createUser</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Content-Type"                data-endpoint="POSTapi-createUser"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Accept"                data-endpoint="POSTapi-createUser"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-updateUser">POST api/updateUser</h2>

<p>
</p>



<span id="example-requests-POSTapi-updateUser">
<blockquote>Example request:</blockquote>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/updateUser"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;post(
    'http://localhost:8000/api/updateUser',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-updateUser">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 53
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;User updated successfully&quot;,
    &quot;error&quot;: &quot;&quot;,
    &quot;status&quot;: 200
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-updateUser" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-updateUser"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-updateUser" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-updateUser" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-updateUser"></code></pre>
</span>
<form id="form-POSTapi-updateUser" data-method="POST"
      data-path="api/updateUser"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-updateUser', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-updateUser"
                    onclick="tryItOut('POSTapi-updateUser');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-updateUser"
                    onclick="cancelTryOut('POSTapi-updateUser');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-updateUser" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/updateUser</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Content-Type"                data-endpoint="POSTapi-updateUser"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Accept"                data-endpoint="POSTapi-updateUser"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-fetchAllSites">GET api/fetchAllSites</h2>

<p>
</p>



<span id="example-requests-GETapi-fetchAllSites">
<blockquote>Example request:</blockquote>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/fetchAllSites"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://localhost:8000/api/fetchAllSites',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-fetchAllSites">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 52
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 1,
        &quot;site_id&quot;: null,
        &quot;site_name&quot;: &quot;South Carolina&quot;,
        &quot;site_value&quot;: &quot;799 Marquardt Vista\nNew Ilaland, SC 49712-4930&quot;,
        &quot;category_code&quot;: null,
        &quot;category_desc&quot;: null,
        &quot;site_type_code&quot;: null,
        &quot;site_type_desc&quot;: null,
        &quot;site_sub_type_code&quot;: null,
        &quot;site_sub_type_desc&quot;: null
    },
    {
        &quot;id&quot;: 2,
        &quot;site_id&quot;: null,
        &quot;site_name&quot;: &quot;Washington&quot;,
        &quot;site_value&quot;: &quot;25699 Raynor Flats Suite 903\nPort Lenora, TN 28034-7468&quot;,
        &quot;category_code&quot;: null,
        &quot;category_desc&quot;: null,
        &quot;site_type_code&quot;: null,
        &quot;site_type_desc&quot;: null,
        &quot;site_sub_type_code&quot;: null,
        &quot;site_sub_type_desc&quot;: null
    },
    {
        &quot;id&quot;: 3,
        &quot;site_id&quot;: null,
        &quot;site_name&quot;: &quot;Arkansas&quot;,
        &quot;site_value&quot;: &quot;30675 Cyrus Prairie\nErikshire, AL 76522-1417&quot;,
        &quot;category_code&quot;: null,
        &quot;category_desc&quot;: null,
        &quot;site_type_code&quot;: null,
        &quot;site_type_desc&quot;: null,
        &quot;site_sub_type_code&quot;: null,
        &quot;site_sub_type_desc&quot;: null
    },
    {
        &quot;id&quot;: 4,
        &quot;site_id&quot;: null,
        &quot;site_name&quot;: &quot;Colorado&quot;,
        &quot;site_value&quot;: &quot;62820 Alva Summit\nNew Brennan, KY 72330&quot;,
        &quot;category_code&quot;: null,
        &quot;category_desc&quot;: null,
        &quot;site_type_code&quot;: null,
        &quot;site_type_desc&quot;: null,
        &quot;site_sub_type_code&quot;: null,
        &quot;site_sub_type_desc&quot;: null
    },
    {
        &quot;id&quot;: 5,
        &quot;site_id&quot;: null,
        &quot;site_name&quot;: &quot;Delaware&quot;,
        &quot;site_value&quot;: &quot;728 Claude Estates Apt. 548\nNorth Raheemside, NC 17337-4223&quot;,
        &quot;category_code&quot;: null,
        &quot;category_desc&quot;: null,
        &quot;site_type_code&quot;: null,
        &quot;site_type_desc&quot;: null,
        &quot;site_sub_type_code&quot;: null,
        &quot;site_sub_type_desc&quot;: null
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-fetchAllSites" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-fetchAllSites"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-fetchAllSites" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-fetchAllSites" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-fetchAllSites"></code></pre>
</span>
<form id="form-GETapi-fetchAllSites" data-method="GET"
      data-path="api/fetchAllSites"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-fetchAllSites', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-fetchAllSites"
                    onclick="tryItOut('GETapi-fetchAllSites');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-fetchAllSites"
                    onclick="cancelTryOut('GETapi-fetchAllSites');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-fetchAllSites" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/fetchAllSites</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Content-Type"                data-endpoint="GETapi-fetchAllSites"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Accept"                data-endpoint="GETapi-fetchAllSites"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-fetchSiteById--id-">GET api/fetchSiteById/{id}</h2>

<p>
</p>



<span id="example-requests-GETapi-fetchSiteById--id-">
<blockquote>Example request:</blockquote>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/fetchSiteById/culpa"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://localhost:8000/api/fetchSiteById/culpa',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-fetchSiteById--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 51
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">&quot;No site found&quot;</code>
 </pre>
    </span>
<span id="execution-results-GETapi-fetchSiteById--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-fetchSiteById--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-fetchSiteById--id-" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-fetchSiteById--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-fetchSiteById--id-"></code></pre>
</span>
<form id="form-GETapi-fetchSiteById--id-" data-method="GET"
      data-path="api/fetchSiteById/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-fetchSiteById--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-fetchSiteById--id-"
                    onclick="tryItOut('GETapi-fetchSiteById--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-fetchSiteById--id-"
                    onclick="cancelTryOut('GETapi-fetchSiteById--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-fetchSiteById--id-" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/fetchSiteById/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Content-Type"                data-endpoint="GETapi-fetchSiteById--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Accept"                data-endpoint="GETapi-fetchSiteById--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="id"                data-endpoint="GETapi-fetchSiteById--id-"
               value="culpa"
               data-component="url">
    <br>
<p>The ID of the fetchSiteById. Example: <code>culpa</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-fetchAllBrands">GET api/fetchAllBrands</h2>

<p>
</p>



<span id="example-requests-GETapi-fetchAllBrands">
<blockquote>Example request:</blockquote>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/fetchAllBrands"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://localhost:8000/api/fetchAllBrands',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-fetchAllBrands">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 50
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;brandName&quot;: &quot;Autem est voluptas dicta quia. Et similique error molestiae iste similique. Autem qui quibusdam eum.&quot;,
        &quot;brandDescription&quot;: &quot;Dolorem distinctio quibusdam voluptatem et ad adipisci deserunt. Officia itaque non magnam aspernatur aut unde. Doloremque non omnis inventore dolor unde. Ipsa voluptate aspernatur voluptatem qui.&quot;
    },
    {
        &quot;brandName&quot;: &quot;Eaque adipisci ut quibusdam expedita suscipit accusantium pariatur aliquam. Atque mollitia et voluptatem ut perspiciatis.&quot;,
        &quot;brandDescription&quot;: &quot;Commodi voluptatem voluptas soluta optio sint consequuntur. Voluptas nisi quo tempora accusamus atque architecto. Consequatur et voluptatem veniam aut consequatur quidem. Magni iure est fugiat dolorum. Ducimus est alias quibusdam veritatis distinctio.&quot;
    },
    {
        &quot;brandName&quot;: &quot;Nulla a voluptatibus ut incidunt nostrum. Neque est explicabo impedit repellat facere suscipit harum.&quot;,
        &quot;brandDescription&quot;: &quot;Quis optio quas sunt ipsa explicabo fuga. Amet rerum nobis consequatur et sapiente ratione vel hic. Quis exercitationem sunt error magnam accusamus.&quot;
    },
    {
        &quot;brandName&quot;: &quot;Ipsum minima quas repudiandae nihil atque sit ducimus. Eum odio consequatur assumenda. Distinctio saepe sunt sunt laudantium quia similique.&quot;,
        &quot;brandDescription&quot;: &quot;Et quaerat consequuntur dignissimos esse laborum exercitationem quas ut. Rerum quae et dolorum sed non quae consequatur est. Cumque optio quia sequi ipsam cupiditate praesentium velit atque. Quibusdam laudantium enim eveniet eum et.&quot;
    },
    {
        &quot;brandName&quot;: &quot;Laborum veritatis dolores mollitia quia repellat. Ut non laboriosam ut delectus consequatur. Libero voluptatem harum cum placeat. Voluptates explicabo omnis facilis ut quia id.&quot;,
        &quot;brandDescription&quot;: &quot;Sit molestiae quae sit. Ut molestiae facere sed totam accusantium. Veritatis quam impedit ut sunt inventore et consequatur. Dolor fugit nesciunt omnis quod et quaerat.&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-fetchAllBrands" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-fetchAllBrands"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-fetchAllBrands" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-fetchAllBrands" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-fetchAllBrands"></code></pre>
</span>
<form id="form-GETapi-fetchAllBrands" data-method="GET"
      data-path="api/fetchAllBrands"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-fetchAllBrands', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-fetchAllBrands"
                    onclick="tryItOut('GETapi-fetchAllBrands');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-fetchAllBrands"
                    onclick="cancelTryOut('GETapi-fetchAllBrands');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-fetchAllBrands" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/fetchAllBrands</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Content-Type"                data-endpoint="GETapi-fetchAllBrands"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Accept"                data-endpoint="GETapi-fetchAllBrands"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-fetchAllCategories">GET api/fetchAllCategories</h2>

<p>
</p>



<span id="example-requests-GETapi-fetchAllCategories">
<blockquote>Example request:</blockquote>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/fetchAllCategories"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://localhost:8000/api/fetchAllCategories',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-fetchAllCategories">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 49
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;categoryName&quot;: &quot;Veniam harum nam ut eos molestiae voluptas sint cum. Corrupti consequatur in est ab facere consequatur ullam. Quis et eveniet mollitia reprehenderit velit voluptatum culpa.&quot;,
        &quot;categoryDescription&quot;: &quot;Numquam asperiores dolorum architecto nemo aut occaecati non. Atque autem et voluptatem et aut. Et quidem distinctio labore repudiandae.&quot;
    },
    {
        &quot;categoryName&quot;: &quot;Nam et adipisci beatae optio accusantium officia. Ut esse debitis voluptas qui. Natus possimus incidunt porro nobis. Nemo repudiandae in et quia eos.&quot;,
        &quot;categoryDescription&quot;: &quot;Est occaecati necessitatibus aut a ut veritatis. Harum rerum atque et.&quot;
    },
    {
        &quot;categoryName&quot;: &quot;Vel error aperiam amet exercitationem sunt nostrum. Optio ea exercitationem ut velit. Voluptate quos et possimus.&quot;,
        &quot;categoryDescription&quot;: &quot;Et a minima provident et. Doloribus harum aperiam praesentium. Et nobis incidunt hic qui accusantium. Vitae rerum architecto dolores non ut.&quot;
    },
    {
        &quot;categoryName&quot;: &quot;Consectetur odio et eos molestiae. Temporibus minus dolore voluptatibus eius aut. Quidem minima inventore dolores error labore unde aut. Aut consequatur laboriosam et nulla rerum.&quot;,
        &quot;categoryDescription&quot;: &quot;Molestiae praesentium aut aliquid debitis numquam aliquam quibusdam dicta. Officiis eaque quasi et. Molestiae quam accusantium dicta ipsum animi temporibus exercitationem. Fugiat et voluptatem numquam sunt praesentium.&quot;
    },
    {
        &quot;categoryName&quot;: &quot;Voluptatem saepe provident ad dolores. Pariatur minima facilis soluta aliquam sit omnis.&quot;,
        &quot;categoryDescription&quot;: &quot;A voluptate commodi quis eum voluptatum. Pariatur voluptatibus qui distinctio sapiente. Consequuntur pariatur nihil ullam asperiores voluptatibus accusantium similique. Expedita omnis dolore ut.&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-fetchAllCategories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-fetchAllCategories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-fetchAllCategories" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-fetchAllCategories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-fetchAllCategories"></code></pre>
</span>
<form id="form-GETapi-fetchAllCategories" data-method="GET"
      data-path="api/fetchAllCategories"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-fetchAllCategories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-fetchAllCategories"
                    onclick="tryItOut('GETapi-fetchAllCategories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-fetchAllCategories"
                    onclick="cancelTryOut('GETapi-fetchAllCategories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-fetchAllCategories" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/fetchAllCategories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Content-Type"                data-endpoint="GETapi-fetchAllCategories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Accept"                data-endpoint="GETapi-fetchAllCategories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-fetchAllProducts">GET api/fetchAllProducts</h2>

<p>
</p>



<span id="example-requests-GETapi-fetchAllProducts">
<blockquote>Example request:</blockquote>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/fetchAllProducts"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://localhost:8000/api/fetchAllProducts',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-fetchAllProducts">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 48
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 1,
        &quot;product_name&quot;: &quot;Schulist-Stroman&quot;,
        &quot;product_content&quot;: &quot;Exercitationem et nemo non hic neque provident quia. Alias aperiam quod accusamus deleniti.&quot;,
        &quot;product_excerpt&quot;: &quot;Tempore eos et itaque et accusamus. Illo adipisci perferendis voluptate ut repellendus similique cum. Ipsa ratione non architecto animi. Quia neque voluptatum molestiae qui veniam recusandae.&quot;,
        &quot;price&quot;: 100,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/00ff22?text=deserunt&quot;,
        &quot;product_SKU&quot;: &quot;100&quot;,
        &quot;category&quot;: {
            &quot;categoryId&quot;: null,
            &quot;categoryName&quot;: null
        },
        &quot;brand&quot;: {
            &quot;brandId&quot;: null,
            &quot;brandName&quot;: null
        },
        &quot;product_isLoan&quot;: null
    },
    {
        &quot;id&quot;: 2,
        &quot;product_name&quot;: &quot;Langosh, Witting and Mitchell&quot;,
        &quot;product_content&quot;: &quot;Ratione consequatur quasi impedit distinctio quasi neque. Aliquam et mollitia maxime rerum maiores debitis. Occaecati ex iusto deleniti et labore. Labore est hic accusantium unde.&quot;,
        &quot;product_excerpt&quot;: &quot;Esse officiis voluptatem maxime. Quam error culpa tenetur consectetur atque. Incidunt rerum consequatur dolor id ea voluptatem commodi. Qui distinctio repellat earum voluptas repudiandae quam qui.&quot;,
        &quot;price&quot;: 100,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/003377?text=saepe&quot;,
        &quot;product_SKU&quot;: &quot;100&quot;,
        &quot;category&quot;: {
            &quot;categoryId&quot;: null,
            &quot;categoryName&quot;: null
        },
        &quot;brand&quot;: {
            &quot;brandId&quot;: null,
            &quot;brandName&quot;: null
        },
        &quot;product_isLoan&quot;: null
    },
    {
        &quot;id&quot;: 3,
        &quot;product_name&quot;: &quot;Nolan, Moen and Beatty&quot;,
        &quot;product_content&quot;: &quot;Commodi numquam omnis aut ea atque enim. Qui maxime maxime enim optio. Culpa fugiat quis quod unde rem aut. Corrupti veniam necessitatibus ipsam quia aperiam dolorum omnis.&quot;,
        &quot;product_excerpt&quot;: &quot;Vitae sit dolores aspernatur et consectetur inventore. Minus dolor mollitia officiis beatae distinctio aut eum.&quot;,
        &quot;price&quot;: 100,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/0099aa?text=sint&quot;,
        &quot;product_SKU&quot;: &quot;100&quot;,
        &quot;category&quot;: {
            &quot;categoryId&quot;: null,
            &quot;categoryName&quot;: null
        },
        &quot;brand&quot;: {
            &quot;brandId&quot;: null,
            &quot;brandName&quot;: null
        },
        &quot;product_isLoan&quot;: null
    },
    {
        &quot;id&quot;: 4,
        &quot;product_name&quot;: &quot;Larkin-Fay&quot;,
        &quot;product_content&quot;: &quot;Repudiandae ipsum dolores ad id. Illum incidunt quod velit sit. Sint occaecati corrupti provident nisi. Quia temporibus nihil quis eaque et adipisci dolores.&quot;,
        &quot;product_excerpt&quot;: &quot;Iste veniam non perspiciatis id ea animi. Excepturi et facere ipsam ipsam voluptatibus sed. Ut harum exercitationem pariatur possimus voluptas. Voluptate dicta qui dolorem et dignissimos quas.&quot;,
        &quot;price&quot;: 100,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/00bb88?text=doloremque&quot;,
        &quot;product_SKU&quot;: &quot;100&quot;,
        &quot;category&quot;: {
            &quot;categoryId&quot;: null,
            &quot;categoryName&quot;: null
        },
        &quot;brand&quot;: {
            &quot;brandId&quot;: null,
            &quot;brandName&quot;: null
        },
        &quot;product_isLoan&quot;: null
    },
    {
        &quot;id&quot;: 5,
        &quot;product_name&quot;: &quot;Nicolas PLC&quot;,
        &quot;product_content&quot;: &quot;Quod et voluptatem ullam id. Aut dolorem odit sed similique numquam voluptatum vero. Reiciendis dolores rerum et eum mollitia eaque neque enim.&quot;,
        &quot;product_excerpt&quot;: &quot;Quia aliquam autem veniam ut laborum. Et iste laboriosam magnam repellat debitis reprehenderit. Quisquam ut quo deleniti repellat ab et.&quot;,
        &quot;price&quot;: 100,
        &quot;cover_image&quot;: &quot;https://via.placeholder.com/640x480.png/00bb99?text=dolore&quot;,
        &quot;product_SKU&quot;: &quot;100&quot;,
        &quot;category&quot;: {
            &quot;categoryId&quot;: null,
            &quot;categoryName&quot;: null
        },
        &quot;brand&quot;: {
            &quot;brandId&quot;: null,
            &quot;brandName&quot;: null
        },
        &quot;product_isLoan&quot;: null
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-fetchAllProducts" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-fetchAllProducts"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-fetchAllProducts" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-fetchAllProducts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-fetchAllProducts"></code></pre>
</span>
<form id="form-GETapi-fetchAllProducts" data-method="GET"
      data-path="api/fetchAllProducts"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-fetchAllProducts', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-fetchAllProducts"
                    onclick="tryItOut('GETapi-fetchAllProducts');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-fetchAllProducts"
                    onclick="cancelTryOut('GETapi-fetchAllProducts');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-fetchAllProducts" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/fetchAllProducts</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Content-Type"                data-endpoint="GETapi-fetchAllProducts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
               name="Accept"                data-endpoint="GETapi-fetchAllProducts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                                        <button type="button" class="lang-button" data-language-name="php">php</button>
                            </div>
            </div>
</div>
</body>
</html>
