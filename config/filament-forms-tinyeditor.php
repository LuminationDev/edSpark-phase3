<?php
$content_style= "
body {font-family: MuseoSans !important;}
html {font-family: MuseoSans !important;}
h1 {
    display: block;
    /* font-size: 2em; */
    margin-block-start: 0.67em;
    margin-block-end: 0.67em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: bold;
    margin-top: 32px;
    margin-bottom: 16px;
    font-size: xx-large;
}

h2 {
    display: block;
    /* font-size: 1.5em; */
    margin-block-start: 0.83em;
    margin-block-end: 0.83em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: bold;
    margin-top: 28px;
    margin-bottom: 16px;
    font-size: x-large;
}

h3 {
    display: block;
    /* font-size: 1.17em; */
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: bold;
    margin-top: 24px;
    margin-bottom: 16px;
    font-size: larger;
}

h4 {
    display: block;
    margin-block-start: 1.33em;
    margin-block-end: 1.33em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: bold;
    margin-top: 20px;
    margin-bottom: 14px;
    font-size: large;
}

h5 {
    display: block;
    /* font-size: 0.83em; */
    margin-block-start: 1.67em;
    margin-block-end: 1.67em;
    margin-inline-start: 0px;
    margin-inline-eqnd: 0px;
    font-weight: 500;
    margin-top: 20px;
    margin-bottom: 14px;
    font-size: large;
}

h6 {
    display: block;
    /* font-size: 0.67em; */
    margin-block-start: 2.33em;
    margin-block-end: 2.33em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: 500;
    margin-top: 20px;
    margin-bottom: 14px;
    font-size: medium;
}

p {
    font-weight: 100;
    padding-bottom: 16px;
    line-height: 1.5;
    font-size: large;
}
";
return [
    /*
    |--------------------------------------------------------------------------
    | Profiles
    |--------------------------------------------------------------------------
    |
    | You can add as many as you want of profiles to use it in your application.
    |
    */

    'profiles' => [

        'default' => [
            'menubar' => true,
            'plugins' => 'advlist autoresize codesample directionality emoticons fullscreen hr image imagetools link lists paste media table wordcount',
            'toolbar' => 'undo redo removeformat | formatselect | bold italic | alignjustify alignleft aligncenter alignright lineheight | numlist bullist | forecolor backcolor | blockquote table toc hr | image link media codesample emoticons | wordcount',
            'upload_directory' => 'uploads/advice',
            'images_upload_url' => env('VITE_SERVER_IMAGE_API') . '/imageUploadTinyMCEjs',
            'custom_configs' => [
            'browser_spellcheck' => true,
            'context_menu' => false,
                'toolbar_sticky' => true,
                'toolbar_sticky_offset' => 65,
                'image_caption' => true,
                'image_advtab' => true,
                'images_upload_url' => env('VITE_SERVER_IMAGE_API') . '/imageUploadTinyMCEjs',
                'convert_urls' => false,
                'paste_retain_style_properties' => "margin padding width height font-size font-weight color text-align ul ol li text-decoration border background float display",
                'content_css' => '/css/filament/font/font.css',
                'content_style' => $content_style,
                'image_file_types' => 'jpg,svg,webp',
                'block_formats' => 'Normal text=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6;',
                'style_formats_merge' => true,
                'contextmenu' => 'copy cut paste link image'
            ],
        ],

        'simple' => [
            'plugins' => 'autoresize directionality emoticons link wordcount',
            'toolbar' => 'removeformat | bold italic | rtl ltr | link emoticons',
            'upload_directory' => null,
        ],

        'template' => [
            'plugins' => 'autoresize template',
            'toolbar' => 'template',
            '-upload_directory' => null,
        ],
        /*
        |--------------------------------------------------------------------------
        | Custom Configs
        |--------------------------------------------------------------------------
        |
        | If you want to add custom configurations to directly tinymce
        | You can use custom_configs key as an array
        |
        */

        /*
          'default' => [
            'plugins' => 'advlist autoresize codesample directionality emoticons fullscreen hr image imagetools link lists media table toc wordcount',
            'toolbar' => 'undo redo removeformat | formatselect fontsizeselect | bold italic | rtl ltr | alignjustify alignright aligncenter alignleft | numlist bullist | forecolor backcolor | blockquote table toc hr | image link media codesample emoticons | wordcount fullscreen',
            'custom_configs' => [
                'allow_html_in_named_anchor' => true,
                'link_default_target' => '_blank',
                'codesample_global_prismjs' => true,
                'image_advtab' => true,
                'image_class_list' => [
                  [
                    'title' => 'None',
                    'value' => '',
                  ],
                  [
                    'title' => 'Fluid',
                    'value' => 'img-fluid',
                  ],
              ],
            ]
        ],
        */

    ],

    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    |
    | You can add as many as you want of templates to use it in your application.
    |
    | https://www.tiny.cloud/docs/plugins/opensource/template/#templates
    |
    | ex: TinyEditor::make('content')->profiles('template')->template('example')
    */

    'templates' => [

        'example' => [
            // content
            ['title' => 'Some title 1', 'description' => 'Some desc 1', 'content' => 'My content'],
            // url
            ['title' => 'Some title 2', 'description' => 'Some desc 2', 'url' => 'http://localhost'],
        ],

    ],
];
