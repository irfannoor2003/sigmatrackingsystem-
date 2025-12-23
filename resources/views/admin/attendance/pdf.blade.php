<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 11px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .meta {
            text-align: center;
            margin-bottom: 15px;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #eee;
        }

        .status-present {
            font-weight: bold;
        }

        .status-leave {
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Attendance Report</h2>

<div class="meta">
    Generated on: {{ now()->format('d M Y') }}
</div>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Staff</th>
            <th>Status</th>
            <th>Clock In</th>
            <th>Clock Out</th>
            <th>Minutes</th>
            <th>Note</th>
        </tr>
    </thead>

    <tbody>
    @forelse($attendances as $a)
        <tr>
            <td>{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>
            <td>{{ $a->salesman->name }}</td>

            <td class="status-{{ $a->status }}">
                {{ ucfirst($a->status) }}
            </td>

            <td>
                {{ $a->clock_in
                    ? \Carbon\Carbon::parse($a->clock_in)->format('h:i A')
                    : '-' }}
            </td>

            <td>
                {{ $a->clock_out
                    ? \Carbon\Carbon::parse($a->clock_out)->format('h:i A')
                    : '-' }}
            </td>

            <td>{{ $a->total_minutes ?? 0 }}</td>
            <td>{{ $a->note ?: '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7">No attendance records found.</td>
        </tr>
    @endforelse
    </tbody>
</table>

</body>
</html>
