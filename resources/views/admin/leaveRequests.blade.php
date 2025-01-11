<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Leave Requests</h2>
    <table class="table table-bordered">
        <thead class="table-dark">
        <tr>
            <th>Staff Name</th>
            <th>Reason</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Document</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($leaveRequests as $request)
            <tr>
                <td>{{ $request->staff->name }}</td>
                <td>{{ $request->reason }}</td>
                <td>{{ $request->start_date }}</td>
                <td>{{ $request->end_date }}</td>
                <td>
                    @if($request->photo)
                        <a href="{{ asset('storage/' . $request->photo) }}" target="_blank">View Document</a>
                    @else
                        No Document
                    @endif
                </td>
                <td>{{ ucfirst($request->status) }}</td>
                <td>
                    @if($request->status === 'pending')
                        <form action="{{ route('admin.approveLeave', $request->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <form action="{{ route('admin.rejectLeave', $request->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    @else
                        <span class="text-muted">No Actions Available</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>