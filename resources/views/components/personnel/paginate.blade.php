@if ($paginator->hasPages())
    <div class="m-2">
        {{ $paginator->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
@endif
