<style>
    .custom-pagination{
        list-style: none;
        display: inline;
    }
    .custom-pagination li{
        display: inline;
        margin: 4px;
       
    }
    .arrow{
    
        padding: 4px 12px;
        background-color: crimson;
        text-align: center;
        border-radius: 8px;
    }
    .arrow:hover{
        cursor: pointer;
    }

</style>

@if ($paginator->hasPages())
    <ul  class="custom-pagination">
        @if ($paginator->onFirstPage())
            <li class="disabled"><span>&laquo;</span></li>
        @else
            <li class="arrow"><a style="color:white; text-decoration:none;"  href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif
        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            @if ($page == $paginator->currentPage())
                <li class="active"><span>{{ $page }}</span></li>
            @else
                <li><a href="{{ $url }}">{{ $page }}</a></li>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="arrow"><a style="color:white; text-decoration:none;" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li class="disabled "><span>&raquo;</span></li>
        @endif
    </ul>
@endif
