@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-green-600 bg-green-100 p-3 rounded space-y-1']) }} id="success-message">
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>

    <script>
        setTimeout(function() {
            let message = document.getElementById('success-message');
            if (message) {
                message.style.opacity = '0';
                setTimeout(() => message.style.display = 'none', 500); // Fade out effect
            }
        }, 5000); // 5 seconds
    </script>
@endif
