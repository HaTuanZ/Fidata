{!! Theme::partial('header') !!}

<div id="app" class="wrapper_subarea">
    {!! Theme::content() !!}
</div>
<script>
    window.themeUrl = '{{ Theme::asset()->url('') }}';
    window.siteUrl = '{{ url('') }}';
    window.currentLanguage = '{{ App::getLocale() }}';
    window.apiUrl = '{{ env('API_URL') }}';
    window.tags = {!! Theme::get('tags') !!};
    window.coins = {!! Theme::get('coins') !!};
</script>
{!! Theme::footer() !!}
