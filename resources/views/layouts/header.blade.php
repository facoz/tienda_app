<header>
    <nav>
        <ul>
            <li>
                <a href="{{route('home')}}" class="{{request()->routeIs('home') ? 'active' : ''}}">Inicio</a>
            </li>
            <li>
                <a href="{{route('order.view.all')}}" class="{{request()->routeIs('order.view.all') ? 'active' : ''}}">Todas las Ordenes</a>
            </li>
        </ul>
    </nav>
</header>