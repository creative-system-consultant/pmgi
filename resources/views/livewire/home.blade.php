<main>

    @if(hasRoles('PTT'))
        <livewire:home.ptt />
    @endif

    @if(hasRoles('PYD'))
        <livewire:home.pyd />
    @endif

    @if(hasRoles('PYM'))
        <livewire:home.pym />
    @endif

    @if(hasRoles('PMC'))
        <livewire:home.pmc />
    @endif

</main>
