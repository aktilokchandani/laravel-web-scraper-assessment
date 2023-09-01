<!DOCTYPE html>
<html>
<head>
    <title>Movies List</title>
    <!-- Include Bootstrap CSS link here -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="row mb-3">
        <div class="col">
            <h1>Movies List</h1>
        </div>
        <div class="col d-flex justify-content-end align-items-center">
            <a class="btn btn-sm btn-success" href="/scrape">Scrape Movies</a>
        </div>
    </div>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Title</th>
                <th>Year</th>
                <th>Rating</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movies as $movie)
                <tr>
                    <td>{{ $movie->title }}</td>
                    <td>{{ $movie->year }}</td>
                    <td>{{ $movie->rating }}</td>
                    <td class="text-center"><a class="btn btn-sm btn-primary" href="{{ $movie->url }}">View Details</a></td> <!-- Add this line -->
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Display pagination links -->
    
    {!! $movies->links() !!}
</div>

<!-- Include Bootstrap JS and any other scripts here if needed -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
