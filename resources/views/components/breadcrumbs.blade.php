@props(['links' => []])

<nav class="flex mb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-bronze-gold transition-colors duration-200">
                <i class="fa-solid fa-house mr-2 text-xs"></i>
                Inicio
            </a>
        </li>
        @foreach($links as $link)
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-chevron-right text-gray-400 text-[10px] mx-1"></i>
                    @if(isset($link['url']))
                        <a href="{{ $link['url'] }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-bronze-gold md:ml-2 transition-colors duration-200">{{ $link['label'] }}</a>
                    @else
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $link['label'] }}</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
