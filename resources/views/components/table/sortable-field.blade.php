<a class="text-nowrap" href="{{ $url }}">
    <span class="text-nowrap">
        {{ $title }}
        @if ($sort == $field)
            @if ($order == 'asc') &darr; @else &uarr; @endif
        @else
            &#x21C5;
        @endif
    </span>
</a>
