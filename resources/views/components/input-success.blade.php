@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4']) }} id="success-message">
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
