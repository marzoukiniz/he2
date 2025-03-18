<div class="flex justify-center btn-lang-top">
    @php
        $next_locale = $current_locale === 'en' ? 'ar' : 'en';
    @endphp
    <a href="{{ url('language/' . $next_locale) }}" class="  bg-gray-200 rounded-md focus:outline-none">
        {{ $next_locale === 'en' ? 'EN' : 'ع' }}
    </a>
</div>