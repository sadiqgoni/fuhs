<div>
    {{-- Because she competes with no one, no one can compete with her. --}}

    <select wire:model.defer="selectedOrganization" wire:change="onOrganizationChange">
        @foreach (organizations() as $organization)
            <option value="{{ $organization->id }}">{{ $organization->name }}</option>
        @endforeach
    </select>
</div>
