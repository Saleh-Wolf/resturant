<!DOCTYPE html>
<html>

<head>
    <title>Restaurant Menu</title>
</head>

<body>

    <h1>
        Welcome
    </h1>

    <h2>
        Table:
        {{ $table->table_number }}
    </h2>

    @foreach($categories as $category)

        <h3>
            {{ $category->name }}
        </h3>

        <ul>

            @foreach($category->menuItems as $item)

                <li>

                    {{ $item->name }}

                    -

                    {{ $item->price }}

                </li>

            @endforeach

        </ul>

    @endforeach

</body>

</html>