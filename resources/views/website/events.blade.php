<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events — Student of the Year</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f9;
            color: #1f1a33;
        }

        .events-hero {
            background: linear-gradient(135deg, #2C1459, #4a2a8a);
            color: #fff;
            padding: 70px 20px 50px;
            text-align: center;
        }

        .events-hero h1 {
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .events-hero p {
            color: #d8cef0;
            font-size: 15px;
        }

        .events-wrap {
            max-width: 1100px;
            margin: -30px auto 60px;
            padding: 0 20px;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }

        .event-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(44, 20, 89, 0.08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .event-card-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .event-category {
            display: inline-block;
            width: fit-content;
            background: #ffcb57;
            color: #2C1459;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 4px 12px;
            border-radius: 999px;
        }

        .event-card h3 {
            font-size: 19px;
            font-weight: 700;
            color: #2C1459;
        }

        .event-meta {
            font-size: 13px;
            color: #6b6480;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .event-meta span strong {
            color: #1f1a33;
        }

        .event-card-footer {
            padding: 18px 24px;
            border-top: 1px solid #f0edf7;
        }

        .event-btn {
            display: inline-block;
            width: 100%;
            text-align: center;
            background: #2C1459;
            color: #fff;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .event-btn:hover {
            background: #3d1c7a;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #837b9e;
        }
    </style>
</head>
<body>

    @include('website.components.header')

    <section class="events-hero">
        <h1>Upcoming &amp; Past Events</h1>
        <p>Stay updated with everything happening at Student of the Year</p>
    </section>

    <div class="events-wrap">
        @if($events->isEmpty())
            <div class="empty-state">
                <p>No events published yet. Please check back soon.</p>
            </div>
        @else
            <div class="events-grid">
                @foreach($events as $event)
                    <div class="event-card">
                        <div class="event-card-body">
                            @if($event->category)
                                <span class="event-category">{{ $event->category }}</span>
                            @endif
                            <h3>{{ $event->name }}</h3>
                            <div class="event-meta">
                                @if($event->start_date)
                                    <span><strong>Starts:</strong> {{ $event->start_date->format('d M Y, h:i A') }}</span>
                                @endif
                                @if($event->end_date)
                                    <span><strong>Ends:</strong> {{ $event->end_date->format('d M Y, h:i A') }}</span>
                                @endif
                                @if($event->classLevel)
                                    <span><strong>Class:</strong> {{ $event->classLevel->name }}</span>
                                @endif
                                @if($event->season)
                                    <span><strong>Season:</strong> {{ $event->season->name }}</span>
                                @endif
                            </div>
                        </div>
                        @if($event->url)
                            <div class="event-card-footer">
                                <a href="{{ $event->url }}" target="_blank" rel="noopener" class="event-btn">View Details</a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @include('website.components.footer')

</body>
</html>
