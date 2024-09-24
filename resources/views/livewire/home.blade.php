<main>

    @if(hasRoles('ADMINISTRATOR'))
        <livewire:home.admin />
    @endif

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

    @if(hasRoles('HR'))
        <livewire:home.hr />
    @endif

</main>
