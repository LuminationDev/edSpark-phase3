<?php
$content_style = "
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
    'version' => [
        'tiny' => '7.0.1',
        'language' => [
            'version' => '23.10.9',
            'package' => 'langs6',
        ],
        'licence_key' => env('TINY_LICENSE_KEY', 'no-api-key'),
    ],
    'provider' => 'vendor', // cloud|vendor
    // 'direction' => 'rtl',
    /**
     * change darkMode: 'auto'|'force'|'class'|'media'|false|'custom'
     */
    'darkMode' => 'auto',

    /** cutsom */
    'skins' => [
        // oxide, oxide-dark, tinymce-5, tinymce-5-dark
        'ui' => 'oxide',

        // dark, default, document, tinymce-5, tinymce-5-dark, writer
        'content' => 'default'
    ],

    'profiles' => [
        'default' => [
            'plugins' => 'accordion autoresize codesample directionality advlist link image lists preview pagebreak searchreplace wordcount code fullscreen insertdatetime media table emoticons',
            'toolbar' => 'undo redo removeformat | fontfamily fontsize font_size_formats styles | bold italic underline | rtl ltr | alignjustify alignleft aligncenter alignright | numlist bullist outdent indent | forecolor backcolor | blockquote table toc hr | image link media codesample emoticons | wordcount fullscreen',
            'upload_directory' => null,
        ],
        'edspark' => [
            'plugins' => 'accordion anchor autoresize codesample directionality advlist link image lists preview pagebreak searchreplace wordcount code fullscreen insertdatetime media table emoticons',
            'toolbar' => 'undo redo removeformat | formatselect | bold italic | alignjustify alignleft aligncenter alignright lineheight | numlist bullist | forecolor backcolor | blockquote table toc hr | image link media code anchor accordion codesample emoticons | wordcount',
            'images_upload_url' => env('VITE_SERVER_IMAGE_API') . '/imageUploadTinyMCEjs',
            'custom_configs' => [
                'browser_spellcheck' => true,
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
            ]
        ],

        'simple' => [
            'plugins' => 'autoresize directionality emoticons link wordcount',
            'toolbar' => 'removeformat | bold italic | rtl ltr | numlist bullist | link emoticons',
            'upload_directory' => null,
        ],

        'minimal' => [
            'plugins' => 'link wordcount',
            'toolbar' => 'bold italic link numlist bullist',
            'upload_directory' => null,
        ],

        'full' => [
            'plugins' => 'accordion autoresize codesample directionality advlist autolink link image lists charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media table emoticons template help',
            'toolbar' => 'undo redo removeformat | fontfamily fontsize fontsizeinput font_size_formats styles | bold italic underline | rtl ltr | alignjustify alignright aligncenter alignleft | numlist bullist outdent indent accordion | forecolor backcolor | blockquote table toc hr | image link anchor media codesample emoticons | visualblocks print preview wordcount fullscreen help',
            'upload_directory' => null,
        ],
    ],

    /**
     * this option will load optional language file based on you app locale
     * example:
     * languages => [
     *      'fa' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/fa.min.js',
     *      'es' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/es.min.js',
     *      'ja' => asset('assets/ja.min.js')
     * ]
     */
    'languages' => [],

    'extra' => [
        'toolbar' => [
            // 'fontsize' => '10px 12px 13px 14px 16px 18px 20px',
            // 'fontfamily' => 'Tahoma=tahoma,arial,helvetica,sans-serif;',
        ]
    ]
];
