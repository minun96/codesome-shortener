<x-mail::message>
# Your Weekly Statistics Digest
Summary of your click activity for the past week

- **Total Links Created:** {{ $stats['total_links'] }}
- **Total Clicks Recorded:** {{ $stats['total_clicks'] }}
- **Most Recent Click:** {{ $stats['last_click'] ? $stats['last_click']->created_at->toDayDateTimeString() : 'No clicks yet' }}

## Top 5 Click
<x-mail::table>
| Short URL | Original URL | Clicks |
| :------------- |:-------------| --------:|
@foreach($stats['top_links'] as $link)
| [{{ $link->short_url }}]({{ $link->short_url }}) | <a href="{{ $link->long_url }}" target="_blank"> {{ Str::limit($link->long_url, 30) }} </a>| {{ $link->clicks_count }} |
@endforeach
</x-mail::table>

</x-mail::message>
