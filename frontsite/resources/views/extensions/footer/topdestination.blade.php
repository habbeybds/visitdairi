<ul class="list-destination row">
    @foreach($config['footer_topdistination'] as $top)
    <li class="col-4 col-md-4 mb-3 pr-1">
        <a href="{{ $top['link'] }}">
            <img class="img-destination" src="{{ config('constants.IMAGE_PATH') . '/' . $top['image'] }}" alt="VisitDairi"/>
        </a>
    </li>
    @endforeach
</ul>