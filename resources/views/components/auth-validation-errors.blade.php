@props(['errors'])

@if ($errors->any())
    <div {{ $attributes }} >
        <div class="font-medium fa-md text-white-50">
            <strong>{{ __('Whoops! Something went wrong.') }}</strong>
        </div>

        <ul class="mt-3 list-disc list-inside text-md  text-white-50">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
