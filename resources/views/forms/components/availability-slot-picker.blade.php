<x-forms::field-wrapper
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}').defer }">
        <!-- Interact with the `state` property in Alpine.js -->
        <div class="max-h-52 overflow-y-scroll">
            @forelse($getAvailableTimeSlotsProperty() as $slot)
                <input x-model="state"  type="radio" id="time_{{ $slot->timestamp }}"
                       value="{{ $slot->timestamp }}" class="sr-only fixed">
                <label for="time_{{ $slot->timestamp }}" :class="state == {{ $slot->timestamp }} ? 'bg-violet-200' : '' "
                       class="w-full text-left focus:outline-none hover:bg-gray-100 px-4 py-2 cursor-pointer flex items-center border-b border-gray-100">
                        <template x-if="state == {{ $slot->timestamp }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-700" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </template>
                    {{ $slot->translatedFormat('H:i') }}
                </label>
            @empty
                <div class="text-center text-gray-700 px-4 py-2">
                    Selecione um serviço
                </div>
            @endforelse
        </div>
</x-forms::field-wrapper>
