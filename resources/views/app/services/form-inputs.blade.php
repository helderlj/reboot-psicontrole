@php $editing = isset($service) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.text
            name="name"
            label="Name"
            value="{{ old('name', ($editing ? $service->name : '')) }}"
            maxlength="255"
            placeholder="Name"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.number
            name="duration"
            label="Duration"
            value="{{ old('duration', ($editing ? $service->duration : '')) }}"
            max="255"
            placeholder="Duration"
            required
        ></x-inputs.number>
    </x-inputs.group>
</div>
