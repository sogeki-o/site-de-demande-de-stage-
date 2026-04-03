@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Gestion des cahiers de charge</h2>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sujet</th>
                    <th>Description</th>
                    <th>Date de partage</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cahiers as $cahier)
                    <tr>
                        <td>{{ $cahier->id }}</td>
                        <td>{{ $cahier->sujet_stage }}</td>
                        <td>{{ $cahier->description }}</td>
                        <td>{{ $cahier->date_partage }}</td>
                        <td>
                            <a href="{{ route('service.cahiers.show', ['cahier' => $cahier->id]) }}"
                                class="btn btn-info btn-sm">Détail</a>
                            <a href="{{ route('service.cahiers.edit', ['cahier' => $cahier->id]) }}"
                                class="btn btn-warning btn-sm">Modifier</a>
                            <form action="{{ route('service.cahiers.destroy', ['cahier' => $cahier->id]) }}" method="POST"
                                style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Supprimer ce cahier ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
