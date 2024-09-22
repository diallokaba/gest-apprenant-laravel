<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            overflow: scroll; /* Scrollbar are always visible */
            overflow: auto
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
            word-wrap: break-word; /* Forcer le retour à la ligne si le texte est trop long */
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        /* Ajuster la largeur des colonnes */
        td, th {
            max-width: 100px; /* Limite la largeur à 100px pour chaque cellule */
        }
    </style>
</head>
<body>
    <h2>Exportation des données</h2>

    <table>
        <thead>
            <tr>
                @foreach(array_keys($data[0]) as $header)
                    <th>{{ ucfirst($header) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
