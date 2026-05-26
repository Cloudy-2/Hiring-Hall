<x-app-layout>

    <x-slot name="pageTitle">Routes</x-slot>
    <x-slot name="return">{"link": "/users/manage", "text": "back"}</x-slot>
    <x-slot name="url_1">{"link": "/developer/routes", "text": "Routes & Privilege"}</x-slot>
    <x-slot name="active">Details</x-slot>
    <x-slot name="buttons"></x-slot>

    @php

        $routes = collect(Route::getRoutes())->map(function ($route) {
            return [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
            ];
        });
        $routes = $routes->sortByDesc('uri');
    @endphp

    <table class="border rounded-full"
        style="border-collapse: collapse; width:100%; font-family: monospace;">
        <thead>
            <tr class="border text-start">
                <th class="border">Method</th>
                <th class="border">URI</th>
                <th class="border">Name</th>
                <th class="border">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($routes as $route)
                <tr class="border">
                    <td class="border">{{ $route['method'] }}</td>
                    <td class="border">{{ $route['uri'] }}</td>
                    <td class="border">{{ $route['name'] }}</td>
                    <td class="border">{{ $route['action'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</x-app-layout>
