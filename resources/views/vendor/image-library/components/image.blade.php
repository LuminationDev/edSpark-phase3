@php
	$attributes = $attributes->merge([
	    'data-image-library' => 'image',
	    'data-image-library-id' => Str::uuid(),
	]);
@endphp
<div class="flex flex-col gap-2">
<img
	src="{{ $src }}"
	srcset="{{ $srcset }}"
	sizes="1px"
	title="{{ $title }}"
	alt="{{ $alt }}"
	width="{{ $width }}"
	{{ $attributes }}
/>
    <p>{{$title}}</p>
</div>

