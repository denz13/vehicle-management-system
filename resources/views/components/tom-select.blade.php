@props([
    'id' => 'tom-select-' . uniqid(),
    'name' => 'tom-select',
    'placeholder' => 'Select options',
    'multiple' => false,
    'required' => false,
    'class' => 'w-full',
    'options' => [],
    'selected' => [],
    'disabled' => false,
    'search' => true,
    'clear' => true,
    'maxItems' => null,
    'plugins' => ['remove_button'],
    'autoInit' => true
])

<select 
    id="{{ $id }}"
    name="{{ $name }}{{ $multiple ? '[]' : '' }}"
    class="tom-select {{ $class }}"
    {{ $multiple ? 'multiple' : '' }}
    {{ $required ? 'required' : '' }}
    {{ $disabled ? 'disabled' : '' }}
    data-placeholder="{{ $placeholder }}"
    data-search="{{ $search ? 'true' : 'false' }}"
    data-clear="{{ $clear ? 'true' : 'false' }}"
    data-max-items="{{ $maxItems }}"
    data-plugins="{{ json_encode($plugins) }}"
    data-auto-init="{{ $autoInit ? 'true' : 'false' }}"
>
    @if(!empty($options))
        @foreach($options as $value => $label)
            <option 
                value="{{ $value }}" 
                {{ in_array($value, $selected) ? 'selected' : '' }}
            >
                {{ $label }}
            </option>
        @endforeach
    @endif
    
    {{ $slot }}
</select>

@if($autoInit)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.getElementById('{{ $id }}');
    if (selectElement && typeof TomSelect !== 'undefined') {
        try {
            // Initialize Tom Select with dynamic configuration
            const config = {
                placeholder: '{{ $placeholder }}',
                plugins: @json($plugins),
                maxItems: {{ $maxItems ?: 'null' }},
                allowEmptyOption: false,
                create: false,
                persist: false,
                closeAfterSelect: false,
                hideSelected: false,
                search: {{ $search ? 'true' : 'false' }},
                clear: {{ $clear ? 'true' : 'false' }}
            };

            // Add multiple-specific configuration
            @if($multiple)
            config.multiple = true;
            config.maxItems = {{ $maxItems ?: 'null' }};
            @endif

            selectElement.tomselect = new TomSelect('#{{ $id }}', config);
            console.log('Tom Select initialized for {{ $id }} with config:', config);
        } catch (error) {
            console.error('Error initializing Tom Select for {{ $id }}:', error);
        }
    } else {
        console.warn('Tom Select not available or element not found for {{ $id }}');
    }
});
</script>
@endif 